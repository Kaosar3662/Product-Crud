<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Notifications\ConfirmMail;
use Illuminate\Support\Facades\Notification;
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
            return $this->sendError('Validtion error.', $validator->errors(), 422);
        };
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['mail_token'] = Str::random(60);
        $input['email_verified_at'] = null;
        $user = User::create($input);
        $user->notify(new ConfirmMail($user));

        return $this->sendResponse('Success', 'You are added Successfully.');
    }
    public function login(Request $request): JsonResponse
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();
            if (!$user->email_verified_at) {
                return $this->sendResponse('Email not verified', ['error' => 'Please verify your email first'], 403);
            }
            $success['token'] = $user->createToken('Myapp')->plainTextToken;
            $success['name'] = $user->name;


            return $this->sendResponse($success, 'You are logged in Successfully.');
        } else {

            return $this->sendResponse('Unauthorized', ['error' => 'Unauthorized']);
        }
    }
    public function verifyEmail($token): JsonResponse
    {
        $user = User::where('mail_token', $token)->first();

        if (!$user) {   
            return $this->sendError('Invalid token', [], 404);
        }

        $user->email_verified_at = now();
        $user->mail_token = null;
        $user->save();

        return $this->sendResponse(['name' => $user->name], 'Email verified successfully.');
    }
}
