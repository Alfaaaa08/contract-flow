<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractRequest;
use App\Models\Contract;
use Illuminate\Http\Request;

class ContractController extends Controller {

    public function index(Request $request) {
    sleep(2);

        $search = $request->input('search');

        $contracts = Contract::with(['client', 'type'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
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
            'filters'   => $request->only(['search'])
        ]);
    }
    public function store(StoreContractRequest $request) {
        $contract = Contract::create($request->validated());

        return redirect()->back()->with('success', 'Contract created successfully!');
    }
}
