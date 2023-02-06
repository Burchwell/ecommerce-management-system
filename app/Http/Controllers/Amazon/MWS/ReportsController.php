<?php

namespace App\Http\Controllers\Amazon\MWS;

use App\Http\Controllers\Amazon\MwsController;
use App\Traits\FbaFulfillmentTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class ordersController
 * @package App\Http\Controllers\Amazon\MWS
 *
 * @MaxRequestQuota 15 Requests
 * @RestoreRate 1 Request / Minute
 * @Hourly Request Quota 60 Requests / Min
 */

class ReportsController extends MwsController
{
    use FbaFulfillmentTrait;

    protected $uri = 'Reports';
    protected $version = '2009-01-01';
    protected $data = [];


    /**
     * Get Report By ID
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getReport($id) {
        $response = $this->getReportById($id);
        $report = [];
        $reportRows = explode("\r\n", $response);
        foreach ($reportRows as $row) {
            $report[] = explode("\t", $row);
        }
        return response()->json(compact('report'));
    }

    /**
     * Get Report By ID
     *
     * @param null $nextToken
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getReportList(Request $request) {
        $param = $request->input();

        $report_list = $this->Client->send('GetReportList', "/{$this->uri}/{$this->version}", $param);
        return response()->json(compact('report_list'));
    }

    /**
     * Get Report By ID
     *
     * @param null $nextToken
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getReportRequestList(Request $request) {
        $param = $request->input();
        $report = $this->Client->send('GetReportRequestList', "/{$this->uri}/{$this->version}", $param);
        return response()->json(compact('report'));
    }



    /**
     * Get Report Count
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getReportCount(Request $request) {
        $param = $request->input();
        $count = $this->Client->send('GetReportCount', "{$this->uri}/{$this->version}", $param);
        return response()->json(compact('count'));
    }

}
