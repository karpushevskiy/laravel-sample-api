<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Resources\User;

use App\Http\Resources\BaseJsonResource;

/**
 * User Edit Resource
 *
 * @package \App\Http\Resources\User
 */
class UserEditResource extends BaseJsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) : array
    {
        return [
            $this->mergeWhen(true, UserBaseResource::make($this->resource)),
            //
        ];
    }
}
