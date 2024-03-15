<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers\Api\Helpers;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Helper Methods Controller
 *
 * @package \App\Http\Controllers\Api\Helpers
 */
class HelperMethodsController extends BaseApiController
{
    /**
     * HelpersController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function someMethod(Request $request) : JsonResponse
    {

    }
}
