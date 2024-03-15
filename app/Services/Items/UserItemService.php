<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\Items;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * User Item Service
 *
 * @package \App\Services\Items
 */
class UserItemService extends BaseItemService
{
    /**
     * UserItemService constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(User::class);
    }

    /**
     * @param array $args
     * @param array $with
     * @return mixed
     */
    public function collection(array $args = [], array $with = []) : mixed
    {
        return parent::collection($args, array_merge($with, ['roles']));
    }

    /**
     * @param int  $id
     * @param bool $throwExceptionIfFail
     * @return User|null
     */
    public function find(int $id, bool $throwExceptionIfFail = true) : ?User
    {
        return parent::find($id, $throwExceptionIfFail);
    }

    /**
     * @param array $attributes
     * @return User|null
     */
    public function create(array $attributes, array $loadMissing = []) : ?User
    {
        return parent::create($attributes, array_merge($loadMissing, ['roles']));
    }

    /**
     * @param int   $id
     * @param array $attributes
     * @return bool
     */
    public function update(int $id, array $attributes) : bool
    {
        return parent::update($id, $attributes);
    }

    /**
     * @param int $id
     * @return bool|null
     */
    public function delete(int $id) : ?bool
    {
        return parent::delete($id);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function restore(int $id) : bool
    {
        return parent::restore($id);
    }

    /*
    |--------------------------------------------------------------------------
    | Additional methods
    |--------------------------------------------------------------------------
    */
    /**
     * @param array $attributes
     * @return void
     */
    protected function prepareAttributes(array &$attributes) : void
    {
        // Prepare 'password' field
        if (!empty($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        }
    }

    /**
     * @param User  $item
     * @param array $attributes
     * @return void
     */
    protected function updateRelations(User $item, array $attributes) : void
    {
        /*
         * Update 'roles' relation
         */
        if (!empty($attributes['role'])) {
            $roleIds = (array) $attributes['role'];

//            // Remove 'super-admin' Role ID from selected roles
//            $superAdminRole = Role::whereIn('name', [config('permission.project_roles.super_admin')])->first();
//
//            if ($superAdminRole && ($key = array_search($superAdminRole->id, $roleIds)) !== false) {
//                unset($roleIds[$key]);
//            }

            if (!empty($roleIds)) {
                $item->syncRoles($roleIds);
            }
        }
    }
}
