<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends BaseController
{
    public function verify($user_id, VerifyEmailRequest $request) {
//        if (!$request->hasValidSignature()) {
//            return $this->sendError(["msg" => ["Invalid/Expired url provided."]], 401);
//        }

        $user = User::findOrFail($user_id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->to(url('/') . '/verify-email-link?email=' . $user->email);
    }

    public function resend() {
        if (auth()->user()->hasVerifiedEmail()) {
            return $this->sendError(["msg" => ["Email already verified."]], 400);
        }

        auth()->user()->sendEmailVerificationNotification();

        return $this->sendError(["msg" => ["IEmail verification link sent on your email id."]], 401);
    }
}
