<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use \App\Enums\ContractStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model {
    use BelongsToTenant;

    protected $connection = 'pgsql';

    public function getTable() {
        return 'public.contracts';
    }

    protected static function booted(): void {
        static::creating(function ($contract) {
            // Temporary: hardcode to tenant 'contractflow' until Auth is ready 
            $contract->tenant_id = 'contractflow';
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
        'status' => ContractStatus::class,
    ];

    public function client(): BelongsTo {
        return $this->belongsTo(Client::class);
    }

    public function type(): BelongsTo {
        return $this->belongsTo(ContractType::class, 'contract_type_id');
    }

    public function getDisplayStatusAttribute() {
        if ($this->status !== ContractStatus::ACTIVE) {
            return $this->status->label();
        }

        $endDate = \Illuminate\Support\Carbon::parse($this->end_date);

        if ($endDate?->isAfter(now()) && $endDate->diffInDays(now(), true) <= 30) {
            return 'Expiring';
        }

        return 'Active';
    }
}
