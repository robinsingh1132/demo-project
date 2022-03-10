<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('demoToken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 200);
        //1|ELzGhuCd6Xn0T0bQM9GqP0Vz3qaiu3CZhxOurZZj
    }

    public function logout()
    {
        // Revoke all tokens...
        auth()->user()->tokens()->delete();
        return response(['message' => 'logged out successfully!']);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|max:100',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response(['message' => 'Invalid Credentials'], 401);
        } else {
            $token = $user->createToken('demoLoginToken')->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token,
            ];
            return response($response, 200);
        }
    }
    public function forgot() {
        $credentials = request()->validate(['email' => 'required|email']);
        Password::sendResetLink($credentials);
        return response()->json(["message" => 'Reset password link sent on your email id.']);
    }

    public function reset() {
        $credentials = request()->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        $reset_password_status = Password::reset($credentials, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        if ($reset_password_status == Password::INVALID_TOKEN) {
            return response()->json(["message" => "Invalid token provided"], 400);
        }

        return response()->json(["message" => "Password has been successfully changed"]);
    }
 
}
