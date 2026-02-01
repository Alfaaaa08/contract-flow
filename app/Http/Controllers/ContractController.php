<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractRequest;
use App\Models\Contract;

class ContractController extends Controller {

    public function index() {
        return inertia('Contracts/Contracts', [
            'contracts' => Contract::with(['client', 'type'])
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
                ]),
        ]);
    }
    public function store(StoreContractRequest $request) {
        $contract = Contract::create($request->validated());

        return redirect()->back()->with('success', 'Contract created successfully!');
    }
}
