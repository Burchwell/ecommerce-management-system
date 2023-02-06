<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CronitorController extends Controller
{


    //Get the cron statuses from Cronitor API
    public function getCronStatus() {

        $apiKey = env('CRONITOR_API_KEY');
        $monitorUrl = "https://cronitor.io/v2/monitors";

        $client = new Client(['headers' => ['Authorization' => 'Basic ZjkxNzJhNDY5YjNhNGY5Nzg0Y2ViZTIwYTQxZjYxN2Q6ZjkxNzJhNDY5YjNhNGY5Nzg0Y2ViZTIwYTQxZjYxN2Q=']]);
        $request = $client->request('GET',$monitorUrl);
        $result = $request->getBody()->getContents();
        return $result;
    }
}
