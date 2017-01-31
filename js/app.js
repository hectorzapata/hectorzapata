var pause = false;
var lat = 23.7348644; long = -99.1430382, n = 1;
var myLatlng = new google.maps.LatLng(lat, long);
var directionsDisplay = new google.maps.DirectionsRenderer;
var mapOptions = {
  zoom: 13,
  center: myLatlng
}
var intervalo, taimaut, i, to;
var map, datos, totales;

$(document).ready(function() {
  //Datos obtenidos desde el controlador
  datos = $.parseJSON($('#datos').val());
  map = new google.maps.Map(document.getElementById("map"), mapOptions);
  directionsDisplay.setMap(map);
  //pinto marcadores
  $.each(datos.clientes, function(index, el) {
    el.marcador = pintarMarcador(new google.maps.LatLng(el.lat, el.lng), el.tipo, el.nombre);
  });
  $.each(datos.conductores, function(index, el) {
    el.marcador = pintarMarcador(new google.maps.LatLng(el.lat, el.lng), el.tipo, el.nombre);
    //debido a la limitante de OVER_QUERY_LIMIT tuve que usar contadores individuales
    el.contador = 0;
    el.totales = 0;
  });
  //método principal que dispara los metodos para comparar los clientes con los conductores
  calcularRutas();
});

function pintarMarcador(latLng, tipo, nombre) {
  leibol = 'img/cliente.png';
  if (tipo == "co")
    leibol = 'img/conductor.png';

  var ok = new google.maps.Marker({
    position: latLng,
    icon: leibol,
    title: nombre,
    map: map
  });
  return ok;
}
//recorre los conductores y calcula cada ruta individualmente con respecto a cada cliente
function calcularRutas() {
  $.each(datos.conductores, function(key, co) {
    co.totales = Object.keys(datos.clientes).length;
    datos.conductores[key].rutas = {};
    $.each(datos.clientes, function(index, cl) {
      datos.conductores[key].rutas[index] = {};
      calcularRuta(co.marcador.getPosition(), cl.marcador.getPosition(), co.nombre, cl.nombre);
    });
  });
}
//crea una ruta y la almacena en el arreglo principal
function calcularRuta(origen, destino, coNombre, clNombre) {
  ds = new google.maps.DirectionsService;
  ds.route({
    origin: origen,
    destination: destino,
    travelMode: google.maps.TravelMode.WALKING
  }, function(response, status) {
    if (response && status == 'OK') {
      var tmp = datos.conductores[coNombre];
      tmp.contador++;
      tmp.rutas[clNombre].response = response;
      tmp.rutas[clNombre].distancia = response.routes[0].legs[0].distance;
      tmp.rutas[clNombre].tiempoLlegada = response.routes[0].legs[0].duration;
      //asigna el avance a la progress bar
      $('#tr_' + coNombre + ' .porcentaje').progress({
        percent: parseInt((tmp.contador * 100) / tmp.totales)
      });
      //si ya se compararon todas las rutas se compara cual es la menor en distancia
      if (tmp.contador == tmp.totales){
        var rutas = datos.conductores[coNombre].rutas;
        var menor = Object.keys(rutas)[0];
        $.each(rutas, function(index, el) {
          if (el.distancia.value < rutas[menor].distancia.value)
            menor = index;
        });
        menor = {"cmc": menor, "d": rutas[menor].distancia.text, "tdl": rutas[menor].tiempoLlegada.text};
        //una vez que se obtiene la menor, se pinta la fila en la tabla
        llenarFila(menor, coNombre);
      }
    }
    if (status === google.maps.DirectionsStatus.OVER_QUERY_LIMIT)
      //esperamos 200 ms para volver a solicitar la ruta debido al OVER_QUERY_LIMIT de la version gratuita de la api
      setTimeout(function() {
        calcularRuta(origen, destino, coNombre, clNombre);
      }, 200);
    else if (status !== google.maps.DirectionsStatus.OK)
      console.log('Directions request failed due to ' + status, coNombre, clNombre);
  });
}
//renderiza rutas
function mostrarRuta(el) {
  if ($(el).hasClass('loading'))
    return;
  var cliente = $(el).parent().siblings('.cmc').html();
  var conductor = $(el).parent().siblings('.n').html();
  if (directionsDisplay.getMap() == null)
    directionsDisplay.setMap(map);

  directionsDisplay.setDirections(datos.conductores[conductor].rutas[cliente].response);
}
//actualiza la fila correspondiente en la tabla
function llenarFila(menor, conductor) {
  $('#tr_' + conductor + ' .cmc').html(menor.cmc);
  $('#tr_' + conductor + ' .d').html(menor.d);
  $('#tr_' + conductor + ' .tdl').html(menor.tdl);
  $('#tr_' + conductor + ' .button').toggle();
  $('#tr_' + conductor + ' .porcentaje').toggle();
  //Si ya no hay filas cargando rutas avanza con dejar pasar 10 seg y luego hace la petición para mover los actores
  if ($('.porcentaje:visible').length == 0) {
    taimaut = 10000;
    i = 1;
    $('.pausar').show();
    avanzar();
  }
}
//funcion que controla el intérvalo y el tiempo muerto para hacer la peticion que mueve los actores
function avanzar() {
  intervalo = setInterval(function () {
    i++;
    $('.barraEspera').progress({
      percent: i
    });
  }, 100);
  to = setTimeout(function () {
    directionsDisplay.setMap(null);
    $.ajax({
      url: rutaAvanzar,
      type: 'GET'
    })
    .done(function(r) {
      repintar(r);
    })
    .fail(function(r) {
      console.log("error", r);
    });
    clearInterval(intervalo);
  }, taimaut);
}
//funcion que pausa la ejecución y evita que se recarguen las posiciones
function pausar(el) {
  //Si no hay ninguna ruta cargando
  if ($('.acciones .porcentaje:visible').length == 0) {
    //si dió click en el botón de pausar se cambian los botones y se detiene el intérvalo y el tiempo muerto
    if ($(el).hasClass('pausar')) {
      $(el).hide();
      $('.reanudar').show();
      clearInterval(intervalo);
      clearTimeout(to);
    }else{ // si dió click en el de reanudar
      $(el).hide();
      $('.pausar').show();
      taimaut = 10000 - (i * 100);
      avanzar();
    }
  }
}
//es llamada cuándo se obtiene respuesta de la función avanzar
function repintar(r) {
  $.each(r, function(index, el) {
    //si es cliente solamente pinto su nueva posición
    if (el.tipo == 'cl') {
      var actor = datos.clientes[el.nombre];
      if (actor) {
        var nuevaPosicion = new google.maps.LatLng(el.lat, el.lng);
        actor.marcador.setPosition(nuevaPosicion);
      }
    }else{
      //si es conductor pinto su nueva posición y oculto los botones para mostrar rutas
      var actor = datos.conductores[el.nombre];
      if (actor) {
        var nuevaPosicion = new google.maps.LatLng(el.lat, el.lng);
        actor.marcador.setPosition(nuevaPosicion);
        $('#tr_' + actor.nombre + ' .button').toggle();
        $('#tr_' + actor.nombre + ' .porcentaje').toggle();
        $('#tr_' + actor.nombre + ' .porcentaje').progress({
          percent: 0
        });
        actor.contador = 0;
      }
    }
  });
  //una vez recorridos todos los actores procedo a llamar el método principal y volver a hacer los cálculos
  calcularRutas();
}
