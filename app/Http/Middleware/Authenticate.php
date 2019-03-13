<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use App\Http\Traits\StatusTraits;
use App\Http\Traits\StatusCodes;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{

    use StatusTraits;

    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if ($this->auth->guard($guard)->guest()) {
            return response('Unauthorized.', 401);
        }

        if ($request->header('Authorization')) {
            $token = @explode(' ', $request->header('Authorization'))[1];
            
            if(!$token) {
                $res['status'] = false;
                $res['error'] = 'Token not provided!';

                return $this->respond($res, StatusCodes::BAD_REQUEST);
            }

            try {
                $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);

                $user = User::find($credentials->id);

                // Insert the user into the request object
                $request->auth = $user;

                return $next($request);
            } catch(ExpiredException $e) {
                $res['status'] = false;
                $res['error'] = 'Token has expired!';

                return $this->respond($res, StatusCodes::UNAUTHORIZED);
            } catch(Exception $e) {
                $res['status'] = false;
                $res['error'] = 'Invalid token!';

                return $this->respond($res, StatusCodes::UNAUTHORIZED);
            }
        } else {
            $res['status'] = false;
            $res['error'] = 'No authorization header present';

            return $this->respond($res, StatusCodes::BAD_REQUEST);
        }
    }
}
