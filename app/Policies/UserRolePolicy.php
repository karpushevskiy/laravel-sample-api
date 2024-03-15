<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Policies;

use App\Models\User;

/**
 * User Role Policy
 *
 * @package \App\Policies
 */
class UserRolePolicy extends BasePolicy
{
    /**
     * @param User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function authorizeSuperAdminAccess(User $user) : \Illuminate\Auth\Access\Response|bool
    {
        return $user->hasRole(config('permission.project_roles.super_admin'));
    }

    /**
     * @param User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function authorizeAdminAccess(User $user) : \Illuminate\Auth\Access\Response|bool
    {
        return $user->hasAnyRole([config('permission.project_roles.super_admin'), config('permission.project_roles.admin')]);
    }

    /**
     * @param User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function authorizeClientAccess(User $user) : \Illuminate\Auth\Access\Response|bool
    {
        return $user->hasRole(config('permission.project_roles.client'));
    }
}
