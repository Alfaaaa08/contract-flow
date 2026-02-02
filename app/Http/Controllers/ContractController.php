<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractRequest;
use App\Enums\ContractStatus;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller {

    public function index(Request $request) {
        $search = strtoupper($request->input('search'));

        $contracts = Contract::with(['client', 'type'])
            ->when($search, function ($query, $search) {
                $query->where(DB::raw('UPPER(name)'), 'like', "%{$search}%");
            })
            ->when((int) $request->input('status'), function ($query, $status) {
                if ((int) $status === 5) {
                    $query->where('status', 2)
                        ->whereNotNull('end_date')
                        ->whereBetween('end_date', [now(), now()->addDays(30)]);
                } elseif ((int) $status === 2) {
                    $query->where('status', 2)
                        ->where(function ($query) {
                            $query->whereNull('end_date')
                                ->orWhere('end_date', '>', now()->addDays(30))
                                ->orWhere('end_date', '<', now());
                        });
                } else {
                    $query->where('status', $status);
                }
            })
            ->latest()
            ->get()
            ->map(fn($contract) => [
                'id'        => $contract->id,
                'name'      => $contract->name,
                'client'    => $contract->client->name,
                'type'      => $contract->type->name,
                'type_icon' => $contract->type->icon,
                'value'     => $contract->value ?: 0,
                'status'    => $contract->display_status,
                'end_date'  => $contract->end_date->format('d/m/Y'),
            ]);


        return inertia('Contracts/Contracts', [
            'contracts' => $contracts,
            'filters'   => $request->only(['search', 'status'])
        ]);
    }
    public function store(StoreContractRequest $request) {
        $contract = Contract::create($request->validated());

        return redirect()->back()->with('success', 'Contract created successfully!');
    }
}
