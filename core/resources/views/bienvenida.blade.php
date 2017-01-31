@extends('main')
@section('estilos')
  <link rel="stylesheet" href="/{{getenv('NOMBRE_DIRECTORIO')}}/css/bienvenida.css" media="screen" title="no title" charset="utf-8">
@endsection
@section('contenido')
  <div class="full dFlex">
    <div class="contenedorInicio">
      <div class="yo">
        <div class="btnEntrar dFlex" id="iniciar">Iniciar</div>
      </div>
      <div class="titulos">
        <div>Prueba Técnica</div>
        <div>Héctor Eduardo Zapata García</div>
      </div>
    </div>
  </div>
  <div class="ui small modal" id="modalCantidad">
    <div class="header">
      Iniciar Aplicacion
    </div>
    <div class="content">
      <p>Defina el número de conductores y clientes a intervenir en la aplicación</p>
      <form class="ui form" method="post" action="app">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="field">
          <label>Conductores</label>
          <input type="number" name="conductores" value="">
        </div>
        <div class="field">
          <label>Clientes</label>
          <input type="number" name="clientes" value="">
        </div>
        <button class="ui button" type="submit">Iniciar</button>
      </form>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
  $(document).ready(function() {
    $('#iniciar').click(function(event) {
      $('#modalCantidad').modal({
        closable  : false,
        onApprove : function() {
          $('.full').hide('slow', function() {
            $('#appContenedor').show('slow');
          });
        }
      }).modal('show');
    });
    $('.ui.form').form({
      on: 'submit',
      inline: true,
      fields: {
        conductores: {
          identifier  : 'conductores',
          rules: [{
            type   : 'empty',
            prompt : 'Por favor, introduzca un número de conductores'
          }]
        },
        clientes: {
          identifier  : 'clientes',
          rules: [{
            type   : 'empty',
            prompt : 'Por favor, introduzca un número de clientes'
          }]
        }
      },onFailure(formErrors, fields){
        $('#modalCantidad').modal('refresh');
        return false;
      }
    });
  });
  </script>
@endsection
