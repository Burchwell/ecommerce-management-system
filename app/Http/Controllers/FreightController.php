<?php

namespace App\Http\Controllers;

use App\FreightLabels;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

/**
 * Class FreightController
 * @package App\Http\Controllers
 */
class FreightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $data = $request->all();

        $options = [
            'title' => 'Freight Label',
            'page-height' => 152.4,
            'page-width' => 101.6,
            'margin-top' => 0,
            'margin-bottom' => 0,
            'margin-left' => 0,
            'margin-right' => 0,
            'dpi' => 203,
        ];

        $pdf = PDF::loadView('warehouse.labels.freight.label', compact('data'));
        $pdf->setOrientation('landscape');

        $filename = str_replace('"', "", strtolower(str_replace(" ", "_", sprintf('%s__%s__%s__%s__%s.pdf', $data['po_number'], $data['vendor'], $data['carrier'], $data['pickup_date'], time()))));

        foreach ($options as $key => $option) {
            $pdf->setOption($key, $option);
        }

        $pdf->save(storage_path("freights/{$filename}"));

        return $pdf->download($filename);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FreightLabels  $freightLabels
     * @return \Illuminate\Http\Response
     */
    public function show(FreightLabels $freightLabels)
    {
        return view('warehouse.labels.freight.label');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FreightLabels  $freightLabels
     * @return \Illuminate\Http\Response
     */
    public function edit(FreightLabels $freightLabels)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FreightLabels  $freightLabels
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FreightLabels $freightLabels)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FreightLabels  $freightLabels
     * @return \Illuminate\Http\Response
     */
    public function destroy(FreightLabels $freightLabels)
    {
        //
    }
}
