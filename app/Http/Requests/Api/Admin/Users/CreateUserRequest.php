<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Api\Admin\Users;

use App\Http\Requests\Api\BaseApiRequest;

/**
 * Create User Request
 *
 * @package \App\Http\Requests\Api\Admin\Users
 */
class CreateUserRequest extends BaseApiRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        $roles = config('permission.project_roles');

        return [
            'first_name' => array_merge($this->textRules(), ['required']),
            'last_name'  => array_merge($this->textRules(), ['nullable']),
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => array_merge($this->passwordRules(), ['required']),
            'role'       => ['required', $this->roleExistRule($roles)],
        ];
    }

    /**
     * @return array
     */
    public function attributes() : array
    {
        return [
            //
        ];
    }

    /**
     * @param array|int|string|null $key
     * @param mixed                 $default
     * @return mixed
     */
    public function validated($key = null, $default = null) : array
    {
        return array_merge(parent::validated(), [
            //
        ]);
    }
}
