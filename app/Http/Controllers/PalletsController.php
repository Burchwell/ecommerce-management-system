<?php

namespace App\Http\Controllers;

use App\Models\Products\Product;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Http\Request;
use Response;

class PalletsController extends Controller
{
    public function index()
    {
        return view('warehouse.labels.palettes.form');
    }

    public function create(Request $request)
    {
        $sku = $request->get('sku');
        $name = "palette-label-" . strtolower($sku) . ".pdf";
        $path = "pdfs/labels/palettes/{$name}";

        if (file_exists(storage_path($path))) {
            unlink(storage_path($path));
        }

        $product = Product::where('sku', '=', $sku)->first() ?: $sku;
        $path = $this->createPDF($product, $name);
        $file = file_get_contents($path);
        $pdf = chunk_split(
            base64_encode($file));

        return response()->json(compact('product', 'pdf'));
    }

    public function show($sku)
    {
        $product = Product::where('sku', '=', $sku)->first();
        if ($product) {
            return view('warehouse.labels.palettes.label', compact('product'));
        }
    }

    public function createPDF($product, $name)
    {
        $path = storage_path("pdfs/labels/palettes/{$name}");

        PDF::loadView('warehouse.labels.palettes.label', compact('product'))
            ->setOrientation('landscape')
            ->setOption('title', optional($product)->sku ?: $name)
            // Width/Height Units: mm
            ->setOption('page-height', 152.4)
            ->setOption('page-width', 101.6)
            ->setOption('margin-top', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-left', 0)
            ->setOption('dpi', 203)
            ->save($path);

        return $path;
    }
}

