@extends('main')
@section('estilos')
  <link rel="stylesheet" href="/{{getenv('NOMBRE_DIRECTORIO')}}/css/app.min.css">
@endsection
@section('contenido')
  <input type="hidden" id="datos" value="{{json_encode($datos)}}">
  <div class="ui grid container">
    <div class="ui blue labeled icon button pausar" onclick="pausar(this)" style="display: none;">
      <i class="pause icon"></i>
      Pausar
    </div>
    <div class="ui blue labeled icon button reanudar" onclick="pausar(this)" style="display: none;">
      <i class="repeat icon"></i>
      Reanudar
    </div>
    <div class="sixteen wide column">
      <div class="ui tiny teal progress barraEspera">
        <div class="bar"></div>
      </div>
      <div id="map"></div>
    </div>
    <div class="sixteen wide column">
      <table id="example" class="ui celled table" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Cliente más cercano</th>
            <th>Distancia</th>
            <th>Tiempo de llegada</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($datos['conductores'] as $key => $value)
            <tr id="tr_{{$value->nombre}}">
              <td class="n">{{$value->nombre}}</td>
              <td class="cmc"></td>
              <td class="d"></td>
              <td class="tdl"></td>
              <td class="acciones">
                <div class="ui teal progress porcentaje">
                  <div class="bar"></div>
                </div>
                <div class="ui green button" onclick="mostrarRuta(this)" style="display: none;">
                  Mostrar Ruta
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
@section('scripts')
  <script type="text/javascript">
    var rutaAvanzar = '/{{getenv('NOMBRE_DIRECTORIO')}}/nuevasPosiciones';
  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key={{getenv('API_KEY_MAPS')}}"></script>
  <script src="/{{getenv('NOMBRE_DIRECTORIO')}}/js/app.min.js" charset="utf-8"></script>
@endsection
