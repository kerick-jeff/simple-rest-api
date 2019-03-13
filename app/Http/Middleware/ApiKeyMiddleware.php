<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiKey;
use App\Models\ApiKeyAccessEvent;
use App\Http\Traits\StatusTraits;
use App\Http\Traits\StatusCodes;

class ApiKeyMiddleware 
{
    
    use StatusTraits;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $header = $request->header('x-api-key');
        $apiKey = ApiKey::getByKey($header);

        if ($apiKey instanceof ApiKey) {
            $this->logAccessEvent($request, $apiKey);
            return $next($request);
        }

        $res['status'] = false;
        $res['error'] = 'Unauthorized! API key needed.';

        return $this->respond($res, StatusCodes::UNAUTHORIZED);
    }

    /**
     * Log an API key access event
     *
     * @param Request $request
     * @param ApiKey  $apiKey
     */
    protected function logAccessEvent(Request $request, ApiKey $apiKey)
    {
        $event = new ApiKeyAccessEvent();
        $event->api_key_id = $apiKey->id;
        $event->ip_address = $request->ip();
        $event->url        = $request->fullUrl();
        $event->save();
    }
}
