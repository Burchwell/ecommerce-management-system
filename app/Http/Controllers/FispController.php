<?php

namespace App\Http\Controllers;

use App\Models\Amazon\FbaInboundShipment;
use App\Models\Amazon\FbaInboundShipmentProduct;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class FispController
 * @package App\Http\Controllers
 */
class FispController extends Controller
{
    public function show($id) {
        $data = FbaInboundShipment::with(['items' => function ($q) {
            $q->with(['prep', 'product' => function ($product) {
                $product->select(['id', 'fba_item_notes', 'weight']);
            }]);
        }, 'warehouse'])->where('shipment_id', '=', $id)->first();
        return view('pdf.fba_inbound_shipments', compact('data'));
    }

    public function pdf($id) {
        $data = FbaInboundShipment::with(['items' => function ($q) {
            $q->with(['prep', 'product' => function ($product) {
                $product->select(['id', 'fba_item_notes', 'weight']);
            }]);
        }, 'warehouse'])->where('shipment_id', '=', $id)->first();

        $snf = explode(' - ', $data['shipment_name']);
        $snf[0] = str_replace('/', '-', $snf[0]);
        $snf[1] = explode('(', $snf[1]);
        $snf[1][1] = str_replace(')', '', $snf[1][1]);
        $name = "FBA Picklist - {$snf[1][0]} from {$snf[1][1]} - {$snf[0]}";

        $pdf = PDF::loadView('pdf.fba_inbound_shipments', compact('data', 'name'))
            ->setOrientation('portrait');


        return $pdf->inline("{$name}.pdf");
    }
}
