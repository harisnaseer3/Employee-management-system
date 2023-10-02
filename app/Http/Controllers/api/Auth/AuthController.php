<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\BaseController;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Jobs\SendVerificationMail;
use App\Models\Role;
use App\Models\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Exception;
use Illuminate\Support\Facades\DB;

class AuthController extends BaseController
{
    public function registerUser(RegisterUserRequest $request): JsonResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $user = User::create(
                    array_merge($request->only('first_name', 'last_name', 'email'), ['password' => bcrypt($request->password)])
                );
//                $user->assignRole(Role::ADMIN);

                SendVerificationMail::dispatch($request->email);
            });

            return $this->sendResponse([], 'User register successfully, Kindly verify the email for further process.');
            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
            return $this->sendError(['Email' => 'Something went wrong.' . $e]);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $this->sendError(['credentials' => ['Invalid credentials.']], 401);
        }

        $user = Auth::user();

        if (empty($user->email_verified_at)) {
            return $this->sendError(['credentials' => ['Email is not verified.']],);
        }

        $token = $user->createToken(User::AUTH_TOKEN)->accessToken;
        $role = $user->roles()->first()->name;

        return $this->sendResponse(['access_token' => $token, 'role' => $role, 'user' => $user->first_name, 'email' => $user->email], 'Successfully login to the system.');
    }


    public function forgotPassword(ForgetPasswordRequest $request)
    {
        Password::sendResetLink($request->all());

        return $this->sendResponse([], 'Reset password link sent on your email id.');
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $reset_password_status = Password::reset($request->all(), function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        if ($reset_password_status == Password::INVALID_TOKEN) {
            return $this->sendError(['token' => ['Invalid token.']], 400);
        }

        return $this->sendResponse([], 'Password has been reset successfully.');
    }

    public function logout(Request $request)
    {
        if (Auth::user()) {
            $user = Auth::user()->token();
            $user->revoke();

            return $this->sendResponse([], 'User logged out successfully.');
        }
        return $this->sendError(['error' => ['Invalid operation.']]);
    }


    public function updateProfile(UpdateProfileRequest $request)
    {

        $data = $request->all();
        $user = Auth::user();

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return $this->sendResponse([], 'Profile Updated Successfully.');
    }
}
