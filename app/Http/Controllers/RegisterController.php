<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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
        $user = User::create($input);

        $success['name'] = $user->name;

        return $this->sendResponse($success, 'You are added Successfully.');
    }
    public function login(Request $request): JsonResponse
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();
            $success['token'] = $user->createToken('Myapp')->plainTextToken;
            $success['name'] = $user->name;


            return $this->sendResponse($success, 'You are logged in Successfully.');
        } else {

            return $this->sendResponse('Unauthorized', ['error' => 'Unauthorized']);
        }
    }
}
