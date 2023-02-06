@php
    $page=1;
    $index = 1;
    $pagebreak = 5;
    $carrier = explode("_", $data['serviceCode'])[0];
@endphp
<!DOCTYPE html>
<html lang="en-us">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{--    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.css') }}"/>--}}
    <title>{{$data['orderNumber']." | Packing Slip"}}</title>
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/js/all.min.js"></script>--}}
<!-- Fonts -->
    <style>
        @page {
            margin: 0.007in;
        }

        * {
            font-family: monospace;
            font-size: 8pt;
            color: black;
        }

        table {
            width: 100%;
        }

        table.border td:not(.noborder) {
            border: 2px solid #000;
        }

        th {
            color: black;
            font-weight: bold;
            padding: 5px;
        }

        td {
            vertical-align: center;
            border-collapse: collapse;
            font-weight: normal;
        }

        .quantity b {
            font-size: 10pt;
        }

        table.order-info td {
            padding: 2px 4px 2px 4px;
        }

        thead {
            display: table-header-group;
        }

        table.line-items th {
            padding: 2px;
            white-space: nowrap;

            border: solid 2px black;
        }

        table.line-items td {
        }

        #dmcodec div {
            max-width: 40px;
        }

        @media print {
            .pagebreak {
                page-break-after: auto;
            }

            /* page-break-after works, as well */
        }
    </style>
