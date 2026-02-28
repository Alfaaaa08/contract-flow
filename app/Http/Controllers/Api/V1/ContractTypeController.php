<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ContractTypeResource;
use App\Http\Resources\V1\ContractTypeCollection;
use App\Http\Resources\V1\ContractResource;
use App\Models\ContractType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ContractTypeController extends Controller {
	/**
	 * Display a listing of contract types.
	 * GET /api/v1/contract-types
	 * Filters: ?search=procurement&per_page=20
	 */
	public function index(Request $request): JsonResponse {
		$perPage = $request->input('per_page', 15);
		$search = $request->input('search');

		$types = ContractType::query()
			->when($search, function ($query, $search) {
				$query->where('name', 'like', "%{$search}%");
			})
			->orderBy('name')
			->paginate($perPage);

		return response()->json(
			new ContractTypeCollection($types)
		);
	}

	/**
	 * Store a newly created contract type.
	 * POST /api/v1/contract-types
	 */
	public function store(Request $request): JsonResponse {
		$validated = $request->validate([
			'name' => [
				'required',
				'string',
				'max:255',
				Rule::unique('contract_types')->where('tenant_id', tenancy()->tenant?->id),
			],
			'icon' => ['nullable', 'string', 'max:50'],
		]);

		$type = ContractType::create($validated);

		return response()->json([
			'message' => 'Contract type created successfully',
			'data'    => new ContractTypeResource($type),
		], 201);
	}

	/**
	 * Display the specified contract type.
	 * GET /api/v1/contract-types/{id}
	 */
	public function show(ContractType $contractType): JsonResponse {
		return response()->json([
			'data' => new ContractTypeResource($contractType),
		]);
	}

	/**
	 * Update the specified contract type.
	 * PUT /api/v1/contract-types/{id}
	 */
	public function update(Request $request, ContractType $contractType): JsonResponse {
		$validated = $request->validate([
			'name' => [
				'required',
				'string',
				'max:255',
				Rule::unique('contract_types')
					->where('tenant_id', tenancy()->tenant?->id)
					->ignore($contractType->id),
			],
			'icon' => ['nullable', 'string', 'max:50'],
		]);

		$contractType->update($validated);

		return response()->json([
			'message' => 'Contract type updated successfully',
			'data'    => new ContractTypeResource($contractType->fresh()),
		]);
	}

	/**
	 * Remove the specified contract type.
	 * DELETE /api/v1/contract-types/{id}
	 */
	public function destroy(ContractType $contractType): JsonResponse {
		if ($contractType->contracts()->exists()) {
			return response()->json([
				'message' => 'Cannot delete contract type with associated contracts',
				'error'   => 'This contract type has contracts associated. Delete them first.',
			], 422);
		}

		$contractType->delete();

		return response()->json([
			'message' => 'Contract type deleted successfully',
		]);
	}

	/**
	 * Get contracts for a specific contract type.
	 * GET /api/v1/contract-types/{id}/contracts
	 */
	public function contracts(ContractType $contractType): JsonResponse {
		$contracts = $contractType->contracts()
			->with(['client'])
			->latest()
			->get();

		return response()->json([
			'data' => ContractResource::collection($contracts),
			'meta' => [
				'total' => $contracts->count(),
				'type'  => new ContractTypeResource($contractType),
			],
		]);
	}
}
