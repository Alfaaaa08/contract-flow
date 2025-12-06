<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTenantRequest;
use App\Http\Requests\Admin\UpdateTenantRequest;
use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TenantController extends Controller
{
    public function __construct(
        protected TenantService $tenantService
    ) {}

    /**
     * Display a listing of tenants.
     */
    public function index(): Response
    {
        $tenants = Tenant::with('domains')
            ->latest()
            ->paginate(10)
            ->through(fn (Tenant $tenant) => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'domain' => $tenant->domains->first()?->domain,
                'admin_email' => $tenant->admin_email,
                'is_active' => $tenant->is_active,
                'created_at' => $tenant->created_at->format('M d, Y'),
            ]);

        return Inertia::render('Admin/Tenants/Index', [
            'tenants' => $tenants,
        ]);
    }

    /**
     * Show the form for creating a new tenant.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Tenants/Create');
    }

    /**
     * Store a newly created tenant.
     */
    public function store(StoreTenantRequest $request): RedirectResponse
    {
        $tenant = $this->tenantService->createTenant($request->validated());

        return redirect()
            ->route('admin.tenants.show', $tenant)
            ->with('success', 'Tenant created successfully.');
    }

    /**
     * Display the specified tenant.
     */
    public function show(Tenant $tenant): Response
    {
        $tenant->load('domains');

        return Inertia::render('Admin/Tenants/Show', [
            'tenant' => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'domain' => $tenant->domains->first()?->domain,
                'admin_email' => $tenant->admin_email,
                'is_active' => $tenant->is_active,
                'created_at' => $tenant->created_at->format('M d, Y \a\t g:i A'),
                'updated_at' => $tenant->updated_at->format('M d, Y \a\t g:i A'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified tenant.
     */
    public function edit(Tenant $tenant): Response
    {
        $tenant->load('domains');

        return Inertia::render('Admin/Tenants/Edit', [
            'tenant' => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'domain' => $tenant->domains->first()?->domain,
                'admin_email' => $tenant->admin_email,
                'is_active' => $tenant->is_active,
            ],
        ]);
    }

    /**
     * Update the specified tenant.
     */
    public function update(UpdateTenantRequest $request, Tenant $tenant): RedirectResponse
    {
        $this->tenantService->updateTenant($tenant, $request->validated());

        return redirect()
            ->route('admin.tenants.show', $tenant)
            ->with('success', 'Tenant updated successfully.');
    }

    /**
     * Remove the specified tenant.
     */
    public function destroy(Tenant $tenant): RedirectResponse
    {
        $this->tenantService->deleteTenant($tenant);

        return redirect()
            ->route('admin.tenants.index')
            ->with('success', 'Tenant deleted successfully.');
    }

    /**
     * Toggle the active status of a tenant.
     */
    public function toggleStatus(Tenant $tenant): RedirectResponse
    {
        $this->tenantService->toggleStatus($tenant);

        $status = $tenant->fresh()->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Tenant {$status} successfully.");
    }
}
