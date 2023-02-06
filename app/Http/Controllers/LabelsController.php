<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Products\Product;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use Response;

/**
 * Class LabelsController
 * @package App\Http\Controllers
 */
class LabelsController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * UpdateJob the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function create(Request $request)
    {
        $data = $request->except(['shippinglabel', 'download']);
        if ($request->has('debug')) {
            $products = Product::get(['sku', 'location'])->take(20);
            $items = [];
            foreach ($products as $product) {
                $items[] = [
                    'sku' => $product->sku,
                    'warehouseLocation' => $product->location,
                    'quantity' => random_int(1, 10)
                ];
            }
        }

        if (!empty($data)) {
            $timestamp = Carbon::now()->format('Y-m-d-H-i-s-u');
            $fileCT = 1;

            if ($label = $request->get('shippinglabel')) {
                $label_path = sprintf("%s/%s/%s-label.pdf", $data['orderNumber'], $timestamp, $fileCT);
                Storage::disk("labels")->put($label_path, base64_decode($label));
                $this->createPackingSlip($data, $timestamp, $fileCT);
                $final_path = $this->_mergePdfs($data['orderNumber'], storage_path("pdfs/labels"), $data['orderNumber'], $timestamp);
            } else {
                $final_path = $this->createPackingSlip($data, $timestamp, $fileCT);
                $fileCT++;
            }

            Label::updateOrCreate(['order_number' => $data['orderNumber']], [
                'order_number' => $data['orderNumber'],
                'printed' => 0
            ]);

            if (!$request->has('download')) {
                $b64Doc = chunk_split(
                    base64_encode(
                        file_get_contents($final_path)));

                return response()->json(["pdf" => $b64Doc]);
            }

            return Response::make(file_get_contents(storage_path("pdfs/labels/{$data['orderNumber']}-final.pdf")), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "inline; filename={$data['orderNumber']}-{$timestamp}.pdf"
            ]);
        }
        return response()->json("No data provided.", 400);
    }

    public function view(Request $request)
    {
        $products = Product::get(['sku', 'location'])->take(2);
        $items = [];
        foreach ($products as $product) {
            $items[] = [
                'sku' => $product->sku,
                'warehouseLocation' => $product->location,
                'quantity' => random_int(1, 10)
            ];
        }

        $packagetypes = [
            'fedex_envelope_onerate',
            'fedex_pak_onerate'
        ];

        $data = [
            "orderNumber" => "BPQB-TESTUPS",
            "createDate" => "2020-06-23",
            "serviceCode" => "ups_ground",
            "packagetype" => $packagetypes[rand(0,1)],
            "shipTo__name" => "Brandell McFarlin",
            "shipTo__company" => null,
            "shipTo__street1" => "1107 WESTBURY",
            "shipTo__street2" => null,
            "shipTo__street3" => null,
            "shipTo__city" => "TEMPLE",
            "shipTo__state" => "TX",
            "shipTo__postalCode" => "76504-2215",
            "shipTo__country" => "US",
            "shipmentItems" => $items
        ];

        return view('warehouse.labels.packingslip.label', compact('data'));
    }

    private function createPackingSlip($data, $timestamp, $fileCt)
    {
        $name = "{$fileCt}-packingslip.pdf";
        $path = storage_path("pdfs/labels/{$data['orderNumber']}/{$timestamp}/{$name}");
        $labelSize = [0, 0, 288, 432];
        $pdf = PDF::loadView('warehouse.labels.packingslip.label', compact('data'))
            ->setOrientation('portrait')
            // Width/Height Units: mm
            ->setOption('dpi', 203)
            ->setOption('lowquality', false)
            ->setOption('page-height', "152.4")
            ->setOption('page-width', "101.6")
            ->setOption('margin-left', '1')
            ->setOption('margin-top', '2')
            ->setOption('margin-right', '1')
            ->setOption('margin-bottom', '1')
            ->save($path);

        return $pdf;
    }

    private function _mergePdfs($filename, $path, $ordernumber, $timestamp)
    {
        $pdf_merger = PDFMerger::init();
        $fpath = $path . DIRECTORY_SEPARATOR . $ordernumber . DIRECTORY_SEPARATOR . $timestamp . DIRECTORY_SEPARATOR;

        foreach (glob("{$fpath}*.pdf") as $file) {
            $pdf_merger->addPDF($file, 'all');
        }

        $pdf_merger->merge();
        $final_path = storage_path("pdfs/labels/{$filename}-final.pdf");
        $pdf_merger->save($final_path, "file");
        File::deleteDirectory(storage_path("pdfs/labels/{$ordernumber}"));
        return $final_path;
    }
}
