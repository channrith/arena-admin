<?php

namespace App\Http\Middleware;

use App\Helpers\SettingHelper;
use App\Models\Service;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        // Prefer standard API token header name
        $incomingToken = $request->header('X-Api-Token')
            ?? $request->header('token'); // fallback support

        if (!$incomingToken) {
            return response()->json([
                'error' => [
                    'message' => 'Missing API token',
                    'code' => 'TOKEN_MISSING'
                ]
            ], 401);
        }

        // Cache settings for 1 minute (avoid re-querying DB on every request)
        $settings = Cache::remember('settings_default', 60, function () {
            return SettingHelper::getDefaultSettings();
        });

        if ($incomingToken === $settings->post_api_token) {
            $service = Service::where('code', 'acauto')->first();
            config(
                [
                    'api.service_id' => $service->id,
                    'api.service_code' => $service->code,
                ]
            );
            return $next($request);
        }

        return response([
            'error' => [
                'message' => 'Unauthenticated'
            ]
        ], 401);
    }
}
