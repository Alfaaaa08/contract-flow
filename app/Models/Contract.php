<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use \App\Enums\ContractStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contract extends Model {
    use BelongsToTenant;
    use HasFactory;

    public function getTable() {
        if (config('database.default') === 'sqlite') {
            return 'contracts';
        }

        return 'public.contracts';
    }

    protected static function booted(): void {
        if (config('database.default') !== 'pgsql') {
            return;
        }

        static::creating(function ($contract) {
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

    public function getProgressAttribute(): int {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        $totalDays = $this->start_date->diffInDays($this->end_date);

        if ($totalDays <= 0) return 100;

        $elapsedDays = $this->start_date->diffInDays(now());

        $percentage = ($elapsedDays / $totalDays) * 100;

        return (int) max(0, min(100, $percentage));
    }
}
