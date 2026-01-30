<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model {
    use BelongsToTenant;

    protected $fillable = [
        'name',
        'client_id',
        'contract_type_id',
        'value',
        'end_date',
        'file_path',
        'status',
        'start_date',
    ];

    public function client(): BelongsTo {
        return $this->belongsTo(Client::class);
    }

    public function type(): BelongsTo {
        return $this->belongsTo(ContractType::class, 'contract_type_id');
    }
}
