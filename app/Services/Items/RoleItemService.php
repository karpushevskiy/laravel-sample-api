<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\Items;

use App\Models\Role;

/**
 * Role Item Service
 *
 * @package \App\Services\Items
 */
class RoleItemService extends BaseItemService
{
    /**
     * RoleItemService constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(Role::class);
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
     * @return Role|null
     */
    public function find(int $id, bool $throwExceptionIfFail = true) : ?Role
    {
        return parent::find($id, $throwExceptionIfFail);
    }

    /**
     * @param array $attributes
     * @return Role|null
     */
    public function create(array $attributes, array $loadMissing = []) : ?Role
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
}
