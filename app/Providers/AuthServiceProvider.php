<?php

namespace App\Providers;

use Exception;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use App\Http\Traits\StatusTraits;
use App\Http\Traits\StatusCodes;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    use StatusTraits;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->header('Authorization')) {
                $token = @explode(' ', $request->header('Authorization'))[1];
                
                if(!$token) {
                    $res['status'] = false;
                    $res['error'] = 'Token not provided!';

                    return $this->respond($res, StatusCodes::BAD_REQUEST);
                }

                try {
                    $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);

                    try {
                        $user = User::findOrFail($credentials->id);
                        $request->auth = $user;

                        return $user;
                    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                        $res['status'] = false;
                        $res['error'] = $e->getMessage();

                        return $this->respond($res, StatusCodes::NOT_FOUND);
                    }
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
        });

        // Define update-delete authorization gate
        Gate::define('update-delete', function ($user, $record) {
            return $user->id == $record->user_id;
        });
    }
}
