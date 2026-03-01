<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\V1\ContractTypeResource;
use App\Http\Resources\V1\ContractTypeCollection;
use App\Http\Resources\V1\ContractResource;
use App\Models\ContractType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ContractTypeController extends Controller {

	/**
	 * @OA\Get(
	 *     path="/contract-types",
	 *     tags={"Contract Types"},
	 *     summary="List contract types",
	 *     description="Get paginated list of contract types",
	 *     security={{"bearerAuth":{}}},
	 *     @OA\Parameter(
	 *         name="search",
	 *         in="query",
	 *         description="Search by name",
	 *         required=false,
	 *         @OA\Schema(type="string")
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
	 *         description="Contract types list",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ContractType")),
	 *             @OA\Property(property="links", type="object"),
	 *             @OA\Property(property="meta", type="object")
	 *         )
	 *     )
	 * )
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
	 * @OA\Post(
	 *     path="/contract-types",
	 *     tags={"Contract Types"},
	 *     summary="Create contract type",
	 *     description="Create a new contract type",
	 *     security={{"bearerAuth":{}}},
	 *     @OA\RequestBody(
	 *         required=true,
	 *         @OA\JsonContent(
	 *             required={"name"},
	 *             @OA\Property(property="name", type="string", example="Maintenance"),
	 *             @OA\Property(property="icon", type="string", example="wrench")
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=201,
	 *         description="Contract type created successfully",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="message", type="string"),
	 *             @OA\Property(property="data", ref="#/components/schemas/ContractType")
	 *         )
	 *     ),
	 *     @OA\Response(response=422, description="Validation error")
	 * )
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
	 * @OA\Get(
	 *     path="/contract-types/{id}",
	 *     tags={"Contract Types"},
	 *     summary="Get contract type details",
	 *     description="Get a single contract type by ID",
	 *     security={{"bearerAuth":{}}},
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         description="Contract Type ID",
	 *         required=true,
	 *         @OA\Schema(type="integer")
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Contract type details",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="data", ref="#/components/schemas/ContractType")
	 *         )
	 *     ),
	 *     @OA\Response(response=404, description="Contract type not found")
	 * )
	 */
	public function show(ContractType $contractType): JsonResponse {
		return response()->json([
			'data' => new ContractTypeResource($contractType),
		]);
	}

	/**
	 * @OA\Put(
	 *     path="/contract-types/{id}",
	 *     tags={"Contract Types"},
	 *     summary="Update contract type",
	 *     description="Update an existing contract type",
	 *     security={{"bearerAuth":{}}},
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         description="Contract Type ID",
	 *         required=true,
	 *         @OA\Schema(type="integer")
	 *     ),
	 *     @OA\RequestBody(
	 *         required=true,
	 *         @OA\JsonContent(
	 *             required={"name"},
	 *             @OA\Property(property="name", type="string"),
	 *             @OA\Property(property="icon", type="string")
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Contract type updated successfully",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="message", type="string"),
	 *             @OA\Property(property="data", ref="#/components/schemas/ContractType")
	 *         )
	 *     ),
	 *     @OA\Response(response=404, description="Contract type not found"),
	 *     @OA\Response(response=422, description="Validation error")
	 * )
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
	 * @OA\Delete(
	 *     path="/contract-types/{id}",
	 *     tags={"Contract Types"},
	 *     summary="Delete contract type",
	 *     description="Delete a contract type (only if no contracts associated)",
	 *     security={{"bearerAuth":{}}},
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         description="Contract Type ID",
	 *         required=true,
	 *         @OA\Schema(type="integer")
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Contract type deleted successfully",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="message", type="string")
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=422,
	 *         description="Cannot delete type with contracts",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="message", type="string"),
	 *             @OA\Property(property="error", type="string")
	 *         )
	 *     ),
	 *     @OA\Response(response=404, description="Contract type not found")
	 * )
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
	 * @OA\Get(
	 *     path="/contract-types/{id}/contracts",
	 *     tags={"Contract Types"},
	 *     summary="Get contracts by type",
	 *     description="Get all contracts for a specific type",
	 *     security={{"bearerAuth":{}}},
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         description="Contract Type ID",
	 *         required=true,
	 *         @OA\Schema(type="integer")
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Type contracts",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Contract")),
	 *             @OA\Property(property="meta", type="object",
	 *                 @OA\Property(property="total", type="integer"),
	 *                 @OA\Property(property="type", ref="#/components/schemas/ContractType")
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(response=404, description="Contract type not found")
	 * )
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
