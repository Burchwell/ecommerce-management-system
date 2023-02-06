<?php

namespace App\Http\Controllers;


use App\Helpers\Tracking\FedEx;
use App\Http\Resources\Labels\LabelResource;
use App\Http\Resources\Labels\LabelResourceCollection;
use App\Jobs\UpdateTrackingJob;
use App\Models\Label;
use App\Models\Order;
use App\Traits\Tracking;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\EachPromise;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Log;
use Response;
use Symfony\Component\Console\Input\Input;

class ShippingLabelsLogController extends Controller
{
    use Tracking;

    private $results = [];

    public function index()
    {
        $request = array_filter(Request::input());
        $perPage = $request['perPage'] ?: $this->defaultPerPage;
        $results = Label::select('*');

        $filters = [];

        if (isset($request['sortBy'])) {
            $results->orderBy($request['sortBy'], (($request['sortDesc'] === 'true') ? 'DESC' : 'ASC'));
        }
        if (isset($request['filter']) && $request['filter'] !== "null") {
            $filters[] = ['order_number', 'LIKE', "%{$request['filter']}%"];
            $filters[] = ['computer_name', 'LIKE', "%{$request['filter']}%"];
        }

        if (empty($filters)) {
            $labels = new LabelResourceCollection(LabelResource::collection($results->paginate($perPage)));
            return response()->json(compact('labels'));
        }

        $results->where(function ($query) use ($filters) {
            for ($i = 0, $iMax = count($filters); $i < $iMax; $i++) {
                if ($i !== 0) {
                    $query->orWhere($filters[$i][0], $filters[$i][1], $filters[$i][2]);
                } else {
                    $query->where($filters[$i][0], $filters[$i][1], $filters[$i][2]);
                }
            }
            return $query;
        });
        $labels = new LabelResourceCollection(LabelResource::collection($results->paginate($perPage)));
        return response()->json(compact('labels'));
    }

    public function printPDF($id)
    {
        try {
            $label = Label::find($id);

            if (is_object($label)) {
                $filename = $label->order_number . time() . ".pdf";
                $path = storage_path('pdfs/labels/' . $label->order_number . '-final.pdf');

                return Response::make(file_get_contents($path), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $filename . '"'
                ]);
            }
        } catch (Exception $e) {
            return response()->json($e->getTrace());
        }
    }

    public function downloadCSV(\Illuminate\Http\Request $request)
    {
        $filters = [];
        if ($request->has('printed_at_start')) {
            $filters[] = ['printed_at', '>', date('Y-m-d 00:00:00', strtotime($request->get('printed_at_start')))];
        }
        if ($request->has('printed_at_end')) {
            $filters[] = ['printed_at', '<', date('Y-m-d 23:59:59', strtotime($request->get('printed_at_end')))];
        }

        $query = Label::leftJoin('orders', function ($query) {
            $query->on('orders.order_number', '=', 'labels.order_number');
        })->select(['labels.id', 'labels.order_number', DB::raw("CASE WHEN printed = 1 THEN 'Yes' ELSE 'No' END as printed"), 'printed_at', 'shipment_tracking_number', 'shipment_carrier', 'shipment_code', 'shipment_special_services', 'shipment_tracking_last_event', 'shipment_tracking_last_updated_api_at']);

        if (!empty($filters)) {
            $query->where($filters);
        }


        $labels = $query->orderBy('printed_at', 'DESC')->get()->toArray();

        $columns = array_keys($labels[0]);

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=AutoPrintLog_" . time() . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $callback = function () use ($labels, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($labels as $label) {
                fputcsv($file, $label);
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }

    public function updateTracking(\Illuminate\Http\Request $request)
    {
        Log::info('Update Tracking started...' . Carbon::now()->format('c'));
        if ($request->has('order_number')) {
            $order = Order::where('order_number', '=', $request->get('order_number'))->first();
            if (!empty($order)) {
                Log::info("Trying to update tracking for Order # {$order->order_number}.");

                $updates = $this->_getTrackingUpdates($order->shipment_tracking_number);
                if ($updates !== null) {
                    $order->update($updates);
                    $order->save();
                    Log::info("Tracking for Order # {$order->order_number} updated.");
                } else {
                    Log::info("No shipping info for Order # {$order->order_number} found.");
                }
            } else {
                Log::info($request->get('order_number') . " not found.");
            }
            return response()->json(compact('order'));
        } else {
            $filters = $this->_getFilters($request);
            $dispatched = $this->dispatch(new UpdateTrackingJob($filters));
            return response()->json(compact('dispatched'));
        }


    }

    private function _getFilters(\Illuminate\Http\Request $request)
    {
        $filters = [];
            if ($request->has('printed_at_start')) {
                $filters[] = ['printed_at', '>', date('Y-m-d 00:00:00', strtotime($request->get('printed_at_start')))];
            }

            if ($request->has('printed_at_end')) {
                $filters[] = ['printed_at', '<', date('Y-m-d 23:59:59', strtotime($request->get('printed_at_end')))];
            }

            if ($request->has('carrier')) {
                $filters[] = ['shipment_carrier', '=', $request->get('carrier')];
            }

        return $filters;
    }
}
