@php
    $name = is_object($product) ? $product->sku : $product;
    $fontSize = 220;
    $nameLen = strlen($name);
    switch(true) {
        case strlen($name) > 35:
                $fontSize = 70;
            break;
        case $nameLen > 24 && $nameLen < 35 :
            $fontSize = 93;
            break;
        case strlen($name) < 4:
            break;
        default:
            $fontSize = ($fontSize - (strlen($name) * 12));
            break;
    }

@endphp
<html lang="en-us">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

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
    <link rel="icon" type="image/png" sizes="32x32" href="{{ secure_asset('assets/favicons/favicons-32x32.png') }} ">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ secure_asset('assets/favicons/favicons-96x96.png') }} ">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ secure_asset('assets/favicons/favicons-16x16.png') }} ">
    <link rel="manifest" href="{{ secure_asset('assets/favicons/manifest.json') }} ">

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ secure_asset('assets/favicons/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('css/app.css')}}"/>
    <script src="https://cdn.rawgit.com/serratus/quaggaJS/0420d5e0/dist/quagga.min.js"></script>

    <title>{{$name}} | Label</title>
    <!-- Fonts -->
    <style>
        @page {
            size: landscape;
            overflow: hidden;
            page-break-after: always;
        }

        #sku {
            color: black;
            font-family: 'Open Sans', sans-serif;
            font-size: {{$fontSize}}   !important;
        }

        * {
            font-family: monospace;
            font-size: 18pt;
            line-height: 1;
            color: black;
        }

        .table thead th {
            border-top-color: black !important;
            border-bottom-color: black !important;
        }

        /*@media only screen {*/
        /*    html {*/
        /*        background: #636b6f;*/
        /*    }*/

        /*    * {*/
        /*        font-family: monospace;*/
        /*        font-size: 20pt;*/
        /*        color: black;*/
        /*    }*/

        /*    body {*/
        /*        background: white;*/
        /*        width: 6in;*/
        /*        height: 4in;*/
        /*        margin-left: auto;*/
        /*        margin-right: auto;*/
        /*    }*/

        /*    #container {*/
        /*        margin: 0;*/
        /*    }*/

        /*    #sku {*/
        /*        font-size: 60px;*/
        /*    }*/

        /*    .table th, .table td, .table td b {*/
        /*        font-size: 35px;*/
        /*    }*/
        /*}*/
    </style>
</head>
<body data-gr-c-s-loaded="true">
<div id="container">
    <div class="p-1 text-center">
        <h1 id="sku" class="text-center" style="

                margin: 0 auto;
                font-weight: 900;
                ">{{strtoupper($name)}}</h1>
    </div>

    @if (is_object($product) && is_object($product->pallet))
        <div class="row">
            <div class="col col-sm-12 text-center">
                <table class="table" style="position: fixed; bottom: -2pt; width: 95%; left: 2.5%;">
                    <thead>
                    <tr>
                        <th class="text-center" colspan="2" style="border: 2px solid black; font-size: 30pt">
                            CARTONS: {{$product->pallet->cartonqty}} PCS
                        </th>
                    </tr>
                    <tr>
                        <th rowspan="2" class="text-center text-uppercase font-weight-bold p-1"
                            style="vertical-align: middle; border: 2px solid black; font-size: 16pt;">{{\Carbon\Carbon::now()->format("F j, Y")}}</th>
                        <th class="text-center p-1" style="border: 2px solid black; font-size: 16pt"><b>Total
                                PCS </b> {{$product->pallet->totalpcs}} PCS
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    @endif
</div>
</body>
</html>
