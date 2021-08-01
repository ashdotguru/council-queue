<!doctype html>
<html
  lang="{{ str_replace('_', '-', app()->getLocale()) }}"
  class="text-gray-900 font-sans"
>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <title>Council Queue</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo:wght@600;800&family=Source+Sans+Pro&display=swap" rel="stylesheet">
  </head>

  <body class="min-h-screen bg-gray-50">
    <div id="app"></div>
  
    <script src="{{ mix('js/app.js') }}"></script>
  </body>
</html>