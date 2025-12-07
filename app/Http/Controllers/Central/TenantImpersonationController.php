<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Services\ImpersonationService;
use Illuminate\Http\RedirectResponse;
use InvalidArgumentException;

class TenantImpersonationController extends Controller
{
    public function __construct(
        protected ImpersonationService $impersonationService
    ) {}

    /**
     * Generate a signed URL to login as a tenant's admin user.
     */
    public function generateLoginUrl(Tenant $tenant): RedirectResponse
    {
        if (! $tenant->is_active) {
            return back()->with('error', 'Cannot login to an inactive tenant.');
        }

        try {
            $url = $this->impersonationService->buildImpersonationUrl($tenant);

            return redirect()->away($url);
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
