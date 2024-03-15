<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Api\Common\Auth;

use App\Http\Requests\Api\BaseApiRequest;

/**
 * Login Request
 *
 * @package \App\Http\Requests\Api\Common\Auth
 */
class LoginRequest extends BaseApiRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
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
