<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers\Api;

use App\Http\Traits\ApiResponse;
use Illuminate\Routing\Controller;

/**
 * Base Api Controller
 *
 * @package \App\Http\Controllers\Api
 */
abstract class BaseApiController extends Controller
{
    use ApiResponse;
}
