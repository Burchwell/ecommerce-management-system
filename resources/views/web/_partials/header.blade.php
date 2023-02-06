<!DOCTYPE html>
<html lang="en-us">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/favicons/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/favicons/apple-icon-60x60.png') }} ">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/favicons/apple-icon-72x72.png') }} ">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/favicons/apple-icon-76x76.png') }} ">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/favicons/apple-icon-114x114.png') }} ">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/favicons/apple-icon-120x120.png') }} ">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/favicons/apple-icon-144x144.png') }} ">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/favicons/apple-icon-152x152.png') }} ">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicons/apple-icon-180x180.png') }} ">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('assets/favicons/android-icon-192x192.png') }} ">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicons/favicons-32x32.png') }} ">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/favicons/favicons-96x96.png') }} ">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicons/favicons-16x16.png') }} ">
    <link rel="manifest" href="{{ asset('assets/favicons/manifest.json') }} ">

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('assets/favicons/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <title>Warehouse Daily Tasks | {{env('APP_NAME')}}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,200,600,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}"/>
    <script
        src="https://code.jquery.com/jquery-3.5.1.js"
        integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
        crossorigin="anonymous"></script>
    <style>
        img {
            width: 100%;
            height: auto;
        }

        .logo {
            max-width: 170px;
        }

        .navbar-brand {
            margin-left: 70px;
        }

        .bg-dark {
            background: black !important;
        }

        body {
            background: #dfdfdf;
        }

        h1 {
            font-size: 1.75rem;
        }

        h2 {
            font-size: 1.5rem;
        }

        h3 {
            font-size: 1.25rem;
        }

        .container {
            padding-bottom: 3rem;
        }

        .container {
            background: #f9f9f9;
            padding-bottom: 3rem;
        }

        input[type='checkbox'] {
            opacity: 0;
        }

        .form-row {
            border: 1px solid #c8c8c8;
            margin-top: 10px;
            font-size: 14px;
            line-height: 28px;
            display: block;
            margin: .5em 0;
            padding: .5em 1em;
            cursor: pointer;
        }

        .form-check-input[type="checkbox"] + label, label.btn input[type="checkbox"] + label {
            position: relative;
            display: inline-block;
            height: 1.5625rem;
            padding-left: 35px;
            line-height: 1.5625rem;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            color: #308596;
        }

        .form-check-input:not(:checked), .form-check-input:checked {
            position: absolute;
            pointer-events: none;
            opacity: 0;
        }

        [type="checkbox"]:not(:checked), [type="checkbox"]:checked {
            position: absolute;
            pointer-events: none;
            opacity: 0;
        }

        .form-check-input[type="checkbox"] + label:before, .form-check-input[type="checkbox"]:not(.filled-in) + label:after, label.btn input[type="checkbox"] + label:before, label.btn input[type="checkbox"]:not(.filled-in) + label:after {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 0;
            width: 18px;
            height: 18px;
            margin-top: 3px;
            content: "";
            border: 1px solid #8a8a8a;
            border-radius: 1px;
            -webkit-transition: .2s;
            transition: .2s;
        }

        .form-check-input[type="checkbox"]:checked + label:before, label.btn input[type="checkbox"]:checked + label:before {
            top: -4px;
            left: -5px;
            width: 12px;
            height: 1.375rem;
            border-top: 2px solid transparent;
            border-right: 2px solid #4285f4;
            border-bottom: 2px solid #4285f4;
            border-left: 2px solid transparent;
            -webkit-transform: rotate(40deg);
            transform: rotate(40deg);
            -webkit-transform-origin: 100% 100%;
            transform-origin: 100% 100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
        }

        .form-check-input[type="checkbox"] + label:before, .form-check-input[type="checkbox"]:not(.filled-in) + label:after, label.btn input[type="checkbox"] + label:before, label.btn input[type="checkbox"]:not(.filled-in) + label:after {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 0;
            width: 18px;
            height: 18px;
            margin-top: 3px;
            content: "";
            border: 2px solid #8a8a8a;
            border-radius: 1px;
            -webkit-transition: .2s;
            transition: .2s;
        }

        .form-check-input[type="checkbox"]:not(.filled-in) + label:after, label.btn input[type="checkbox"]:not(.filled-in) + label:after {
            border: 0;
            -webkit-transform: scale(0);
            transform: scale(0);
        }

        #completedby option {
            color: #636b6f;
            font-family: "Noto Sans", "Helvetica Neue", Arial, "Lucida Grande", Tahoma, Verdana, sans-serif;
            font-size: 18px;
            margin: 5px 0;
        }

        select {
            -webkit-appearance: none;
            text-align-last:center;
        }

        @media screen and (max-width: 575px) {
            .col-sm-2 {
                width: 32% !important;
                display: inline-block;
            }

            .col-sm-1 {
                width: 20% !important;
                display: inline-block;
            }

            .col-sm-12 {
                width: 100% !important;
            }

            .form-group {
                margin-bottom: 0;
            }
        }
    </style>
</head>
<body>

