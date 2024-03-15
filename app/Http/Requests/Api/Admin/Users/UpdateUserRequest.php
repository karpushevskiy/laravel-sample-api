<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Api\Admin\Users;

use App\Models\User;
use Illuminate\Validation\Rule;

/**
 * Update User Request
 *
 * @package \App\Http\Requests\Api\Admin\Users
 */
class UpdateUserRequest extends CreateUserRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        $roles = config('permission.project_roles');

        return [
            'first_name' => array_merge($this->textRules(), ['sometimes']),
            'last_name'  => array_merge($this->textRules(), ['nullable']),
            'email'      => ['sometimes', 'email', Rule::unique(User::class)->ignore($this->route('user'))],
            'password'   => array_merge($this->passwordRules(), ['sometimes']),
            'role'       => ['sometimes', $this->roleExistRule($roles)],
        ];
    }

    /**
     * @return array
     */
    public function attributes() : array
    {
        return array_merge(parent::attributes(), [
            //
        ]);
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
