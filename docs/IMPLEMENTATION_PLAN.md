# Multi-Tenant SaaS Starter Kit - Implementation Plan

## Overview

Build a professional, portfolio-ready multi-tenant SaaS starter with:
- **Central Admin App**: Manage tenants from a central dashboard
- **Tenant App**: Isolated workspace per company with auth, users, and projects

---

## Architecture Decision

### Admin Authentication: `is_admin` Column Approach

**Why this over a separate Admin model:**
- Simpler codebase (portfolio-friendly)
- Reuses existing auth infrastructure
- Single User model with role flag
- Easy to extend to full RBAC later

---

## Phase 2: Central Admin App

### Step 2.1: Database Migrations

**Files to create:**

1. `database/migrations/xxxx_add_is_admin_to_users_table.php`
```php
// Add: is_admin (boolean, default: false)
```

2. `database/migrations/xxxx_add_columns_to_tenants_table.php`
```php
// Add: name (string), admin_email (string, nullable), is_active (boolean, default: true)
```

3. `database/seeders/AdminSeeder.php`
```php
// Create default admin: admin@example.com / password
```

**Files to modify:**
- `database/seeders/DatabaseSeeder.php` - Call AdminSeeder

---

### Step 2.2: Admin Middleware

**Files to create:**

1. `app/Http/Middleware/EnsureUserIsAdmin.php`
```php
// Check auth()->user()->is_admin, redirect to /admin/login if false
```

**Files to modify:**
- `bootstrap/app.php` - Register 'admin' middleware alias

---

### Step 2.3: Admin Authentication

**Files to create:**

1. `app/Http/Controllers/Admin/Auth/AdminAuthenticatedSessionController.php`
   - `create()` - render `Admin/Auth/Login`
   - `store()` - validate + check is_admin + login
   - `destroy()` - logout

2. `routes/admin.php`
```php
Route::prefix('admin')->group(function () {
    // Guest: login
    Route::middleware('guest')->group(function () {
        Route::get('login', [..., 'create'])->name('admin.login');
        Route::post('login', [..., 'store']);
    });

    // Auth + Admin: dashboard, tenants
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::post('logout', [..., 'destroy'])->name('admin.logout');
        Route::resource('tenants', TenantController::class)->names('admin.tenants');
        Route::post('tenants/{tenant}/toggle-status', [..., 'toggleStatus']);
    });
});
```

3. `resources/js/Layouts/AdminLayout.jsx`
   - Similar to AuthenticatedLayout
   - Admin branding, tenant nav link

4. `resources/js/Pages/Admin/Auth/Login.jsx`
   - Login form using GuestLayout

**Files to modify:**
- `routes/web.php` - Include `admin.php` in central domain group

---

### Step 2.4: Admin Dashboard

**Files to create:**

1. `app/Http/Controllers/Admin/AdminDashboardController.php`
   - `index()` - return tenant count, active count, recent tenants

2. `resources/js/Pages/Admin/Dashboard.jsx`
   - Stats cards: Total tenants, Active tenants
   - Quick action: Create Tenant button
   - Recent tenants list

---

### Step 2.5: Tenant Management CRUD

**Files to create:**

1. `app/Http/Controllers/Admin/TenantController.php`
   - `index()` - paginated tenant list
   - `create()` - create form
   - `store()` - create tenant + domain + run migrations + seed admin
   - `show($tenant)` - tenant details
   - `edit($tenant)` - edit form
   - `update($tenant)` - update tenant
   - `destroy($tenant)` - delete tenant + database
   - `toggleStatus($tenant)` - activate/deactivate

2. `app/Http/Requests/Admin/StoreTenantRequest.php`
   - Validate: name (required), domain (required, unique), admin_email (required, email)

3. `app/Http/Requests/Admin/UpdateTenantRequest.php`
   - Validate: name, domain (unique except current), is_active

4. `app/Services/TenantService.php`
   - `createTenant(data)` - create tenant, domain, handle seeding
   - `deleteTenant(tenant)` - cleanup

5. React Pages:
   - `resources/js/Pages/Admin/Tenants/Index.jsx` - Table with actions
   - `resources/js/Pages/Admin/Tenants/Create.jsx` - Create form
   - `resources/js/Pages/Admin/Tenants/Edit.jsx` - Edit form
   - `resources/js/Pages/Admin/Tenants/Show.jsx` - Details view

6. Components:
   - `resources/js/Components/Admin/StatusBadge.jsx` - Active/Inactive badge

**Files to modify:**
- `app/Models/Tenant.php` - Add fillable, casts, accessors for name/admin_email/is_active
- `app/Providers/TenancyServiceProvider.php` - Enable SeedDatabase job (line 30)

---

### Step 2.6: Tenant Seeding on Creation

**Files to create:**

1. `database/seeders/TenantDatabaseSeeder.php`
```php
// Create tenant admin user from tenant->admin_email
// Password: 'password' (or generate random and email)
```

**Files to modify:**
- `config/tenancy.php` - Set seeder_parameters to use TenantDatabaseSeeder

---

## Phase 3: Tenant App

### Step 3.1: Tenant Database Migrations

**Files to create in `database/migrations/tenant/`:**

1. `0001_01_01_000000_create_users_table.php`
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->enum('role', ['admin', 'user'])->default('user');
    $table->rememberToken();
    $table->timestamps();
});

