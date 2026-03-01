<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Controller;
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
	 * @OA\Post(
	 *     path="/auth/register",
	 *     tags={"Authentication"},
	 *     summary="Register a new user and tenant",
	 *     description="Creates a new tenant and user, returns JWT token",
	 *     @OA\RequestBody(
	 *         required=true,
	 *         @OA\JsonContent(
	 *             required={"name","email","password","password_confirmation","company"},
	 *             @OA\Property(property="name", type="string", example="John Doe"),
	 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
	 *             @OA\Property(property="password", type="string", format="password", example="password123"),
	 *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123"),
	 *             @OA\Property(property="company", type="string", example="Acme Corp")
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=201,
	 *         description="User registered successfully",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="message", type="string", example="User registered successfully"),
	 *             @OA\Property(property="user", ref="#/components/schemas/User"),
	 *             @OA\Property(property="tenant", type="object",
	 *                 @OA\Property(property="id", type="string", example="acme-corp"),
	 *                 @OA\Property(property="name", type="string", example="Acme Corp"),
	 *                 @OA\Property(property="domain", type="string", example="acme-corp.contractflow.test")
	 *             ),
	 *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGci..."),
	 *             @OA\Property(property="token_type", type="string", example="bearer"),
	 *             @OA\Property(property="expires_in", type="integer", example=3600)
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=422,
	 *         description="Validation error",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="message", type="string"),
	 *             @OA\Property(property="errors", type="object")
	 *         )
	 *     )
	 * )
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

			$domain = $tenantId . '.contractflow.test';
			$tenant->domains()->create(['domain' => $domain]);

			tenancy()->initialize($tenant);

			$user = User::create([
				'name'      => $request->name,
				'email'     => $request->email,
				'password'  => bcrypt($request->password),
				'tenant_id' => $tenant->id,
			]);

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
	 * @OA\Post(
	 *     path="/auth/login",
	 *     tags={"Authentication"},
	 *     summary="Authenticate user",
	 *     description="Login with email and password, returns JWT token",
	 *     @OA\RequestBody(
	 *         required=true,
	 *         @OA\JsonContent(
	 *             required={"email","password"},
	 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
	 *             @OA\Property(property="password", type="string", format="password", example="password123")
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Login successful",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="message", type="string", example="Login successful"),
	 *             @OA\Property(property="user", ref="#/components/schemas/User"),
	 *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGci..."),
	 *             @OA\Property(property="token_type", type="string", example="bearer"),
	 *             @OA\Property(property="expires_in", type="integer", example=3600)
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="Invalid credentials",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="message", type="string", example="Invalid credentials"),
	 *             @OA\Property(property="error", type="string")
	 *         )
	 *     )
	 * )
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
	 * @OA\Post(
	 *     path="/auth/logout",
	 *     tags={"Authentication"},
	 *     summary="Logout user",
	 *     description="Invalidate the JWT token",
	 *     security={{"bearerAuth":{}}},
	 *     @OA\Response(
	 *         response=200,
	 *         description="Successfully logged out",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="message", type="string", example="Successfully logged out")
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="Unauthenticated",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="message", type="string", example="Unauthenticated")
	 *         )
	 *     )
	 * )
	 */
	public function logout(): JsonResponse {
		JWTAuth::invalidate(JWTAuth::getToken());

		return response()->json([
			'message' => 'Successfully logged out',
		]);
	}

	/**
	 * @OA\Post(
	 *     path="/auth/refresh",
	 *     tags={"Authentication"},
	 *     summary="Refresh JWT token",
	 *     description="Get a new JWT token using the current token",
	 *     security={{"bearerAuth":{}}},
	 *     @OA\Response(
	 *         response=200,
	 *         description="Token refreshed successfully",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="message", type="string", example="Token refreshed successfully"),
	 *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGci..."),
	 *             @OA\Property(property="token_type", type="string", example="bearer"),
	 *             @OA\Property(property="expires_in", type="integer", example=3600)
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="Token is invalid or expired",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="message", type="string"),
	 *             @OA\Property(property="error", type="string")
	 *         )
	 *     )
	 * )
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
	 * @OA\Get(
	 *     path="/auth/me",
	 *     tags={"Authentication"},
	 *     summary="Get authenticated user",
	 *     description="Returns the currently authenticated user",
	 *     security={{"bearerAuth":{}}},
	 *     @OA\Response(
	 *         response=200,
	 *         description="Authenticated user data",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="user", ref="#/components/schemas/User")
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="Unauthenticated",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="message", type="string", example="Unauthenticated")
	 *         )
	 *     )
	 * )
	 */
	public function me(): JsonResponse {
		$user = JWTAuth::user();

		return response()->json([
			'user' => UserResource::make($user),
		]);
	}
}
