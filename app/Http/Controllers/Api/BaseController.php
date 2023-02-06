<?php

namespace App\Http\Controllers\Api;
use App\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

/**
 * Class AuthController
 * @package App\Http\Controllers\Api
 */
class BaseController extends Controller
{
    /**
     * Get the token array structure.
     *
     * @param string $status
     * @param int $response
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getHttpResponse($response = 200, string $status = '', ...$data)
    {
        return response()->json([
            'status' => $status,
            'data' => $data[0]
        ], $response);
    }

    public function user() {

        if ($user = Auth::user()) {
            return $this->getHttpResponse("success", $user, 200);
        }
        return $this->getHttpResponse("error", "user not found.", 404);
    }
}
