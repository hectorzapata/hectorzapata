<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Héctor Zapata - Prueba Técnica</title>
    <link href="https://fonts.googleapis.com/css?family=Arsenal:400,400i,700,700i" rel="stylesheet">
    <link rel="stylesheet" href="/{{getenv('NOMBRE_DIRECTORIO')}}/semantic/semantic.min.css" media="screen" title="no title" charset="utf-8">
    <style media="screen">
      body *{
        font-family: 'Arsenal';
      }
    </style>
    @yield('estilos')
  </head>
  <body>
    @yield('contenido')
    <script src="/{{getenv('NOMBRE_DIRECTORIO')}}/js/jquery-3.1.1.min.js" charset="utf-8"></script>
    <script src="/{{getenv('NOMBRE_DIRECTORIO')}}/semantic/semantic.min.js" charset="utf-8"></script>
    @yield('scripts')
  </body>
</html>
