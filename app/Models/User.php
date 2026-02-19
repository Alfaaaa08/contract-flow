<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable {

    public function getTable(): string {
        if (config('database.default') === 'pgsql') {
            return 'public.users';
        }
        return 'users';
    }
    
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Available tenant user roles.
     */
    public const ROLES = [
        'admin' => 'Admin',
        'user' => 'User',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Check if the user is a central admin (for admin panel access).
     */
    public function isAdmin(): bool {
        return $this->is_admin === true;
    }

    /**
     * Check if the user is a tenant admin (for tenant app access).
     * Used in tenant context where 'role' column exists.
     */
    public function isTenantAdmin(): bool {
        return $this->role === 'admin';
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole(string $role): bool {
        return $this->role === $role;
    }

    /**
     * Get the projects created by this user.
     * Only available in tenant context.
     */
    public function projects(): HasMany {
        return $this->hasMany(Project::class, 'created_by');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'tenant_id' => $this->tenant_id,
        ];
    }
}
