<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContractRequest;
use App\Http\Resources\V1\ContractResource;
use App\Http\Resources\V1\ContractCollection;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContractController extends Controller {
	/**
	 * Display a listing of contracts.
	 * GET /api/v1/contracts
	 * Filters: ?search=alpha&status=2&per_page=20
	 */
	public function index(Request $request) {
		$perPage = $request->input('per_page', 15);
		$search = $request->input('search');
		$status = $request->input('status');

		$contracts = Contract::query()
			->with(['client', 'type'])
			->when($search, function ($query, $search) {
				$query->where('name', 'like', "%{$search}%");
			})
			->when($status, function ($query, $status) {
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
			->paginate($perPage);

		return response()->json(
			new ContractCollection($contracts)
		);
	}

	/**
	 * Store a newly created contract.
	 * POST /api/v1/contracts
	 */
	public function store(StoreContractRequest $request) {
		$contract = Contract::create($request->validated());

		$contract->load(['client', 'type']);

		return response()->json([
			'message' => 'Contract created successfully',
			'data'    => new ContractResource($contract),
		], 201);
	}

	/**
	 * Display the specified contract.
	 * GET /api/v1/contracts/{id}
	 */
	public function show(Contract $contract) {
		$contract->load(['client', 'type']);

		return response()->json([
			'data' => new ContractResource($contract),
		]);
	}

	/**
	 * Update the specified contract.
	 * PUT /api/v1/contracts/{id}
	 */
	public function update(StoreContractRequest $request, Contract $contract) {
		$contract->update($request->validated());

		$contract->load(['client', 'type']);

		return response()->json([
			'message' => 'Contract updated successfully',
			'data'    => new ContractResource($contract),
		]);
	}

	/**
	 * Remove the specified contract.
	 * DELETE /api/v1/contracts/{id}
	 */
	public function destroy(Contract $contract) {
		$contract->delete();

		return response()->json([
			'message' => 'Contract deleted successfully',
		]);
	}

	/**
	 * Bulk delete contracts.
	 * DELETE /api/v1/contracts/bulk
	 */
	public function bulkDestroy(Request $request) {
		$validated = $request->validate([
			'ids'   => 'required|array',
			'ids.*' => 'integer|exists:contracts,id',
		]);

		$deleted = Contract::whereIn('id', $validated['ids'])->delete();

		return response()->json([
			'message' => "{$deleted} contracts deleted successfully",
			'deleted_count' => $deleted,
		]);
	}

	/**
	 * Get contract statistics.
	 * GET /api/v1/contracts/stats
	 */
	public function stats(): JsonResponse {
		return response()->json([
			'total' => Contract::count(),
			'by_status' => Contract::selectRaw('status, COUNT(*) as count')
				->groupBy('status')
				->pluck('count', 'status'),
			'total_value' => Contract::sum('value'),
			'expiring_soon' => Contract::where('end_date', '<=', now()->addDays(30))
				->where('end_date', '>=', now())
				->count(),
		]);
	}

	/**
	 * Get expiring contracts.
	 * GET /api/v1/contracts/expiring?days=30
	 */
	public function expiring(Request $request) {
		$days = $request->input('days', 30);

		$contracts = Contract::query()
			->with(['client', 'type'])
			->where('end_date', '<=', now()->addDays($days))
			->where('end_date', '>=', now())
			->get();

		return response()->json([
			'data' => ContractResource::collection($contracts),
			'meta' => [
				'total' => $contracts->count(),
				'days'  => $days,
			],
		]);
	}
}
