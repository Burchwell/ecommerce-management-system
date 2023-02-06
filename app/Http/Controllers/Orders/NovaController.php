<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class NovaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rates(Request $request)
    {
        $param = $request->all();
        dd($param);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $client = new Client();
        try {
            $response = $client->get("https://nova.skaraudio.com/api/order/{$id}?api_token=Qq1BFP5EtYSgHuKCYfCNwr1S8LIKbuAjXK5iw7NvA3c0zZthD1IIZMlCDJcK");
            if ($response->getStatusCode() === 200) {
                $order = json_decode($response->getBody()->getContents(), true);
                return response()->json($order);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
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
