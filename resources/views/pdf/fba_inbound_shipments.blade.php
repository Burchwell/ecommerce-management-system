
<!DOCTYPE html>
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
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ secure_asset('assets/favicons/android-icon-192x192.png') }} ">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ secure_asset('assets/favicons/favicon-32x32.png') }} ">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ secure_asset('assets/favicons/favicon-96x96.png') }} ">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ secure_asset('assets/favicons/favicon-16x16.png') }} ">
    <link rel="manifest" href="{{ secure_asset('assets/favicons/manifest.json') }} ">

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ secure_asset('assets/favicons/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <title>{{$name ?? ''}}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,200,600,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{secure_asset('css/app.css')}}"/>
    <style>
        .table-striped td, .table-striped th {
            border: 1px solid #dee2e6;
            vertical-align: middle !important;
            padding: 5px;
        }

        table { page-break-inside: auto } tr { page-break-inside: avoid; page-break-after: auto } thead { display: table-header-group } tfoot { display: table-footer-group }

        @media print {
            .element-that-contains-table {
                overflow: visible !important;
            }
        }
    </style>
</head>
<body style="background: white">
<div class="container">
    <div class="row my-2">
        <div class="col col-sm-12">
            <img src="{{secure_asset('images/skar-audio-logo-black.jpg')}}" style="width: 150px; margin-top: 20px; float: right"/>
            <table class="table table-inline table-sm table-borderless" style="width: 75%">
                <colgroup>
                    <col width="25%"/>
                </colgroup>
                <tr>
                    <th>Shippment ID</th>
                    <td>{{$data->shipment_id}}</td>
                </tr>
                <tr>
                    <th>Shipment Name</th>
                    <td>{{$data->shipment_name}}</td>
                </tr>
                <tr>
                    <th>Destination</th>
                    <td>
                        <b>{{$data->warehouse->name}} ({{$data->warehouse->fba_code}})</b>, <br/>
                        {{$data->warehouse->address}}, <br/>
                        {{$data->warehouse->city}}, {{$data->warehouse->state}}, {{$data->warehouse->zipcode}}</td>
                </tr>
            </table>
        </div>
        <div class="col col-sm-6">

        </div>
    </div>
    <div class="row">
        <div class="col col-sm-12">
            <div class="element-that-contains-table">
                <table class="table table-striped table-sm">
                    <colgroup>
                        <col width="20%"/>
                        <col width="10%"/>
                        <col width="12%"/>
                        <col width="17%"/>
                        <col width="17%"/>
                    </colgroup>
                    <thead>
                    <tr>
                        <th class="v-align-middle">
                            Merchant SKU
                        </th>
                        <th class="v-align-middle text-center">
                            Total PCS
                        </th>
                        <th class="v-align-middle text-center">
                            Puller Initial
                        </th>
                        <th class="v-align-middle text-center">
                            Prep Type I
                        </th>
                        <th class="v-align-middle text-center">
                            Prep Type II
                        </th>
                        <th class="v-align-middle text-center">
                            Notes
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data->items as $row)
                        @php
                            $notes = [];

                            if ($row->product->weight > 50) {
                                $notes[] = "Team Lift Sticker";
                            }
                        @endphp
                    <tr>

                        <td>{{$row->sku}}</td>
                        <td class="text-center">{{$row->quantity_shipped}}</td>
                        <td></td>
                        <td class="text-center" style="font-weight: bold;">{{$row->prep[0]->prep_type ?? ""}}</td>
                        <td style="font-weight: bold; color: darkred" class="text-center">
                            @if (!empty($notes))
                                @foreach($notes as $note)
                                    {{$note}}
                                @endforeach
                                @else
                            @endif
                        </td>
                        <td class="text-center" style="font-weight: bold;">
                            {{$row->product->fba_item_notes ?? ''}}
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
