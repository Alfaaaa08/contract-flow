<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class InitializeTenancyFromJWT {
    public function handle(Request $request, Closure $next) {
        try {
            $token = JWTAuth::getToken();

            if (!$token) {
                return $next($request);
            }

            $payload = JWTAuth::getPayload($token);

            $tenantId = $payload->get('tenant_id');

            if ($tenantId) {
                $tenant = Tenant::find($tenantId);

                if ($tenant) {
                    tenancy()->initialize($tenant);
                }
            }
        } catch (JWTException $e) {
        }

        return $next($request);
    }
}
