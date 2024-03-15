<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Common\Auth\ForgotPasswordRequest;
use App\Http\Requests\Api\Common\Auth\LoginRequest;
use App\Http\Requests\Api\Common\Auth\RegisterRequest;
use App\Http\Requests\Api\Common\Auth\ResetPasswordRequest;
use App\Http\Requests\Api\Common\Auth\ResetPasswordValidationRequest;
use App\Http\Resources\Auth\AuthUserResource;
use App\Services\Items\UserItemService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Authentication Controller
 *
 * @package \App\Http\Controllers\Api\Common
 */
class AuthenticationController extends BaseApiController
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(LoginRequest $request) : JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (!Auth::guard('web')->attempt($credentials)) {
            throw new AuthenticationException(__('auth.failed'));
        }

        $user  = $request->user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->respondWithResource(
            AuthUserResource::make($user),
            __('auth.logged_in')
        )->withHeaders(['Authorization' => $token]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request) : JsonResponse
    {
        $user = $request->user();

        if ($user && $token = $user->currentAccessToken()) {
            $token->delete();
        }

        return $this->respondSuccess(__('auth.logged_out'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function user(Request $request) : JsonResponse
    {
        $user = $request->user()
            ->loadMissing(['roles.permissions']);

        return $this->respondWithResource(
            AuthUserResource::make($user)
        );
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request) : JsonResponse
    {
        $userItemService = app(UserItemService::class);

        $attributes = $request->validated();
        $user       = $userItemService->create($attributes);

        if ($user) {
            $token = $user->createToken('auth-token')->plainTextToken;

            return $this->respondWithResource(
                AuthUserResource::make($user)
            )->withHeaders([
                'Authorization' => $token,
            ]);
        } else {
            return $this->respondFailure();
        }
    }

    /**
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request) : JsonResponse
    {
        $attributes = $request->validated();
        $result     = Password::sendResetLink($attributes);

        switch ($result) {
            case 'passwords.sent':
            case 'passwords.user':
                return $this->respondSuccess(__('passwords.sent'));
            case 'passwords.throttled':
                return $this->respondError(__('passwords.throttled'), 429);
            default:
                return $this->respondInternalError();
        }
    }

    /**
     * @param ResetPasswordValidationRequest $request
     * @return JsonResponse
     */
    public function validateResetPasswordToken(ResetPasswordValidationRequest $request) : JsonResponse
    {
        $credentials = $request->only(['email', 'token']);

        $passwordBroker = app(PasswordBroker::class);

        $user = $passwordBroker->getUser($credentials);

        return (!is_null($user) && $passwordBroker->tokenExists($user, $credentials['token']))
            ? $this->respondSuccess()
            : $this->respondValidationErrors(['token' => [__('passwords.invalid')]]);
    }

    /**
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request) : JsonResponse
    {
        $attributes = $request->validated();

        $currentUser = null;

        $result = Password::reset($attributes, function ($user, $password) use ($request, &$currentUser) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->save();

            $user->setRememberToken(Str::random(10));

            event(new PasswordReset($user));

            $currentUser = $user;
        });

        $success = $result == Password::PASSWORD_RESET;
        $message = __($result);

        if ($currentUser && $success && $tokens = $currentUser->tokens()) {
            $tokens->delete();
        }

        return $success
            ? $this->respondSuccess($message)
            : $this->respondValidationErrors([], $message);
    }
}
