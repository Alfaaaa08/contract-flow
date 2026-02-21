<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant {
    use HasDatabase, HasDomains, HasFactory;

    /**
     * Custom columns that are not stored in the data JSON column.
     *
     * @return array<string>
     */
    public static function getCustomColumns(): array {
        return [
            'id',
            'name',
            'admin_email',
            'is_active',
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'id',
        'name',
        'admin_email',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the tenant's primary domain.
     */
    protected function primaryDomain(): Attribute {
        return Attribute::make(
            get: fn() => $this->domains->first()?->domain,
        );
    }

    /**
     * Scope a query to only include active tenants.
     */
    public function scopeActive($query) {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive tenants.
     */
    public function scopeInactive($query) {
        return $query->where('is_active', false);
    }

    /**
     * Check if the tenant is active.
     */
    public function isActive(): bool {
        return $this->is_active === true;
    }

    public function getConnectionName() {
        return config('database.default');
    }

    public static function booted() {
    }

    public function createDatabase(): void {
        return;
    }

    public function deleteDatabase(): void {
        return;
    }
}
