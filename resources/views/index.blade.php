<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 
<head>
    <meta charset="UTF-8">
    <title>Sanctum</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset('css/app.css') }}" type="text/css" rel="stylesheet"/>
        <link rel="stylesheet" href="https://bootswatch.com/4/darkly/bootstrap.css">
  <link crossorigin=""
        href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
        integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
        rel="stylesheet">
  <title><%= htmlWebpackPlugin.options.title %></title>
 


</head>
 
<body>

    <div id="app">
       <app></app>
    </div>
</body>
 
</html>