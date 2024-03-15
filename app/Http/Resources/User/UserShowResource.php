<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Resources\User;

use App\Http\Resources\BaseJsonResource;
use App\Http\Resources\Role\RoleBaseResource;

/**
 * User Show Resource
 *
 * @package \App\Http\Resources\User
 */
class UserShowResource extends BaseJsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) : array
    {
        return [
            $this->mergeWhen(true, UserBaseResource::make($this->resource)),
            'roles' => RoleBaseResource::collection($this->whenLoaded('roles')),
            //
        ];
    }
}
