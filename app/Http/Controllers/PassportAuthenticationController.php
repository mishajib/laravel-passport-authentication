<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class PassportAuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('authToken')->accessToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully!',
            'user' => $user,
            'token' => $token
        ], Response::HTTP_CREATED);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'bail|email|required',
            'password' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!auth()->attempt($validator->validated())) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials!'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = auth()->user()->createToken('authToken')->accessToken;

        return response()->json([
            'success' => true,
            'message' => 'User logged in successfully.',
            'user' => auth()->user(),
            'token' => $token
        ], Response::HTTP_OK);
    }

    public function refreshToken(Request $request)
    {
        $request->user()->token()->refresh();
        $token = $request->user()->token();
        dd($token);

        return response()->json([
            'success' => true,
            'message' => 'Token refreshed successfully.',
            'token' => $token
        ], Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'success' => true,
            'message' => 'User logged out successfully.'
        ], Response::HTTP_OK);
    }
}
