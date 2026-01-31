<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model {
    use BelongsToTenant;

    protected static function booted(): void {
        static::creating(function ($contract) {
            // Temporary: hardcode to tenant 2 until Auth is ready 
            $contract->tenant_id = 2;
        });
    }

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

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'value'      => 'decimal:2',
    ];

    public function client(): BelongsTo {
        return $this->belongsTo(Client::class);
    }

    public function type(): BelongsTo {
        return $this->belongsTo(ContractType::class, 'contract_type_id');
    }
}
