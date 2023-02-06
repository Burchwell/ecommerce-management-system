<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use SimpleXMLElement;

/**
 * Class FedEx
 * @package App\Helpers
 */
class Helpers
{
    public static function is_assoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
