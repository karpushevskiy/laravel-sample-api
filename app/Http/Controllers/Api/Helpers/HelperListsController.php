<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers\Api\Helpers;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Role\RoleListResource;
use App\Http\Resources\User\UserListResource;
use App\Services\Items\RoleItemService;
use App\Services\Items\UserItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Helper Lists Controller
 *
 * @package \App\Http\Controllers\Api\Helpers
 */
class HelperListsController extends BaseApiController
{
    /**
     * HelperListsController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function showRolesList(Request $request) : JsonResponse
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $roleItemService = app(RoleItemService::class);

        $args   = $request->only([]);
        $result = $roleItemService->collection(array_merge($args, [
            'paginated'       => false,
            'exclude_by_name' => config('permission.project_roles.super_admin'),
        ]));

        return $this->respondWithCollection(
            RoleListResource::collection($result)
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function showUsersList(Request $request) : JsonResponse
    {
//        $this->authorize('authorizeAdminAccess', User::class);

        $userItemService = app(UserItemService::class);

        $args   = $request->only([]);
        $result = $userItemService->collection(array_merge($args, [
            'paginated' => false,
        ]));

        return $this->respondWithCollection(
            UserListResource::collection($result)
        );
    }
}
