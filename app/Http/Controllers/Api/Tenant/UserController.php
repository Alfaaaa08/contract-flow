<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Api\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreUserRequest;
use App\Http\Requests\Tenant\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Tenant API User Controller.
 *
 * Handles CRUD operations for user management via API.
 * Restricted to tenant admins only.
 */
class UserController extends Controller
{
    use ApiResponse;

    /**
     * Create a new controller instance.
     *
     * @param  UserService  $userService
     */
    public function __construct(
        private UserService $userService
    ) {}

    /**
     * Display a listing of users.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = User::latest()->paginate(15);

        return $this->paginated(
            UserResource::collection($users),
            'Users retrieved successfully'
        );
    }

    /**
     * Store a newly created user.
     *
     * @param  StoreUserRequest  $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->validated());

        return $this->created(
            new UserResource($user),
            'User created successfully'
        );
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        return $this->success(
            new UserResource($user),
            'User retrieved successfully'
        );
    }

    /**
     * Update the specified user.
     *
     * @param  UpdateUserRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user = $this->userService->updateUser($user, $request->validated());

        return $this->success(
            new UserResource($user),
            'User updated successfully'
        );
    }

    /**
     * Remove the specified user.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $this->userService->deleteUser($user, $request->user()->id);

            return $this->noContent('User deleted successfully');
        } catch (\InvalidArgumentException $e) {
            return $this->error($e->getMessage(), null, 422);
        }
    }
}
