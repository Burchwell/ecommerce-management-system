<?php

namespace App\Http\Controllers\Api;
use App\User;
use Carbon\Carbon;
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
class AuthController extends BaseController
{
    /**
     * Register new user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name'      => ['required'],
            'email'     => ['required', 'email', 'unique:users'],
            'password'  => ['required', 'min:8', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/', 'confirmed'],
            'password_confirmation' => ['required', 'same:password'],
            'role' => 'string|required'
        ]);

        if ($validator->fails()){
            return $this->getHttpResponse(422,"error", $validator->errors());
        }

        $register = $request->all();
        $register['password'] = Hash::make($register['password']);

        $user = User::create($register);
        $data['token'] = $this->getUserToken($user, "{$user->email}:{$user->created_at}");
        $data['user'] = $user;

        return $this->getHttpResponse(200,"success", $data);
    }

    /**
     * Login user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);
        if (Auth::guard('web')->attempt($credentials)) {
            $user  = Auth::guard('web')->user();
            $token = ($this->getUserToken($user, "{$user->email}:{$user->created_at}"))->accessToken;
            $expires_in = date('Y-m-d H:i:s', strtotime('+'.config('passport.access_token_expiry_days')." days"));

            return $this->getHttpResponse(200, 'success', compact('user', 'token', 'expires_in'));
        }

        return $this->getHttpResponse(401, 'error', 'unauthorized.');
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        if (auth()->logout()) {
            return $this->getHttpResponse(  200,'success', 'logged out.');
        }
        return $this->getHttpResponse(500, 'error', 'unknown error occured.');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAuthToken(Request $request) {
        return \response()->json('Auth token valid');
    }

    /**
     * Get User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser() {
        if ($user = Auth::user()) {
            return $this->getHttpResponse(200, 'success', $user);
        }
        return $this->getHttpResponse(401,'error',   "Unauthorized.");
    }

    /**
     * Create Access Token
     *
     * @param User $user
     * @param string|null $token_name
     * @return \Laravel\Passport\PersonalAccessTokenResult
     */
    public function getUserToken(User $user, string $token_name = null) {
        return $user->createToken($token_name);
    }
}
