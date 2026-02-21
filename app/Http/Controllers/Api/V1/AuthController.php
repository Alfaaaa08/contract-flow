<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller {
	/**
	 * Register a new user and tenant.
	 * 
	 * Creates a new tenant with domain, then creates the user associated with that tenant.
	 * Returns JWT token for immediate authentication.
	 *
	 * @param  RegisterRequest  $request
	 * @return JsonResponse
	 */
	public function register(RegisterRequest $request): JsonResponse {
		try {
			config([
				'tenancy.bootstrappers' => [],
				'tenancy.database.auto_create_tenant_databases' => false,
				'tenancy.database.auto_delete_tenant_databases' => false,
				'tenancy.features' => [],
			]);

			$tenantId = Str::slug($request->company);

			$tenant = Tenant::create([
				'id'   => $tenantId,
				'name' => $request->company,
			]);

			// Create domain
			$domain = $tenantId . '.contractflow.test';
			$tenant->domains()->create(['domain' => $domain]);

			// Initialize tenancy
			tenancy()->initialize($tenant);

			// Create user
			$user = User::create([
				'name'      => $request->name,
				'email'     => $request->email,
				'password'  => bcrypt($request->password),
				'tenant_id' => $tenant->id,
			]);
			
			// Generate token
			$token = JWTAuth::fromUser($user);

			return response()->json([
				'message' => 'User registered successfully',
				'user'    => UserResource::make($user),
				'tenant'  => [
					'id'     => $tenant->id,
					'name'   => $tenant->name,
					'domain' => $domain,
				],
				'token'      => $token,
				'token_type' => 'bearer',
				'expires_in' => config('jwt.ttl') * 60,
			], 201);
		} catch (\Exception $e) {
			return response()->json([
				'message' => 'Registration failed',
				'error'   => $e->getMessage(),
			], 500);
		}
	}

	/**
	 * Authenticate user and return JWT token.
	 *
	 * @param  LoginRequest  $request
	 * @return JsonResponse
	 */
	public function login(LoginRequest $request): JsonResponse {
		$credentials = $request->credentials();

		if (!$token = JWTAuth::attempt($credentials)) {
			return response()->json([
				'message' => 'Invalid credentials',
				'error'   => 'The provided credentials do not match our records.',
			], 401);
		}

		$user = JWTAuth::user();

		return response()->json([
			'message'    => 'Login successful',
			'user'       => UserResource::make($user),
			'token'      => $token,
			'token_type' => 'bearer',
			'expires_in' => config('jwt.ttl') * 60,
		]);
	}

	/**
	 * Log out the authenticated user (invalidate token).
	 *
	 * @return JsonResponse
	 */
	public function logout(): JsonResponse {
		JWTAuth::invalidate(JWTAuth::getToken());

		return response()->json([
			'message' => 'Successfully logged out',
		]);
	}

	/**
	 * Refresh the JWT token.
	 * 
	 * Returns a new token while invalidating the old one.
	 *
	 * @return JsonResponse
	 */
	public function refresh(): JsonResponse {
		try {
			$newToken = JWTAuth::refresh(JWTAuth::getToken());

			return response()->json([
				'message'    => 'Token refreshed successfully',
				'token'      => $newToken,
				'token_type' => 'bearer',
				'expires_in' => config('jwt.ttl') * 60,
			]);
		} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			return response()->json([
				'message' => 'Token is invalid',
				'error'   => $e->getMessage(),
			], 401);
		} catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
			return response()->json([
				'message' => 'Token has expired',
				'error'   => $e->getMessage(),
			], 401);
		}
	}

	/**
	 * Get the authenticated user.
	 *
	 * @return JsonResponse
	 */
	public function me(): JsonResponse {
		$user = JWTAuth::user();

		return response()->json([
			'user' => UserResource::make($user),
		]);
	}
}
