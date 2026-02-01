<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContractType extends Model {
    use BelongsToTenant;

    protected $connection = 'pgsql';

    public function getTable() {
        return 'public.contract_types';
    }

    protected $fillable = ['name', 'icon'];

    public function contracts(): HasMany {
        return $this->hasMany(Contract::class);
    }
}
