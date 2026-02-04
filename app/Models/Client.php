<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model {
    use BelongsToTenant;
    use HasFactory;

    protected $connection;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);

        $this->connection = env('DB_CONNECTION', 'pgsql');
    }

    public function getConnectionName()
    {
        return config('database.default');
    }
    
    public function getTable()
    {
        $table = 'clients';
        
        if (config('database.default') === 'sqlite') {
            return $table;
        }

        return "public.{$table}";
    }

    protected $fillable = ['name', 'email'];

    public function contracts(): HasMany {
        return $this->hasMany(Contract::class);
    }
}
