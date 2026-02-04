<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContractType extends Model {
    use BelongsToTenant;
    use HasFactory;

    protected $connection;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);

        $this->connection = env('DB_CONNECTION', 'pgsql');
    }

    public function getTable() {
        if (config('database.default') === 'sqlite') {
            return 'contract_types';
        }

        return 'public.contract_types';
    }

    protected $fillable = ['name', 'icon', 'tenant_id'];

    public function contracts(): HasMany {
        return $this->hasMany(Contract::class);
    }
}
