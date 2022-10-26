<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class ApiTokenController extends Controller
{

    public function index(Request $request) {
        $validator = Validator::make($request->all(), [
            'token' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $user = $request->user('sanctum');
        return $user;
        if (!$user) {
            return response()->json(['error' => 'user not authorize'], 401);
        }
        $token = PersonalAccessToken::findToken($request->token);
        if (!$token) {
            return response()->json(['error' => 'user not found (invalid token)'], 401);
        }
        return $token->tokenable;
    }

    public function createToken(LoginRequest $request) {
        $validator = Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'user not found!'], 401);
        }
        $token = $user->createToken($request->device_name);
        return ['token' => $token->plainTextToken];
    }
}
