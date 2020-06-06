<?php

namespace App\Http\Middleware\User;

use Closure;
use DB;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $api_key_count = DB::table('api_key')
            ->where('user_id', $request->user_id)
            ->where('api_key', $request->api_key)
            ->count();

        if ($api_key_count > 0) {

            return $next($request);
        } else {

            $response = [
                'code'    => 200,
                'status'  => false,
                'data'    => [],
                'message' => 'Authentication Failed! Please login',
            ];

            return response()->json($response, 200);
        }
    }
}
