<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Api\Common\Auth;

use App\Http\Requests\Api\BaseApiRequest;

/**
 * Register Request
 *
 * @package \App\Http\Requests\Api\Common\Auth
 */
class RegisterRequest extends BaseApiRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'first_name' => array_merge($this->textRules(), ['required']),
            'last_name'  => array_merge($this->textRules(), ['required']),
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => array_merge($this->passwordRules(), ['required']),
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
}
