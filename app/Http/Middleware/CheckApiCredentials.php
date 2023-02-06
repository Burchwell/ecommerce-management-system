<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Exceptions\MissingScopeException;
use Laravel\Passport\Http\Middleware\CheckClientCredentials;

/**
 * Class CheckApiCredentials
 * @package App\Http\Middleware
 */
class CheckApiCredentials extends CheckClientCredentials
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next, ...$scopes)
    {
        if ($request->has('api_token')) {
            $request->headers->set('Authorization', 'Bearer '.$request->get('api_token'));
        }

        if ($request->has('basic_auth')) {
            $basic_auth_array = explode(':', $request->get('basic_auth'));
            if (Auth::guard('web')->attempt(['email'=>$basic_auth_array[0], 'password' => $basic_auth_array[1]])) {
                $user  = Auth::guard('web')->user();
                $token = $user->createToken("{$user->email}:{$user->created_at}");
                $request->headers->set('Authorization', 'Bearer '.$token->accessToken);
            }
        }

        $auth_guard_middleware = app()->make(\Illuminate\Auth\Middleware\Authenticate::class);

        try {
            $response = $auth_guard_middleware->handle($request, $next, 'api');
        } catch (AuthenticationException $e) {
            $client_cred_middleware = app()->make(CheckClientCredentials::class);
            $response = $client_cred_middleware->handle($request, $next, ...$scopes);
        }

        return $response;
    }

    protected function validate($psr, $scopes)
    {
        $token = $this->repository->find($psr->getAttribute('oauth_access_token_id'));

        if (! $token) {
            throw new AuthenticationException;
        }

        if (in_array("*", $token->scopes, true)) {
            return;
        }

        foreach ($scopes as $scope) {
            if ($token->cant($scope)) {
                throw new MissingScopeException($scope);
            }
        }
    }
}
