<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ClientResource;
use App\Http\Resources\V1\ClientCollection;
use App\Http\Resources\V1\ContractResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ClientController extends Controller {
	/**
	 * Display a listing of clients.
	 * GET /api/v1/clients
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
	 * Store a newly created client.
	 * POST /api/v1/clients
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
	 * Display the specified client.
	 * GET /api/v1/clients/{id}
	 */
	public function show(Client $client): JsonResponse {
		return response()->json([
			'data' => new ClientResource($client),
		]);
	}

	/**
	 * Update the specified client.
	 * PUT /api/v1/clients/{id}
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
	 * Remove the specified client.
	 * DELETE /api/v1/clients/{id}
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
	 * Get contracts for a specific client.
	 * GET /api/v1/clients/{id}/contracts
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
