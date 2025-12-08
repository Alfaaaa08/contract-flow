<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ImpersonationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class ImpersonationController extends Controller
{
    public function __construct(
        protected ImpersonationService $impersonationService
    ) {}

    /**
     * Handle impersonation login from central admin.
     */
    public function login(Request $request): RedirectResponse
    {
        $token = $request->query('token');
        $signature = $request->query('signature');

        if (! $token || ! $signature) {
            abort(403, 'Invalid impersonation request.');
        }

        $tenant = tenancy()->tenant;

        if (! $tenant) {
            abort(403, 'Tenant not found.');
        }

        // Verify signature
        if (! $this->impersonationService->verifySignature($tenant->id, $token, $signature)) {
            abort(403, 'Invalid signature.');
        }

        // Verify token
        try {
            $this->impersonationService->verifyToken($token, $tenant->id);
        } catch (InvalidArgumentException $e) {
            abort(403, $e->getMessage());
        }

        // Find the tenant admin user
        $user = User::where('email', $tenant->admin_email)->first()
            ?? User::where('role', 'admin')->first();

        if (! $user) {
            abort(403, 'No admin user found for this tenant.');
        }

        // Login as the admin user
        Auth::login($user);

        // Mark session as impersonated for potential UI indication
        session(['impersonated_by_admin' => true]);

        return redirect()->route('tenant.dashboard')
            ->with('success', 'Logged in as tenant admin.');
    }
}
