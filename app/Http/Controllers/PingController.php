<?php

namespace App\Http\Controllers;

use App\Models\Ping;
use Illuminate\Http\Request;

class PingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pings = Ping::paginate(25);
        return response()->json($pings);
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
     * @param  \App\Models\Ping  $ping
     * @return \Illuminate\Http\Response
     */
    public function show(Ping $ping)
    {
        //
    }

    /**
     * UpdateJob the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ping  $ping
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ping $ping)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ping  $ping
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ping $ping)
    {
        //
    }
}
