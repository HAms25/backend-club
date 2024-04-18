<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register( Request $request ){
        $data = $request->validate([
            "user" => "required|unique:users",
            "name" => "required",
            "password" => "required|confirmed",
        ]);

        $userData = $data;
        $user = User::create($userData);
        $token = $user->createToken("my-token")->plainTextToken;

        return response()->json([
            "token" => $token,
            "Type" => "Bearer"
        ]);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            "user" => "required",
            "password" => "required",
        ]);

        $user = User::where("user", $fields["user"])->first();

        if (!$user || !Hash::check($fields["password"], $user->password)) {
            return response([
                "message" => "Credenciales Incorrectas"
            ]);
        }

        $token = $user->createToken('my-token')->plainTextToken;

        return response()->json([
            "token" => $token,
            "Type" => "Bearer",
            "role_id" => $user->role_id,
        ]);
    }
}
