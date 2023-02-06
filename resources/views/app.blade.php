<!DOCTYPE html>
<html lang="en-us">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1 minimum-scale=1">

        <link rel="apple-touch-icon" sizes="57x57" href="{{ secure_asset('assets/favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ secure_asset('assets/favicons/apple-icon-60x60.png') }} ">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ secure_asset('assets/favicons/apple-icon-72x72.png') }} ">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ secure_asset('assets/favicons/apple-icon-76x76.png') }} ">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ secure_asset('assets/favicons/apple-icon-114x114.png') }} ">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ secure_asset('assets/favicons/apple-icon-120x120.png') }} ">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ secure_asset('assets/favicons/apple-icon-144x144.png') }} ">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ secure_asset('assets/favicons/apple-icon-152x152.png') }} ">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ secure_asset('assets/favicons/apple-icon-180x180.png') }} ">
        <link rel="icon" type="image/png" sizes="192x192"
              href="{{ secure_asset('assets/favicons/android-icon-192x192.png') }} ">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ secure_asset('assets/favicons/favicon-32x32.png') }} ">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ secure_asset('assets/favicons/favicon-96x96.png') }} ">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ secure_asset('assets/favicons/favicon-16x16.png') }} ">
        <link rel="manifest" href="{{ secure_asset('assets/favicons/manifest.json') }} ">

        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ secure_asset('assets/favicons/ms-icon-144x144.png') }}">
        <meta name="theme-color" content="#ffffff">
        <title>{{env('APP_NAME')}}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.14.0/css/all.css" integrity="sha384-VhBcF/php0Z/P5ZxlxaEx1GwqTQVIBu4G4giRWxTKOCjTxsPFETUDdVL5B6vYvOt" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/a7571620fd.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="{{mix('css/app.css')}}"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nvd3/1.8.1/nv.d3.min.css"/>
    </head>
    <body>
        <div id="container">
            <router-view></router-view>
        </div>
        <script src="{{ mix('js/main.js') }}"></script>
        <script src="https://js.pusher.com/6.0/pusher.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
        <script src="{{ secure_asset('assets/js/pace.js') }}"></script>
    </body>
</html>