// Also: password_reset_tokens, sessions tables
```

2. `0001_01_01_000001_create_cache_table.php`
```php
// cache, cache_locks tables
```

3. `0001_01_01_000002_create_projects_table.php`
```php
Schema::create('projects', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->enum('status', ['draft', 'active', 'completed', 'archived'])->default('draft');
    $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
    $table->timestamps();
});
```

---

### Step 3.2: Tenant Models

**Files to create:**

1. `app/Models/Tenant/User.php`
```php
// Tenant-specific user with role attribute
// Relationships: hasMany(Project::class, 'created_by')
```

2. `app/Models/Tenant/Project.php`
```php
// Fillable: name, description, status, created_by
// Relationships: belongsTo(User::class, 'created_by')
```

---

### Step 3.3: Tenant Authentication

**Files to create:**

1. `app/Http/Controllers/Tenant/Auth/AuthenticatedSessionController.php`
   - Login for tenant users

2. `app/Http/Controllers/Tenant/Auth/RegisteredUserController.php`
   - Registration for tenant users

3. `app/Http/Controllers/Tenant/Auth/PasswordResetLinkController.php`
   - Password reset

4. `resources/js/Layouts/TenantLayout.jsx`
   - Tenant branding, nav: Dashboard, Users, Projects

5. `resources/js/Pages/Tenant/Auth/Login.jsx`
6. `resources/js/Pages/Tenant/Auth/Register.jsx`
7. `resources/js/Pages/Tenant/Auth/ForgotPassword.jsx`

**Files to modify:**
- `routes/tenant.php` - Add all auth routes

---

### Step 3.4: Tenant Dashboard

**Files to create:**

1. `app/Http/Controllers/Tenant/DashboardController.php`
   - `index()` - return user count, project count, tenant info

2. `resources/js/Pages/Tenant/Dashboard.jsx`
   - Stat cards, welcome message, recent projects

3. `resources/js/Components/Tenant/StatCard.jsx`

**Files to modify:**
- `routes/tenant.php` - Add dashboard route
- `app/Http/Middleware/HandleInertiaRequests.php` - Share tenant data

---

### Step 3.5: Tenant User Management

**Files to create:**

1. `app/Http/Controllers/Tenant/UserController.php`
   - Full CRUD for tenant users

2. `app/Http/Requests/Tenant/StoreUserRequest.php`
3. `app/Http/Requests/Tenant/UpdateUserRequest.php`

4. React Pages:
   - `resources/js/Pages/Tenant/Users/Index.jsx`
   - `resources/js/Pages/Tenant/Users/Create.jsx`
   - `resources/js/Pages/Tenant/Users/Edit.jsx`

**Files to modify:**
- `routes/tenant.php` - Add users resource routes

---

### Step 3.6: Projects CRUD Module

**Files to create:**

1. `app/Http/Controllers/Tenant/ProjectController.php`
   - Full CRUD

2. `app/Http/Requests/Tenant/StoreProjectRequest.php`
3. `app/Http/Requests/Tenant/UpdateProjectRequest.php`

4. React Pages:
   - `resources/js/Pages/Tenant/Projects/Index.jsx`
   - `resources/js/Pages/Tenant/Projects/Create.jsx`
   - `resources/js/Pages/Tenant/Projects/Edit.jsx`
   - `resources/js/Pages/Tenant/Projects/Show.jsx`

5. `resources/js/Components/Tenant/ProjectCard.jsx`

**Files to modify:**
- `routes/tenant.php` - Add projects resource routes

---

## File Summary

### New Files (36 total)

**Backend (19 files):**
- 3 migrations (central)
- 3 migrations (tenant)
- 2 seeders
- 1 middleware
- 1 service
- 7 controllers
- 6 form requests

**Frontend (17 files):**
- 2 layouts
- 13 pages
- 2 components

### Modified Files (7 total)
- `app/Models/Tenant.php`
- `app/Providers/TenancyServiceProvider.php`
- `app/Http/Middleware/HandleInertiaRequests.php`
- `bootstrap/app.php`
- `routes/web.php`
- `routes/tenant.php`
- `database/seeders/DatabaseSeeder.php`

---

## Routes Overview

### Central Domain Routes
```
/admin/login          GET/POST   Admin login
/admin                GET        Admin dashboard
/admin/logout         POST       Admin logout
/admin/tenants        GET        List tenants
/admin/tenants/create GET        Create tenant form
/admin/tenants        POST       Store tenant
/admin/tenants/{id}   GET        Show tenant
/admin/tenants/{id}/edit GET     Edit tenant form
/admin/tenants/{id}   PUT        Update tenant
/admin/tenants/{id}   DELETE     Delete tenant
/admin/tenants/{id}/toggle-status POST  Toggle active
```

### Tenant Domain Routes
```
/login                GET/POST   Tenant login
/register             GET/POST   Tenant register
/forgot-password      GET/POST   Password reset
/logout               POST       Logout
/dashboard            GET        Tenant dashboard
/users                GET        List users
/users/create         GET        Create user form
/users                POST       Store user
/users/{id}/edit      GET        Edit user form
/users/{id}           PUT        Update user
/users/{id}           DELETE     Delete user
/projects             GET        List projects
/projects/create      GET        Create project form
/projects             POST       Store project
/projects/{id}        GET        Show project
/projects/{id}/edit   GET        Edit project form
/projects/{id}        PUT        Update project
/projects/{id}        DELETE     Delete project
```