</head>
<body>
<div style="background: #fff;">
    <div class="pagebreak" style="min-height: 580px">
        <table>
            <colgroup>
                <col width="90%"/>
                <col width="10%"/>
            </colgroup>

            <tr>
                <td colspan="2" style="background: black;color: white;font-weight: bold;padding: 5px;font-size: 20pt;font-weight: bold; text-align: center;">
                    PACKING SLIP
                </td>
            </tr>
            <tr>
                <td>
                    <table>

                        <tr>
                            <td colspan="4" rowspan="2" style="padding-left: 5px; border: 2px solid;">
                                <b>{{ $data['shipTo__name'] }}</b><br>
                                {{--                    {{$data['shipTo__street1']}}, {{$data['shipTo__street2'] ?: ''}} <br/>--}}
                                {{--                    {{$data['shipTo__street3'] ?: ''}}<br/>--}}
                                {{$data['shipTo__city']}}, {{$data['shipTo__state']}}
                                - {{explode('-', $data['shipTo__postalCode'])[0]}}, {{$data['shipTo__country']}}
                            </td>
                            <td rowspan="2" id="dmcodec">
                                {!! DNS2D::getBarcodeHTML($data['orderNumber'], 'DATAMATRIX',3.3,3.3) !!}
                            </td>
                        </tr>
                        <tr></tr>
                        <tr>
                            <td colspan="2"
                                style="background: black; color: white; text-align: center; font-weight: bold; font-size: 10pt; font-weight: bold; padding: 5px 0;">
                                {{ $data['orderNumber'] }}</td>
                            <td colspan="2"
                                style="background: black; color: white; text-align: center; font-weight: bold; font-size: 10pt; font-weight: bold; padding: 5px 0;">
                                {{ \Carbon\Carbon::createFromDate($data['createDate'])->format("m/d/Y") }}</td>
                            <td style="background: black; color: white; text-align: center; font-weight: bold; font-size: 10pt; font-weight: bold; padding: 5px 0">
                                {{$page ?: ''}} / {{ceil(count($data['shipmentItems'])/$pagebreak)}}
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table>
                        <tr>
                                @if ($carrier === 'ups')
                                <td style="text-align: center; position: relative; padding: 5px; border: 2px solid">
                                    <b><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAACz0lEQVRoQ+2YOYsVQRCAv/VKVMQLPMBAEEFBEzEUE8F/sGBgZqQmHoGJIIKIBiZioIlgYmgmLCb7D1bBZMFEQUW88Mi8KJiGopy+e96bBzuwMG+7pqa+urp65pjxa27G7WcFwBPBa8BFs3YDkL+mV00EvgEbG1mzDOwv0ZUL8LfkJQXPJNuVIjgpo/s4fwIbQg6IAUzTeG23186hAd4AewpSyD3iHDhRgJhTcngmBrAa+JNjWaLs4AAtvd3HNBjAZuBrohdrxAYBGNrrGrgpwFXgeo07C55tBrAKmMae0AxgHXBQeXCpuz8CbOvudwAPlYwF3g289UThA7C9WzsL3OvumwHIZvRavdzVwQ9gvfl/LFK6hgT6nQdK5EYJIPY6iBDsKAC+AFt66uco8ATYpby/FfgMfO8GuFEAfFI1kpNazWeh0hrIATgNPDL1MPUa0AAvgQMJBa+LfFQAj4F5A/AK2NvThWyRV4/TLVLoKXCypxUfAp57IEYVAVvE1qt2/S5wrgMbRQRiAGKrr1NVA+ierDei2E4c6kIPAPHyi8D44ZaaA/gGSwsakrsPnAlMqDJmvG+VQjmGxTYs+SC2CYgBNN2JU2aXReB4ZOyWwc2ND/JVT2D6ruZt1ObgTuAwIGO1C7Pd/uW31MApYCGQKrIkE+2+Tp8WrW6jl4FbsTz0bP8OwJ0XIgz/LUeN190kpDxJkaeT6C40dYBc4JoIOKdJmz0fIk/9wuAUflRHP59e3YVKIqCfj9oXFehJjd/AmoBXagCyjE9Nib6uEHpWn3HlRKZH6NDGpj9NJjs2WdBTpCVOsBApM5I34CUAokzOuZLf+srVZQ1fC/zKbVW5L7X6Zfc9lgliDb8DXMg13MnXAjg9ciCRg0koItbwm8CVUsNbAzh9t4FLEaNOAM9qDR8KwOmV2UbOCjU1ksTYKoWSXjaE0ArAEF7N0TnzEfgH9ay9MYHrlMkAAAAASUVORK5CYII="
                                            width="57px" alt="UPS"/> </b>
                            @elseif ($carrier === 'fedex')
                                <td style="text-align: center; position: relative; padding: 17px 5px; border: 2px solid">
                                    <b><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAPCAYAAACiLkz/AAABJUlEQVRIS+1WywmDQBBdIWAPW4ZnyxA8CUkFqcCTTXhT8CRYhmfLMDVIDgmKyrru/NRLIB7dmXnz3rxZ9ZRSH4U/HnFOHVP17XwR3hhMAYgKOthQ9f8EbIXOKm4rCk4gCIJH13Ul5UHs3GWhnycAedKlpE1W6neO+CgGd4mxxhaAq5rHXLGzO4eAqZKphlkMAj1CiivIFMfZgTNNjBic6UFWgnJXIVECWuui7/s7x6hGjGQvOBcGekuiBNq2LcIwNAlAgCuI7/tqGAbIatKP1m4CVVXpJEleSyGphUgCc2EuAddwp9ymad5RFN2A6fMsNCe7Rsh5J3TeGn75EnMX8ciyQxOAfO+8RrlKbZLjOFZ1XbssderXJE3TTX6WZRuMPM+fZsNfawxXRn8EOcUAAAAASUVORK5CYII="
                                            width="57px" alt="fedex"/></b>
                            @elseif ($carrier === 'usps')
                                <td style="text-align: center; position: relative; padding: 10px; border: 2px solid">
                                    <b><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAdCAYAAADsMO9vAAACQ0lEQVRYR9WWv0sCYRjHn7u5HFwaFALB5SSkwKFB1DnolgIXwVCkxdH6F0JxcsocXJxyOWhXcbCpMFAHQRp0cRAMmpS70FC8833P9973PSMXUZ4f38/z454TgOCTSqUuisXiC4Hp3k0Ehowagy83VxYAnYhSqXSfTCYfuCkjCDSfz6+5AWDy2d0lwW4AHddgMHjyeDxJguKSmuwXwIYuoQGq1eppPp+HVqsFlUrlrdfrwXQ6hdFoBOPxGGazmU6Lw+EAl8sFq29JkiAWi51Fo1FIJBIQCATeCUtqdeSwHbAaiFCfqdlynBuNxkcoFDohCLi039qBeDyulctlAn/+Jl6vF/r9Pm4vjUVFAwAAbfV5PBBwuRex/wUAqq0L4XsBQI4k66B1u907SZKymx2YTCa3TqfzEZeQdoSoATRNuxQEQTHC1uv173A4fIAbH1RCFvFWAVC5LC2wHQC7IJCLuGPMTEGNxKwd2FmUdrt95Pf7xxSily6iKIKqqmvddgJYqnY6nb4qFArPBEuv07z+IctyVlGUDEEAYpNarfYaiUTOaauN8UMDMBwwVB7To5bJZLRcLkdcCIOhrQC7rjH3HdtMyBIcK1wURU1VVdpq6/w6nc6Nz+fTvaixAphVnKUgOOCtfKs/rCYjPThcKr8RhBngr4SvGKgBUMKtdo2pG8YDZiQyew9fJ1YU5VCW5S8mJfTOyO4Lw+Hwyu12Gy+gHReaXvqvJxrAcMDWRs1m8zMYDB6zZuXobwrA+jjlqBMbCgnwAx0zomToAXx1AAAAAElFTkSuQmCC"
                                            width="57px" alt="USPS"/></b>
                                    @endif
                                    <br/>
                                    {{strtoupper(str_replace($carrier, '', str_replace("_", " ", $data['packagetype'])))}}
                                </td>
                        </tr>
                        <tr>
                            <td style="text-align: center; font-weight: bold;text-transform: uppercase;"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table style="margin-top: 5px;">
                        <colgroup>
                            <col width="70%"/>
                            <col width="10%"/>
                            <col width="10%"/>
                            <col width="10%"/>
                        </colgroup>
                        <thead>
                        <tr>
                            <td style="background: black; color: white; font-weight: bold; padding-left: 10px; font-size: 10pt; font-weight: bold;">
                                SKU
                            </td>
                            <td style="background: black; color: white; font-weight: bold; text-align: center; padding-top: 8px;"
                                rowspan="2">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAABVklEQVRoQ+1YyxLCMAiEi//hSf3/j9Ge/A8vcerUmT6iuxLaZCq9hkCWXSiJppSSrPCpqo7drhZnNccBgJOF7o6BuXaZPKSUbiJyGtsSNdCp6pnx/62WFgwEgCFdwcAHbc1r9j8ktEC97PEuRYzi9KSYGECOvboQihMA3nWV6TBtSwj9bLwkhOKYJYQcBwCUodG6qQsh/00xICIdOnBm/Sgihx+HuYeI3A2xpkPj7sZpQ0ayW4hhziXU/i40LmkRkc0Y8DpwLT+Tp49ahyiJGwBKsuext+kulHtggKOER1Z6Hx5dKACUsFGTga2GOSY/l4zRdTI0MhdpFMk4TiO31HrNdyHqgMgoAAwXbcurBEoutU4xgOrEWAOL53XL5SoAFEgoGHiNLEjfTCV51QATa24TADxrIBjIzfFMVpqqAebAjA0xTsM22vqFJgBk/wOMPBibrST0BDAMirONMJf4AAAAAElFTkSuQmCC" alt="QCD" width="18px"  />
                            </td>
                            <td style="background: black; color: white; font-weight: bold; text-align: center; padding-top: 8px;"
                                rowspan="2">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAFJElEQVRoQ+1aP4/jRBT3G9tJLJ1uQTqkg2sQEhSIT7DFSpF3JW8KoMimRDSUVNCi0yE6BHwBGkSXdQNFNtKuFS3FSvsBDoRAoqFAiD97dzqxtsdjNFaeNRnP2GPHwBakyWaTmTfv3+/93huDZVnWYrEYTiaTOM9zGwAy/j/5NZ/P7dlsluFvV6vVaDweX//XawEPgO+qw+N3p6enOwcHB4/kd3ktYyzHfRhjFiHEopRag8EAmtaK8pvkrlYrB0QPaCw/mM1miULw7clk8hiFiIdWHd5xnEIJfOfK1BltPp8byYW6sBG8UxxWVoJSmnPrql6i5eXD88/4PSGkMKLK8ovFQikXleNrKotxI537kiTJxQN0OXySJO+NRqNP+whXpQKy+/DQGAJovTaHj+P4c8/z3jEJG5QXRdEzPOfQE6q1WvclSfLIcZzbYty2tXwcx194nvc2hp7oell5DEdZ3vpzGobhLZ6L8roNBSilPxNC7mk2KeO2zvJpmn49HA7fMEEbTHzTfFnL/Y0Q8hyeoVSgwQK1h8+y7AfXdV9pQiouVEarlocvbceTn9emDRhNkiRxHMc1DJvHhJCdJqTiYTOdTp9YljWQPdfl8ADwIQDcR7mFFrzCytZLkvTaceyhlLC/EEKeb0Iqvpfv+78yxgbbwmwBlQBfAsBbslzOBgoPmLheQS0qhcb3/T8ppdA22TUe/44Q8mqT3LIaykrUIYYYNkEQfE8pvSsWqy4wu1biSRiGd1RoIzIGhNWLiwsPeFKhBQTcLchdU6Hxff/KMF+KrTQx/0cURS+t8b61XJDRp67E1/GTIAiuGGNgGPNPoyi611Sk0IBrIIhVxtrwgM71HLJM0AZdr4PK5XK5o+JUunBljFHGmI1sVsWpSg/Uxe1yuRzxkDKl0ypiZrqWMZZaluXUhFzJavM8twoPNCWd4zgqHr9Bp1X5YkqJxUPjPqY1olBAJVy0gM71Ap3+nRBypw0lppT+RAh5sQ0hVJFJrQImnVQQBNcVcgWwPDk5eVMVcnEcf+W67utNHq+LeXmtUgHZfTpaW9eF4RqM/T76CJXiFQV0sRfH8Qee530kIkYdMZP7X14z+rR8yUZ1DbiuDeQJLSSasnnna2UP9NF+qvKl9IBp1q/J1S0AeFrH59dNe4lUqnwxgco6r1VgdJtGRl6LHsCQazu1MCWEkGVZDgAbI48+iJk8NumrkRG9xr2zQSV6IGalIbB6m+TLNnIrZG6byYO4Vkx2sZVsk2tNHZzWA33AnTywEilLn7mmpdPblnhZgW2GBjqvVTzQh+VRcVEBTsU5jPZpedzLiE53iVtUAPsIebrXR64Z0+k25Er0gEine2o/S5RDoxrR6S6uRxjFBryP+WpnOl03Htclu9wEiR7oM9eM6HSXQnPj6HTbpLuxdNpwbHKz6XRTiccLPfFi4n86LVmthFE+nZ5Op7QLVNYVuH+DTp+fn3uV6XRfcPdP0ekwDIfitW/tdHo6nSoHvCbUok86DQDPAsCVPN4sptOa+4GNKXGe5/w+mbXpYbel0wDwMgD8iKGvu/YF4ZkHo1Hh0dFRlqYpbarOXei0bduvAcBDGeV0I8rKDY3ppQZOmPf3919I0/RbVQNuSqdd1z04Pj7+xvRSQx4S9/awx+Hh4bt5nn8s9wMqOu267oOzs7PP2lxqqB4yKR/24H+Mx2OqmTAXl4AYajxxdnd3/8LPqrVZlt23bfsBXiDy3+7t7b1/eXn5SdNa4VLDSO7fMtfy7jfBfF4AAAAASUVORK5CYII=" alt="QTY" width="18px" />
                            <td style="background: black; color: white; font-weight: bold; text-align: center; padding-top: 8px;"
                                rowspan="2">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAABuklEQVRoQ+1aS1bDMAyUFmyAA/DYAvc/EN33BN10I555SZ9xY1say4lD0238mdGMpDgu085/vHP89DgEROSbiF5XUuzCzJ+avYoKiIhoFuk9hpmzOLMPRgE/BydHYpHAaOBLJO4IjAo+R+Ig0DuB0/XTXPijwFQqVeVrbeDRfidm/rpZKgYiImcietsQnGbrMzO/HwQ0odKOiX2tqIJjKbDUoCokxiFQekUokBiDQAl8sJ6IPBPRZcGG2xOogZ9BZ1TYlkAj+MDNhUA4G9yaCVJtSnO6JnFS8q5E9KQh4BD5eRtcAaDk/W7qCB63EFjyvMFjBDQRFJE7O2nmTSXTcnS1WwgBgszR5BJchSyALGOVoONhdgVqB2sAROi0Ftv4ELBUlIY6X4sHroCHEg2Rb+8DcWi0Pk9OfKht/CyEknCIvK8CFjs5gscaWS2r0C5dWzfzvD2JlxZG35MAEn0IpCXW2TZ9khiInseUfgp4oFOsUSRwIqIPxSJbDsl/WgRebVcnUvy4exBYQY+qAiOrsNRr/ucl3+yEjs3IZDbomjXdYbq9eTHtjA8OF92qD2eP81cDPJh9Z+5egR/5izdAEyZ5oAAAAABJRU5ErkJggg=="
                                     alt="CHK" width="18px"/>
                            </td>
                        </tr>
                        <tr>
                            <td style="background: black; color: white; font-weight: bold; padding-left: 10px;">
                                Location
                            </td>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($data['shipmentItems'] as $item)
                            @if ($index > 0 && $index % $pagebreak === 0)
                                @php($page++)
                                <tr>
                                    <td colspan="4">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td style="border-bottom: 1px solid">Packer:</td>
                                    <td colspan="2" style="border-bottom: 1px solid"></td>
                                    </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>

        @if (!empty($data['packagetype']))
            <table style="margin-top: 3rem;">
                <thead>
                <tr>
                    <th style="background: black;color: white;font-weight: bold;padding: 5px;font-size: 20pt;font-weight: bold; text-align: center;">
                        {{ strtoupper(str_replace("_", " ", $data['packagetype'])) }}
                    </th>
                </tr>
                </thead>
            </table>
        @endif
        </div>

        <div style="page-break-before: auto; padding-bottom: 35px;">
            <table>
                <tr>
                    <td>
                        <table>
                            <colgroup>
                                <col width="90%"/>
                                <col width="10%"/>
                            </colgroup>
                            <tr>
                                <td colspan="2"
                                    style="text-align: center; font-weight: bold; font-size: 10pt; padding: 5px 0; border-bottom: 2px solid">
                                    Packingslip
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table>

                                        <tr>
                                            <td colspan="4" rowspan="2" style="padding-left: 5px; border: 2px solid;">
                                                <b>{{ $data['shipTo__name'] }}</b><br>
                                                {{$data['shipTo__street1']}}, {{$data['shipTo__street2'] ?: ''}} <br/>
                                                {{$data['shipTo__street3'] ?: ''}}<br/>
                                                {{$data['shipTo__city']}}, {{$data['shipTo__state']}}
                                                - {{explode('-', $data['shipTo__postalCode'])[0]}}
                                                , {{$data['shipTo__country']}}
                                            </td>
                                            <td rowspan="2" id="dmcodec">
                                                {!! DNS2D::getBarcodeHTML($data['orderNumber'], 'DATAMATRIX',3.3,3.3)  !!}
                                            </td>
                                        </tr>
                                        <tr></tr>
                                        <tr>
                                            <td colspan="2"
                                                style="background: black; color: white; text-align: center; font-weight: bold; font-size: 10pt; font-weight: bold; padding: 5px 0;">
                                                {{ $data['orderNumber'] }}</td>
                                            <td colspan="2"
                                                style="background: black; color: white; text-align: center; font-weight: bold; font-size: 10pt; font-weight: bold; padding: 5px 0;">
                                                {{ \Carbon\Carbon::createFromDate($data['createDate'])->format("m/d/Y") }}</td>
                                            <td style="background: black; color: white; text-align: center; font-weight: bold; font-size: 10pt; font-weight: bold; padding: 5px 0">
                                                {{$page}} / {{ceil(count($data['shipmentItems'])/$pagebreak)}}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table>
                                        <tr>
                                                @if ($carrier === 'ups')
                                                    <td style="text-align: center; position: relative; padding: 5px; border: 2px solid">
                                                        <b><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAACz0lEQVRoQ+2YOYsVQRCAv/VKVMQLPMBAEEFBEzEUE8F/sGBgZqQmHoGJIIKIBiZioIlgYmgmLCb7D1bBZMFEQUW88Mi8KJiGopy+e96bBzuwMG+7pqa+urp65pjxa27G7WcFwBPBa8BFs3YDkL+mV00EvgEbG1mzDOwv0ZUL8LfkJQXPJNuVIjgpo/s4fwIbQg6IAUzTeG23186hAd4AewpSyD3iHDhRgJhTcngmBrAa+JNjWaLs4AAtvd3HNBjAZuBrohdrxAYBGNrrGrgpwFXgeo07C55tBrAKmMae0AxgHXBQeXCpuz8CbOvudwAPlYwF3g289UThA7C9WzsL3OvumwHIZvRavdzVwQ9gvfl/LFK6hgT6nQdK5EYJIPY6iBDsKAC+AFt66uco8ATYpby/FfgMfO8GuFEAfFI1kpNazWeh0hrIATgNPDL1MPUa0AAvgQMJBa+LfFQAj4F5A/AK2NvThWyRV4/TLVLoKXCypxUfAp57IEYVAVvE1qt2/S5wrgMbRQRiAGKrr1NVA+ierDei2E4c6kIPAPHyi8D44ZaaA/gGSwsakrsPnAlMqDJmvG+VQjmGxTYs+SC2CYgBNN2JU2aXReB4ZOyWwc2ND/JVT2D6ruZt1ObgTuAwIGO1C7Pd/uW31MApYCGQKrIkE+2+Tp8WrW6jl4FbsTz0bP8OwJ0XIgz/LUeN190kpDxJkaeT6C40dYBc4JoIOKdJmz0fIk/9wuAUflRHP59e3YVKIqCfj9oXFehJjd/AmoBXagCyjE9Nib6uEHpWn3HlRKZH6NDGpj9NJjs2WdBTpCVOsBApM5I34CUAokzOuZLf+srVZQ1fC/zKbVW5L7X6Zfc9lgliDb8DXMg13MnXAjg9ciCRg0koItbwm8CVUsNbAzh9t4FLEaNOAM9qDR8KwOmV2UbOCjU1ksTYKoWSXjaE0ArAEF7N0TnzEfgH9ay9MYHrlMkAAAAASUVORK5CYII="
                                                                width="57px" alt="UPS"/> </b>
                                                @elseif ($carrier === 'fedex')
                                                    <td style="text-align: center; position: relative; padding: 5px; border: 2px solid">
                                                        <b><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAPCAYAAACiLkz/AAABJUlEQVRIS+1WywmDQBBdIWAPW4ZnyxA8CUkFqcCTTXhT8CRYhmfLMDVIDgmKyrru/NRLIB7dmXnz3rxZ9ZRSH4U/HnFOHVP17XwR3hhMAYgKOthQ9f8EbIXOKm4rCk4gCIJH13Ul5UHs3GWhnycAedKlpE1W6neO+CgGd4mxxhaAq5rHXLGzO4eAqZKphlkMAj1CiivIFMfZgTNNjBic6UFWgnJXIVECWuui7/s7x6hGjGQvOBcGekuiBNq2LcIwNAlAgCuI7/tqGAbIatKP1m4CVVXpJEleSyGphUgCc2EuAddwp9ymad5RFN2A6fMsNCe7Rsh5J3TeGn75EnMX8ciyQxOAfO+8RrlKbZLjOFZ1XbssderXJE3TTX6WZRuMPM+fZsNfawxXRn8EOcUAAAAASUVORK5CYII="
                                                                width="57px" alt="fedex"/></b>
                                                @elseif ($carrier === 'usps')
                                                    <td style="text-align: center; position: relative; padding: 10px; border: 2px solid">
                                                        <b><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAdCAYAAADsMO9vAAACQ0lEQVRYR9WWv0sCYRjHn7u5HFwaFALB5SSkwKFB1DnolgIXwVCkxdH6F0JxcsocXJxyOWhXcbCpMFAHQRp0cRAMmpS70FC8833P9973PSMXUZ4f38/z454TgOCTSqUuisXiC4Hp3k0Ehowagy83VxYAnYhSqXSfTCYfuCkjCDSfz6+5AWDy2d0lwW4AHddgMHjyeDxJguKSmuwXwIYuoQGq1eppPp+HVqsFlUrlrdfrwXQ6hdFoBOPxGGazmU6Lw+EAl8sFq29JkiAWi51Fo1FIJBIQCATeCUtqdeSwHbAaiFCfqdlynBuNxkcoFDohCLi039qBeDyulctlAn/+Jl6vF/r9Pm4vjUVFAwAAbfV5PBBwuRex/wUAqq0L4XsBQI4k66B1u907SZKymx2YTCa3TqfzEZeQdoSoATRNuxQEQTHC1uv173A4fIAbH1RCFvFWAVC5LC2wHQC7IJCLuGPMTEGNxKwd2FmUdrt95Pf7xxSily6iKIKqqmvddgJYqnY6nb4qFArPBEuv07z+IctyVlGUDEEAYpNarfYaiUTOaauN8UMDMBwwVB7To5bJZLRcLkdcCIOhrQC7rjH3HdtMyBIcK1wURU1VVdpq6/w6nc6Nz+fTvaixAphVnKUgOOCtfKs/rCYjPThcKr8RhBngr4SvGKgBUMKtdo2pG8YDZiQyew9fJ1YU5VCW5S8mJfTOyO4Lw+Hwyu12Gy+gHReaXvqvJxrAcMDWRs1m8zMYDB6zZuXobwrA+jjlqBMbCgnwAx0zomToAXx1AAAAAElFTkSuQmCC"
                                                                width="57px" alt="USPS"/></b>
                                                @endif
                                                <br/>
                                                {{ucwords(str_replace($carrier, '', str_replace("_", " ", $data['serviceCode'])))}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center; font-weight: bold;text-transform: uppercase;"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table>
                                        <colgroup>
                                            <col width="70%"/>
                                            <col width="10%"/>
                                            <col width="10%"/>
                                            <col width="10%"/>
                                        </colgroup>
                                        <thead>
                                        <tr>
                                            <td style="background: black; color: white; font-weight: bold; padding-left: 15px;">
                                                SKU
                                            </td>
                                            <td style="background: black; color: white; font-weight: bold; text-align: center; padding-top: 8px;"
                                                rowspan="2">
                                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAABVklEQVRoQ+1YyxLCMAiEi//hSf3/j9Ge/A8vcerUmT6iuxLaZCq9hkCWXSiJppSSrPCpqo7drhZnNccBgJOF7o6BuXaZPKSUbiJyGtsSNdCp6pnx/62WFgwEgCFdwcAHbc1r9j8ktEC97PEuRYzi9KSYGECOvboQihMA3nWV6TBtSwj9bLwkhOKYJYQcBwCUodG6qQsh/00xICIdOnBm/Sgihx+HuYeI3A2xpkPj7sZpQ0ayW4hhziXU/i40LmkRkc0Y8DpwLT+Tp49ahyiJGwBKsuext+kulHtggKOER1Z6Hx5dKACUsFGTga2GOSY/l4zRdTI0MhdpFMk4TiO31HrNdyHqgMgoAAwXbcurBEoutU4xgOrEWAOL53XL5SoAFEgoGHiNLEjfTCV51QATa24TADxrIBjIzfFMVpqqAebAjA0xTsM22vqFJgBk/wOMPBibrST0BDAMirONMJf4AAAAAElFTkSuQmCC" alt="QCD" width="18px"  />
                                            </td>
                                            <td style="background: black; color: white; font-weight: bold; text-align: center; padding-top: 8px;"
                                                rowspan="2">
                                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAFJElEQVRoQ+1aP4/jRBT3G9tJLJ1uQTqkg2sQEhSIT7DFSpF3JW8KoMimRDSUVNCi0yE6BHwBGkSXdQNFNtKuFS3FSvsBDoRAoqFAiD97dzqxtsdjNFaeNRnP2GPHwBakyWaTmTfv3+/93huDZVnWYrEYTiaTOM9zGwAy/j/5NZ/P7dlsluFvV6vVaDweX//XawEPgO+qw+N3p6enOwcHB4/kd3ktYyzHfRhjFiHEopRag8EAmtaK8pvkrlYrB0QPaCw/mM1miULw7clk8hiFiIdWHd5xnEIJfOfK1BltPp8byYW6sBG8UxxWVoJSmnPrql6i5eXD88/4PSGkMKLK8ovFQikXleNrKotxI537kiTJxQN0OXySJO+NRqNP+whXpQKy+/DQGAJovTaHj+P4c8/z3jEJG5QXRdEzPOfQE6q1WvclSfLIcZzbYty2tXwcx194nvc2hp7oell5DEdZ3vpzGobhLZ6L8roNBSilPxNC7mk2KeO2zvJpmn49HA7fMEEbTHzTfFnL/Y0Q8hyeoVSgwQK1h8+y7AfXdV9pQiouVEarlocvbceTn9emDRhNkiRxHMc1DJvHhJCdJqTiYTOdTp9YljWQPdfl8ADwIQDcR7mFFrzCytZLkvTaceyhlLC/EEKeb0Iqvpfv+78yxgbbwmwBlQBfAsBbslzOBgoPmLheQS0qhcb3/T8ppdA22TUe/44Q8mqT3LIaykrUIYYYNkEQfE8pvSsWqy4wu1biSRiGd1RoIzIGhNWLiwsPeFKhBQTcLchdU6Hxff/KMF+KrTQx/0cURS+t8b61XJDRp67E1/GTIAiuGGNgGPNPoyi611Sk0IBrIIhVxtrwgM71HLJM0AZdr4PK5XK5o+JUunBljFHGmI1sVsWpSg/Uxe1yuRzxkDKl0ypiZrqWMZZaluXUhFzJavM8twoPNCWd4zgqHr9Bp1X5YkqJxUPjPqY1olBAJVy0gM71Ap3+nRBypw0lppT+RAh5sQ0hVJFJrQImnVQQBNcVcgWwPDk5eVMVcnEcf+W67utNHq+LeXmtUgHZfTpaW9eF4RqM/T76CJXiFQV0sRfH8Qee530kIkYdMZP7X14z+rR8yUZ1DbiuDeQJLSSasnnna2UP9NF+qvKl9IBp1q/J1S0AeFrH59dNe4lUqnwxgco6r1VgdJtGRl6LHsCQazu1MCWEkGVZDgAbI48+iJk8NumrkRG9xr2zQSV6IGalIbB6m+TLNnIrZG6byYO4Vkx2sZVsk2tNHZzWA33AnTywEilLn7mmpdPblnhZgW2GBjqvVTzQh+VRcVEBTsU5jPZpedzLiE53iVtUAPsIebrXR64Z0+k25Er0gEine2o/S5RDoxrR6S6uRxjFBryP+WpnOl03Htclu9wEiR7oM9eM6HSXQnPj6HTbpLuxdNpwbHKz6XRTiccLPfFi4n86LVmthFE+nZ5Op7QLVNYVuH+DTp+fn3uV6XRfcPdP0ekwDIfitW/tdHo6nSoHvCbUok86DQDPAsCVPN4sptOa+4GNKXGe5/w+mbXpYbel0wDwMgD8iKGvu/YF4ZkHo1Hh0dFRlqYpbarOXei0bduvAcBDGeV0I8rKDY3ppQZOmPf3919I0/RbVQNuSqdd1z04Pj7+xvRSQx4S9/awx+Hh4bt5nn8s9wMqOu267oOzs7PP2lxqqB4yKR/24H+Mx2OqmTAXl4AYajxxdnd3/8LPqrVZlt23bfsBXiDy3+7t7b1/eXn5SdNa4VLDSO7fMtfy7jfBfF4AAAAASUVORK5CYII=" alt="QTY" width="18px" />
                                            <td style="background: black; color: white; font-weight: bold; text-align: center; padding-top: 8px;"
                                                rowspan="2">
                                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAABuklEQVRoQ+1aS1bDMAyUFmyAA/DYAvc/EN33BN10I555SZ9xY1say4lD0238mdGMpDgu085/vHP89DgEROSbiF5XUuzCzJ+avYoKiIhoFuk9hpmzOLMPRgE/BydHYpHAaOBLJO4IjAo+R+Ig0DuB0/XTXPijwFQqVeVrbeDRfidm/rpZKgYiImcietsQnGbrMzO/HwQ0odKOiX2tqIJjKbDUoCokxiFQekUokBiDQAl8sJ6IPBPRZcGG2xOogZ9BZ1TYlkAj+MDNhUA4G9yaCVJtSnO6JnFS8q5E9KQh4BD5eRtcAaDk/W7qCB63EFjyvMFjBDQRFJE7O2nmTSXTcnS1WwgBgszR5BJchSyALGOVoONhdgVqB2sAROi0Ftv4ELBUlIY6X4sHroCHEg2Rb+8DcWi0Pk9OfKht/CyEknCIvK8CFjs5gscaWS2r0C5dWzfzvD2JlxZG35MAEn0IpCXW2TZ9khiInseUfgp4oFOsUSRwIqIPxSJbDsl/WgRebVcnUvy4exBYQY+qAiOrsNRr/ucl3+yEjs3IZDbomjXdYbq9eTHtjA8OF92qD2eP81cDPJh9Z+5egR/5izdAEyZ5oAAAAABJRU5ErkJggg=="
                                                     alt="CHK" width="18px"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background: black; color: white; font-weight: bold; padding-left: 10px">
                                                Location
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody>

                                    @endif
                                    <tr>
                                        <td style="
    border: 1px solid;
    font-size: 10pt;
    border-bottom: 1px dashed;
    padding: 6px;
">{{$item['sku']}} </td>
                                        <td rowspan="2" style="
    text-align: center;
    font-size: 12pt;
    padding: 3px;
    border: 1px solid;
"><div style="display: inline;">{!! DNS2D::getBarcodeHTML($item['sku'], 'DATAMATRIX',2.3, 2.3) !!}</div></td>
                                        <td rowspan="2" style="
    border: 1px solid;
    text-align: center;
    font-size: 16pt;
    font-weight: bold;
">{{$item['quantity']}}</td>
                                        <td rowspan="2" style="background: black; text-align: center; padding: 3pt;">
                                            <div
                                                style="width: 22px; height: 22px; background: white; margin: 0 auto; border-radius: 3px;">
                                                &nbsp;
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="
                                        border: 1px solid;
                                        border-top: none;
                                        padding: 3px;
                                    ">{{!is_null($item['warehouseLocation']) ? $item['warehouseLocation'] : 'NO LOCATION SET'}}</td>
                                    </tr>
                                    @php($index++)
                                    @endforeach
                                    <tr>
                                        <td colspan="4">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td style="border-bottom: 1px solid">Packer:</td>
                                        <td colspan="2" style="border-bottom: 1px solid"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                    </table>
                </td>
            </tr>
        </table>
            @if (!empty($data['packagetype']))
            <table style="margin-top: 3rem;">
              <thead>
                <tr>
                    <th style="background: black;color: white;font-weight: bold;padding: 5px;font-size: 20pt;font-weight: bold; text-align: center;">
                        {{ strtoupper(str_replace("_", " ", $data['packagetype'])) }}
                    </th>
                </tr>
              </thead>
            </table>
            @endif
        </div>
</div>
</body>
</html>
