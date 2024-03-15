<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Requests\Api\Common\Auth;

use App\Http\Requests\Api\BaseApiRequest;

/**
 * Forgot Password Request
 *
 * @package \App\Http\Requests\Api\Common\Auth
 */
class ForgotPasswordRequest extends BaseApiRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'email' => ['required', 'email'],
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
