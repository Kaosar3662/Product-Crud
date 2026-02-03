<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Notifications\ConfirmMail;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\UserRegisteredNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RegisterController extends BaseController
{
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'cpassword' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors(), 422);
        }

        // Start the transaction
        DB::beginTransaction();
        try {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $input['mail_token'] = Str::random(60);
            $input['email_verified_at'] = null;

            $user = User::create($input);
            $user->notify(new ConfirmMail($user));
            $admin = User::find(41);
            if ($admin) {
                $admin->notify(new UserRegisteredNotification($user->toArray()));
            }

            DB::commit();

            return $this->sendResponse(null, 'You are added successfully.', 201);
        } catch (\Exception $e) {

            DB::rollBack();
            return $this->sendError('Registration failed.', ['error' => 'An internal error occurred.'], 500);
        }
    }
    public function login(Request $request): JsonResponse
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();
            if (!$user->email_verified_at) {
                return $this->sendError('Email not verified', [
                    'email' => ['Please verify your email first']
                ], 403);
            }
            $success['token'] = $user->createToken('Myapp')->plainTextToken;
            $success['name'] = $user->name;


            return $this->sendResponse($success, 'You are logged in Successfully.');
        } else {

            return $this->sendError('Unauthorized', [
                'auth' => ['Invalid credentials']
            ], 401);
        }
    }
    public function verifyEmail($token)
        
        {
        $frontendUrl = 'http://localhost:5173/email-confirmed';
        $user = User::where('mail_token', $token)->first();

        if (!$user) {
            return $this->sendError('Invalid token', [], 404);
        }

        $user->email_verified_at = now();
        $user->mail_token = null;
        $user->save();

        return redirect($frontendUrl);
    }


    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate token
        $token = Str::random(60);

        $user->password_reset_token = $token;
        $user->password_reset_sent_at = now();
        $user->save();

        // Send the reset email
        $resetLink = "http://localhost:5173/reset-password?token={$token}&email={$user->email}";
        $user->notify(new ResetPasswordNotification($user, $resetLink));

        return $this->sendResponse(null, 'Password reset link sent to your email.');
    }

    public function validateResetToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
        ]);

        $user = User::where('email', $request->email)
                    ->where('password_reset_token', $request->token)
                    ->first();

        if (!$user) {
            return $this->sendError('Invalid token', null, 400);
        }

        // Check if token expired (60 minutes)
        if (!$user->password_reset_sent_at || now()->greaterThan(Carbon::parse($user->password_reset_sent_at)->addMinutes(60))) {
            return $this->sendError('Token expired', null, 400);
        }

        return $this->sendResponse(['valid' => true], 'Token is valid');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'new_password' => 'required|string|min:6',
            'confirm_password' => 'required|string|same:new_password',
        ]);

        $user = User::where('email', $request->email)
                    ->where('password_reset_token', $request->token)
                    ->first();

        if (!$user) {
            return $this->sendError('Invalid token', null, 400);
        }

        // Check if token expired (60 minutes)
        if (!$user->password_reset_sent_at || now()->greaterThan(Carbon::parse($user->password_reset_sent_at)->addMinutes(60))) {
            return $this->sendError('Token expired', null, 400);
        }

        $user->password = bcrypt($request->new_password);
        $user->password_reset_token = null;
        $user->password_reset_sent_at = null;
        $user->save();

        return $this->sendResponse(null, 'Password reset successfully');
    }
}
