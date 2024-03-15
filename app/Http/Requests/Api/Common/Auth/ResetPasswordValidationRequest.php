<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Api\Common\Auth;

use App\Http\Requests\Api\BaseApiRequest;

/**
 * Reset Password Validation Request
 *
 * @package \App\Http\Requests\Api\Common\Auth
 */
class ResetPasswordValidationRequest extends BaseApiRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
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
