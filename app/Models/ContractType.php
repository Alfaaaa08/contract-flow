<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContractType extends Model {
    use BelongsToTenant;

    protected $fillable = ['name'];

    public function contracts(): HasMany {
        return $this->hasMany(Contract::class);
    }
}
