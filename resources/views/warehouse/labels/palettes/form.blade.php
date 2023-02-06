<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    <link rel="icon" type="image/png" sizes="192x192" href="{{ secure_asset('assets/favicons/android-icon-192x192.png') }} ">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ secure_asset('assets/favicons/favicons-32x32.png') }} ">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ secure_asset('assets/favicons/favicons-96x96.png') }} ">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ secure_asset('assets/favicons/favicons-16x16.png') }} ">
    <link rel="manifest" href="{{ secure_asset('assets/favicons/manifest.json') }} ">

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ secure_asset('assets/favicons/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <title>Palette Labels | {{env('APP_NAME')}}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,200,600,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('css/app.css')}}"/>
    <script src="https://cdn.rawgit.com/serratus/quaggaJS/0420d5e0/dist/quagga.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        #scanner-container {
            position: relative;
        }

        canvas.drawing, canvas.drawingBuffer {
            position: absolute;
            left: 0;
            top: 0;
        }

        #product {
            display: none;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row pt-4">
        <div class="col col-sm-12 col-md-6 offset-md-3">
            <img src="{{secure_asset('/images/skar-audio-logo-black.svg')}}">
            <form action="/warehouse/pallets" method="post" id="palletsForm">
                @csrf
                <input hidden name="download" value="true">
                <div class="form-group text-left">
                    <label for="exampleInputEmail1">Sku Number</label>
                    <div class="input-group">
                        <input type="text" name="sku" id="skuInput" class="form-control form-control-lg"  required>
                        <div class="input-group-append">
                            <button type="button" id="submit" class="btn btn-secondary" type="button"
                                    style="min-width: 120px">Print
                            </button>
                        </div>
                    </div>
                    <small id="emailHelp" class="form-text text-muted">Enter the Sku Number and Hit Print
                        {{--                            or Click button below to scan barcode.--}}
                    </small>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container">
    <div class="row" id="product">
        <div class="col col-sm-12 col-md-6 offset-md-3">
            <div class="row">
                <div class="col col-sm-12 col-md-6">
                    <img src="" id="imageurl" class="img-fluid"/>
                </div>
                <div class="col col-sm-12 col-md-6">
                    <h3 id="sku">Title</h3>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="text-center">Total PCS</th>
                                <th class="text-center">Carton QTY</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td id="totalpcs" class="text-center"></td>
                                <td id="cartonqty" class="text-center"></td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="text-center">Length</th>
                                <th class="text-center">Width</th>
                                <th class="text-center">Height</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td id="length" class="text-center"></td>
                                <td id="width" class="text-center"></td>
                                <td id="height" class="text-center"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
<script>
    $(function () {
        $('#submit').on('click', function (e) {
            if ($("#skuInput").val() !== "") {
                e.preventDefault();
                $("#product").fadeOut();
                var param = $("#palletsForm").serializeArray();
                $.ajax({
                    'url': '/warehouse/pallets',
                    'method': 'POST',
                    'data': param
                }).done(function (response) {
                    console.log(response);
                    var pallet = response.product.pallet;
                    for (var val in pallet) {
                        if (val === 'imageurl') {
                            $('#' + val).attr('src', pallet[val]);
                        } else {
                            var domObj = $("#" + val);
                            if (domObj.length) {
                                $("#" + val).html(pallet[val]);
                            }
                        }
                    }
                    $("#product").fadeIn();

                    download(response.product.sku+".pdf", response.pdf)
                });
            } else {
                alert("SKU Required.")
            }
        });

        function download(filename, text) {
            var element = document.createElement('a');
            element.setAttribute('href', 'data:application/octet-stream;base64,' + encodeURIComponent(text));
            element.setAttribute('download', filename);

            element.style.display = 'none';
            document.body.appendChild(element);

            element.click();

            document.body.removeChild(element);
        }
    });

</script>
</body>
</html>
