<?php

namespace App\Http\Controllers;

use App\Events\Ping;
use App\Models\Label;
use App\Models\Order;
use App\Models\Ping as PingModel;
use App\Models\Products\Product;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Class LabelController
 * @package App\Http\Controllers
 */
class LabelController extends Controller
{
    protected $pdf;

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
     * @param Label $label
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function show(Request $request)
    {
        $packlist = $request->all();
        return view('warehouse.labels.packingslip.label', compact('packlist'));
    }

    /**
     * @param Request $request
     * @return string
     */
    public function base64PDF(Request $request)
    {
            return response()->json(['packing_slip'=> $b64Doc]);
    }

    public function downloadPDF(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $response = $this->createPDF($data);
        } catch (\Exception $e) {
            throw new \RuntimeException(sprintf("Error generating PDF! %s", $e->getMessage()));
        }
    }

    public function base64($path) {
        return chunk_split(base64_encode(file_get_contents($path)));
    }

    /**
     * UpdateJob the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Label $label)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function destroy(Label $label)
    {
        //
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function log($id, Request $request) {
        try {
            $param = $request->all();
            $param['order_number'] = $id;
            $param['printed'] = strtolower($param['printed']) === "ok";
            $param['printed_at'] = date("Y-m-d H:i:s", strtotime($param['printed_at']));
            $label = Label::create($param);
            return response()->json($label, 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::channel('slack')->critical($id." was not label not logged");
            return response()->json("An error occured. {$e->getMessage()}", 500);
        }

    }

    private function download($pdf, $filename) {
        return $pdf->download($filename);
    }
}
