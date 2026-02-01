<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model {
    use BelongsToTenant;

    protected $connection = 'pgsql';

    public function getTable()
    {
        return 'public.clients';
    }

    protected $fillable = ['name', 'email'];

    public function contracts(): HasMany {
        return $this->hasMany(Contract::class);
    }
}
