<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;

class InitializeTenancyFromJWT
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth('api')->check()) {
            return $next($request);
        }

        /** @var \Tymon\JWTAuth\JWTGuard $guard */
        $guard = auth('api');

        $tenantId = $guard->payload()->get('tenant_id');

        if ($tenantId) {
            $tenant = Tenant::find($tenantId);
            
            if ($tenant) {
                tenancy()->initialize($tenant);
            }
        }

        return $next($request);
    }
}