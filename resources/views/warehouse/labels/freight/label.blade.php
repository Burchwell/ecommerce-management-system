@php
if ($data['pallets'] === null) {
    $data['pallets'] = 1;
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

    <title>{{$data['po_number']}} | Label</title>
    <!-- Fonts -->
    <style>
        html, body {
            background: white;
        }
        @page {
            size: landscape;
            overflow-x: hidden;
            page-break-after: auto;
            max-height: 4in;
            width: 6in;
        }

        * {
            font-family: monospace;
            font-size: 18pt;
            line-height: 1;
            color: black;
        }

        .pagebreak:not(:first-child) {
            page-break-before: always;
        }

        .pagebreak > * {
            padding: 0 12.5pt;
            text-align: center;
        }

        h4 {
            font-size: 2rem;
        }

        h2 {
            font-size: 3rem;
        }
    </style>
</head>
<body data-gr-c-s-loaded="true">
    @for($i=1;$i<=$data['pallets'];$i++)
        @for ($j=1;$j<=$data['labels'];$j++)
            <div class="pagebreak" style="max-width: 750px">
                <h4>Pallet {{$i}} of {{$data['pallets']}}</h4>
                <h2 style="font-weight: bold">{{$data['carrier']}}</h2>
                <h5>Pickup Date: {{ date('m/d/Y', strtotime($data['pickup_date'])) }}</h5>
                <br/>
                <p id="vendor">
                    {{$data['vendor']}}
                    <br/>
                    P.O. #: {{$data['po_number']}}
                </p>
                <p >{{$data['address']}}</p>
            </div>
        @endfor
    @endfor
</body>
</html>
