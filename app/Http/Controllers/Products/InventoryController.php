<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Products\Inventory;
use App\Models\Products\Product;
use Illuminate\Support\Facades\Request;

/**
 * Class InventoryController
 * @package App\Http\Controllers\Products
 */
class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $perPage = Request::input('perPage') ?: 10;
        $inventory = Product::with(['inventory'])->without(['images', 'pallet', 'variants', 'notes'])->where([
            ['sku', 'NOT LIKE', '%3CH-%'],
            ['sku', 'NOT LIKE', '%DLR-%'],
            ['sku', 'NOT LIKE', '%RFBISH-%'],
            ['sku', 'NOT LIKE', '%USED-%'],
            ['sku', 'NOT LIKE', '%BNDL-%']
        ])->get(['id', 'sku']);

        return response()->json(compact('inventory'));
    }

    public function search($search, Request $request) {
        $inventory = Product::with('inventory')
            ->without(['pallet', 'notes', 'variants', 'images'])
            ->where(
            [
                ['sku', 'NOT LIKE', '3CH-%'],
                ['sku', 'NOT LIKE', 'DLR-%'],
                ['sku', 'NOT LIKE', 'BNDL-%'],
                ['sku', 'NOT LIKE', 'RFBISH-%'],
                ['sku', 'NOT LIKE', 'USED-%'],
                ['sku', 'LIKE', "%{$search}%"]
            ])
            ->orWhere(
                [
                    ['sku', 'NOT LIKE', '3CH-%'],
                    ['sku', 'NOT LIKE', 'BNDL-%'],
                    ['sku', 'NOT LIKE', 'DLR-%'],
                    ['sku', 'NOT LIKE', 'RFBISH-%'],
                    ['sku', 'NOT LIKE', 'USED-%'],
                    ['id', '=', $search]
                ])
            ->select('id', 'sku')->get(['id', 'sku']);

            return response()->json(compact('inventory'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
