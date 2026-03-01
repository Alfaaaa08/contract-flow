<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\StoreContractRequest;
use App\Http\Resources\V1\ContractResource;
use App\Http\Resources\V1\ContractCollection;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContractController extends Controller {

	/**
	 * @OA\Get(
	 *     path="/contracts",
	 *     tags={"Contracts"},
	 *     summary="List contracts",
	 *     description="Get paginated list of contracts with optional filters",
	 *     security={{"bearerAuth":{}}},
	 *     @OA\Parameter(
	 *         name="search",
	 *         in="query",
	 *         description="Search by contract name",
	 *         required=false,
	 *         @OA\Schema(type="string")
	 *     ),
	 *     @OA\Parameter(
	 *         name="status",
	 *         in="query",
	 *         description="Filter by status (1=Draft, 2=Active, 3=Completed, 5=Expiring Soon)",
	 *         required=false,
	 *         @OA\Schema(type="integer", enum={1, 2, 3, 5})
	 *     ),
	 *     @OA\Parameter(
	 *         name="per_page",
	 *         in="query",
	 *         description="Items per page",
	 *         required=false,
	 *         @OA\Schema(type="integer", default=15)
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Contracts list",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Contract")),
	 *             @OA\Property(property="links", type="object",
	 *                 @OA\Property(property="first", type="string"),
	 *                 @OA\Property(property="last", type="string"),
	 *                 @OA\Property(property="prev", type="string", nullable=true),
	 *                 @OA\Property(property="next", type="string", nullable=true)
	 *             ),
	 *             @OA\Property(property="meta", type="object",
	 *                 @OA\Property(property="current_page", type="integer"),
	 *                 @OA\Property(property="per_page", type="integer"),
	 *                 @OA\Property(property="total", type="integer")
	 *             )
	 *         )
	 *     )
	 * )
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
     * @OA\Post(
     *     path="/contracts",
     *     tags={"Contracts"},
     *     summary="Create contract",
     *     description="Create a new contract",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","client_id","contract_type_id","start_date","end_date","status"},
     *             @OA\Property(property="name", type="string", example="Alpha Project"),
     *             @OA\Property(property="client_id", type="integer", example=1),
     *             @OA\Property(property="contract_type_id", type="integer", example=1),
     *             @OA\Property(property="start_date", type="string", format="date", example="2026-01-01"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2026-12-31"),
     *             @OA\Property(property="value", type="number", format="float", example=50000.00),
     *             @OA\Property(property="status", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Contract created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/Contract")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
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
     * @OA\Get(
     *     path="/contracts/{id}",
     *     tags={"Contracts"},
     *     summary="Get contract details",
     *     description="Get a single contract by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Contract ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contract details",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Contract")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Contract not found")
     * )
     */
	public function show(Contract $contract) {
		$contract->load(['client', 'type']);

		return response()->json([
			'data' => new ContractResource($contract),
		]);
	}

	/**
     * @OA\Put(
     *     path="/contracts/{id}",
     *     tags={"Contracts"},
     *     summary="Update contract",
     *     description="Update an existing contract",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Contract ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","client_id","contract_type_id","start_date","end_date","status"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="client_id", type="integer"),
     *             @OA\Property(property="contract_type_id", type="integer"),
     *             @OA\Property(property="start_date", type="string", format="date"),
     *             @OA\Property(property="end_date", type="string", format="date"),
     *             @OA\Property(property="value", type="number", format="float"),
     *             @OA\Property(property="status", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contract updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/Contract")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Contract not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
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
     * @OA\Delete(
     *     path="/contracts/{id}",
     *     tags={"Contracts"},
     *     summary="Delete contract",
     *     description="Delete a contract",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Contract ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contract deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Contract not found")
     * )
     */
	public function destroy(Contract $contract) {
		$contract->delete();

		return response()->json([
			'message' => 'Contract deleted successfully',
		]);
	}

	/**
     * @OA\Delete(
     *     path="/contracts/bulk",
     *     tags={"Contracts"},
     *     summary="Bulk delete contracts",
     *     description="Delete multiple contracts at once",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"ids"},
     *             @OA\Property(property="ids", type="array", @OA\Items(type="integer"), example={1,2,3})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contracts deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="deleted_count", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
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
     * @OA\Get(
     *     path="/contracts/stats",
     *     tags={"Contracts"},
     *     summary="Get contract statistics",
     *     description="Get statistics about contracts",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Contract statistics",
     *         @OA\JsonContent(
     *             @OA\Property(property="total", type="integer", example=100),
     *             @OA\Property(property="by_status", type="object",
     *                 @OA\Property(property="1", type="integer"),
     *                 @OA\Property(property="2", type="integer"),
     *                 @OA\Property(property="3", type="integer")
     *             ),
     *             @OA\Property(property="total_value", type="number", format="float"),
     *             @OA\Property(property="expiring_soon", type="integer")
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/contracts/expiring",
     *     tags={"Contracts"},
     *     summary="Get expiring contracts",
     *     description="Get contracts expiring within specified days",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="days",
     *         in="query",
     *         description="Number of days",
     *         required=false,
     *         @OA\Schema(type="integer", default=30)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Expiring contracts",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Contract")),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="total", type="integer"),
     *                 @OA\Property(property="days", type="integer")
     *             )
     *         )
     *     )
     * )
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
