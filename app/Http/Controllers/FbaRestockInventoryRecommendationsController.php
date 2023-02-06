<?php

namespace App\Http\Controllers;

use App\FbaRestockInventoryRecommendations;
use Illuminate\Support\Facades\Request;

/**
 * Class FbaRestockInventoryRecommendationsController
 * @package App\Http\Controllers
 */
class FbaRestockInventoryRecommendationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $perPage = Request::input('perPage');
        $sku = Request::input('filter');

        $query = FbaRestockInventoryRecommendations::with(['product' => function ($product) {
            $product->with('turnAround', 'inventory');
        }])->select('*');

        if (!empty($sku) && $sku !== 'null') {
            $query->whereHas('product', function ($sq) use ($sku) {
                $sq->where('sku', 'LIKE', "$sku%");
            });
        }

        $query->orderBy('maximum_shipment_quantity', 'DESC');


        $recommendations = $query->paginate($perPage);

        return response()->json(compact('recommendations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
     * Display the specified resource.
     *
     * @param  \App\FbaRestockInventoryRecommendations  $fbaRestockInventoryRecommendations
     * @return \Illuminate\Http\Response
     */
    public function show(FbaRestockInventoryRecommendations $fbaRestockInventoryRecommendations)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FbaRestockInventoryRecommendations  $fbaRestockInventoryRecommendations
     * @return \Illuminate\Http\Response
     */
    public function edit(FbaRestockInventoryRecommendations $fbaRestockInventoryRecommendations)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FbaRestockInventoryRecommendations  $fbaRestockInventoryRecommendations
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FbaRestockInventoryRecommendations $fbaRestockInventoryRecommendations)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FbaRestockInventoryRecommendations  $fbaRestockInventoryRecommendations
     * @return \Illuminate\Http\Response
     */
    public function destroy(FbaRestockInventoryRecommendations $fbaRestockInventoryRecommendations)
    {
        //
    }
}
