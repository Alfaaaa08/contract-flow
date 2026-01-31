<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractRequest;
use App\Models\Contract;

class ContractController extends Controller {
    public function store(StoreContractRequest $request) {
        $contract = Contract::create($request->validated());

        return redirect()->back()->with('success', 'Contract created successfully!');
    }
}
