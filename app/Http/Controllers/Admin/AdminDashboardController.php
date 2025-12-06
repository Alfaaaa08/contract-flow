<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with tenant statistics.
     */
    public function index(): Response
    {
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('is_active', true)->count();
        $inactiveTenants = $totalTenants - $activeTenants;

        $recentTenants = Tenant::with('domains')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn (Tenant $tenant) => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'domain' => $tenant->domains->first()?->domain,
                'is_active' => $tenant->is_active,
                'created_at' => $tenant->created_at->format('M d, Y'),
            ]);

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total' => $totalTenants,
                'active' => $activeTenants,
                'inactive' => $inactiveTenants,
            ],
            'recentTenants' => $recentTenants,
        ]);
    }
}
