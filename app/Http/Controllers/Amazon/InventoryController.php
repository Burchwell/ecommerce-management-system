<?php

namespace App\Http\Controllers\Amazon;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * Class InventroyController
 * @package App\Http\Controllers
 */
class InventoryController extends Controller
{
    public function listInventorySupply($date) {
        $date = str_replace('+00:00', 'Z', gmdate('c', strtotime($date)));
        $fba = DB::connection('tools')->table('api_fbaitemmodel')->get()->where(
            [
                ['mod_timestamp', '>=', DB::raw(date('Y-m-d 00:00:00'))],
                ['mod_timestamp', '<=', DB::raw(date('Y-m-d 23:59:59'))],
            ]);
        dd($fba);

    }
}
