<?php

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Model;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class CustomLogging
 *
 * @package App\Helpers
 * @method static \Illuminate\Database\Eloquent\Builder|CustomLogging newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomLogging newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomLogging query()
 * @mixin \Eloquent
 */
class CustomLogging extends Model
{
    /**
     * @param $message
     * @param string $name
     * @param string $path
     */
    public static function info($message, $name = 'custom',  $path = 'logs/custom_info.log') {
        $log = new Logger($name);
        $log->pushHandler(new StreamHandler(storage_path($path)));
        $log->info($message);
    }

    public static function log($message, $name = 'custom',  $path = 'logs/custom_log.log') {
        $log = new Logger($name);
        $log->pushHandler(new StreamHandler(storage_path($path)));
        $log->log($message);
    }

    public static function alert($message, $name = 'custom',  $path = 'logs/custom_alert.log') {
        $log = new Logger($name);
        $log->pushHandler(new StreamHandler(storage_path($path)));
        $log->alert($message);
    }

    public static function debug($message, $name = 'custom',  $path = 'logs/custom_debug.log') {
        $log = new Logger($name);
        $log->pushHandler(new StreamHandler(storage_path($path)));
        $log->debug($message);
    }
}
