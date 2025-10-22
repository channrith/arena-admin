<?php

namespace App\Http\Middleware;

use App\Helpers\SettingHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiPostToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $settings = SettingHelper::getDefaultSettings();
        if ($request->header('token') !== $settings->post_api_token) {
            return response([
                'error' => [
                    'message' => 'Unauthenticated'
                ]
            ], 401);
        }
    
        return $next($request);
    }
}
