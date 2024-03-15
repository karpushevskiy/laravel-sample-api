<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Admin\Users\CreateUserRequest;
use App\Http\Requests\Api\Admin\Users\UpdateUserRequest;
use App\Http\Resources\User\UserBaseResource;
use App\Http\Resources\User\UserEditResource;
use App\Http\Resources\User\UserShowResource;
use App\Models\User;
use App\Services\Items\UserItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Users Controller
 *
 * @package \App\Http\Controllers\Api\Admin
 */
class UsersController extends BaseController
{
    /**
     * @var UserItemService
     */
    protected $userItemService;

    /**
     * UsersController constructor.
     *
     * @param UserItemService $userItemService
     * @return void
     */
    public function __construct(UserItemService $userItemService)
    {
        $this->userItemService = $userItemService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        $this->authorize('authorizeAdminAccess', User::class);

        $args   = $request->only(['page', 'per_page', 'sort', 'order_by', 'full_name', 'roles']);
        $result = $this->userItemService->collection(array_merge($args, [
            'paginated' => true,
        ]));

        return $this->respondWithCollection(
            UserShowResource::collection($result),
        );
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user) : JsonResponse
    {
        $this->authorize('authorizeAdminAccess', User::class);

        return $this->respondWithResource(
            UserShowResource::make($user->loadMissing(['person', 'roles']))
        );
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function edit(User $user) : JsonResponse
    {
        $this->authorize('authorizeAdminAccess', User::class);

        return $this->respondWithResource(
            UserEditResource::make($user->loadMissing(['roles']))
        );
    }

    /**
     * @param CreateUserRequest $request
     * @return JsonResponse
     */
    public function store(CreateUserRequest $request) : JsonResponse
    {
        $this->authorize('authorizeAdminAccess', User::class);

        $attributes = $request->validated();
        $result     = $this->userItemService->create($attributes);

        return $this->respondWithResource(
            UserBaseResource::make($result->loadMissing(['roles']))
        );
    }

    /**
     * @param UpdateUserRequest $request
     * @param User              $user
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user) : JsonResponse
    {
        $this->authorize('authorizeAdminAccess', User::class);

        // TODO: Refactor this code for better performance.
        if (
            !$user->is_super_admin ||
            ($user->is_super_admin && $request->user()->id === $user->id)
        ) {
            $attributes = $request->validated();
            $result     = $this->userItemService->update($user->id, $attributes);

            return $result
                ? $this->respondSuccess()
                : $this->respondFailure();
        } else {
            return $this->respondFailure(__('exceptions.cannot_update_selected_item'));
        }
    }

    /**
     * @param Request $request
     * @param User    $user
     * @return JsonResponse
     */
    public function destroy(Request $request, User $user) : JsonResponse
    {
        $this->authorize('authorizeSuperAdminAccess', User::class);

        // TODO: Refactor this code for better performance.
        if (
            !$user->is_super_admin &&
            $request->user()->id !== (int) $user->id
        ) {
            $result = $this->userItemService->delete($user->id);

            return $result
                ? $this->respondSuccess()
                : $this->respondFailure();
        } else {
            return $this->respondFailure(__('exceptions.cannot_delete_selected_item'));
        }
    }

    /**
     * @param Request $request
     * @param User    $user
     * @return JsonResponse
     */
    public function restore(Request $request, User $user)
    {
        $this->authorize('authorizeAdminAccess', User::class);

        $result = $this->userItemService->update($user->id, [
            'deleted_at' => null,
        ]);

        return $result
            ? $this->respondSuccess()
            : $this->respondFailure();
    }
}
