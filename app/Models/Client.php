<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model {
    use BelongsToTenant;
    use HasFactory;

    public function getTable(): string {
        if (config('database.default') === 'pgsql') {
            return 'public.clients';
        }
        return 'clients';
    }

    protected $fillable = ['name', 'email'];

    public function contracts(): HasMany {
        return $this->hasMany(Contract::class);
    }
}
