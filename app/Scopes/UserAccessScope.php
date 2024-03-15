<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

/**
 * Eloquent Query Builder User Access Scope
 *
 * @package \App\Scopes
 */
class UserAccessScope implements Scope
{
    /**
     * Scope a query to only include models that a current User has assigned.
     * To use this scope in the model, connect it via the boot method: static::addGlobalScope(new UserAccessScope);
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model   $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $user = Auth::guard('sanctum')->user();

        if ($user) {
            $user = $user->loadMissing(['roles.permissions']);

            if (!$user->hasRole(config('permission.project_roles.super_admin'))) {
                $builder->whereHas('user', function ($query) use ($user) {
                    $query->where('id', '=', $user->id);
                });
            }
        }
    }
}
