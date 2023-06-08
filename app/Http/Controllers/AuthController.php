<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $input = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'same:confirm_password', 'min:4'],
        ]);
        User::create([ 'email' => $input['email'], 'password' => Hash::make($input['password']) ]);
        return response()->json(['message' => 'User Create Successfully'], 200);
    }

    public function login(Request $request)
    {
        $input = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if(Auth::attempt(['email' => $input['email'], 'password' => $input['password']])) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json(['token' => $token], 200);
        };
        return response()->json(['message' => 'Unauthorized'], 401);

    }
}
