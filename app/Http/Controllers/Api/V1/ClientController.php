<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\V1\ClientResource;
use App\Http\Resources\V1\ClientCollection;
use App\Http\Resources\V1\ContractResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ClientController extends Controller {
	
/**
     * @OA\Get(
     *     path="/clients",
     *     tags={"Clients"},
     *     summary="List clients",
     *     description="Get paginated list of clients with optional search",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by name or email",
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
     *         description="Clients list",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Client")),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     )
     * )
     */
	public function index(Request $request): JsonResponse {
		$perPage = $request->input('per_page', 15);
		$search = $request->input('search');

		$clients = Client::query()
			->when($search, function ($query, $search) {
				$query->where('name', 'like', "%{$search}%")
					->orWhere('email', 'like', "%{$search}%");
			})
			->latest()
			->paginate($perPage);

		return response()->json(
			new ClientCollection($clients)
		);
	}

	/**
     * @OA\Post(
     *     path="/clients",
     *     tags={"Clients"},
     *     summary="Create client",
     *     description="Create a new client",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Acme Corporation"),
     *             @OA\Property(property="email", type="string", format="email", example="contact@acme.com"),
     *             @OA\Property(property="phone", type="string", example="11999999999")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Client created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/Client")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
	public function store(Request $request): JsonResponse {
		$validated = $request->validate([
			'name'  => ['required', 'string', 'max:255'],
			'email' => [
				'nullable',
				'email',
				'max:255',
				Rule::unique('clients')->where('tenant_id', tenancy()->tenant?->id),
			],
			'phone' => ['nullable', 'string', 'max:20'],
		]);

		$client = Client::create($validated);

		return response()->json([
			'message' => 'Client created successfully',
			'data'    => new ClientResource($client),
		], 201);
	}

	/**
     * @OA\Get(
     *     path="/clients/{id}",
     *     tags={"Clients"},
     *     summary="Get client details",
     *     description="Get a single client by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Client ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Client details",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Client")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Client not found")
     * )
     */
	public function show(Client $client): JsonResponse {
		return response()->json([
			'data' => new ClientResource($client),
		]);
	}

	/**
     * @OA\Put(
     *     path="/clients/{id}",
     *     tags={"Clients"},
     *     summary="Update client",
     *     description="Update an existing client",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Client ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="phone", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Client updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/Client")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Client not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
	public function update(Request $request, Client $client): JsonResponse {
		$validated = $request->validate([
			'name'  => ['required', 'string', 'max:255'],
			'email' => [
				'nullable',
				'email',
				'max:255',
				Rule::unique('clients')
					->where('tenant_id', tenancy()->tenant?->id)
					->ignore($client->id),
			],
			'phone' => ['nullable', 'string', 'max:20'],
		]);

		$client->update($validated);

		return response()->json([
			'message' => 'Client updated successfully',
			'data'    => new ClientResource($client->fresh()),
		]);
	}

	/**
     * @OA\Delete(
     *     path="/clients/{id}",
     *     tags={"Clients"},
     *     summary="Delete client",
     *     description="Delete a client (only if no contracts associated)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Client ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Client deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Cannot delete client with contracts",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Client not found")
     * )
     */
	public function destroy(Client $client): JsonResponse {
		if ($client->contracts()->exists()) {
			return response()->json([
				'message' => 'Cannot delete client with associated contracts',
				'error'   => 'This client has contracts associated. Delete them first.',
			], 422);
		}

		$client->delete();

		return response()->json([
			'message' => 'Client deleted successfully',
		]);
	}

	/**
     * @OA\Get(
     *     path="/clients/{id}/contracts",
     *     tags={"Clients"},
     *     summary="Get client contracts",
     *     description="Get all contracts for a specific client",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Client ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Client contracts",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Contract")),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="total", type="integer"),
     *                 @OA\Property(property="client", ref="#/components/schemas/Client")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Client not found")
     * )
     */
	public function contracts(Client $client): JsonResponse {
		$contracts = $client->contracts()
			->with(['type'])
			->latest()
			->get();

		return response()->json([
			'data' => ContractResource::collection($contracts),
			'meta' => [
				'total' => $contracts->count(),
				'client' => new ClientResource($client),
			],
		]);
	}
}
