<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function me(Request $request)
    {
        return Response::json([
            "message" => "Get user data success",
            "data" => $request->user()
        ])->setStatusCode(200);
    }

    public function signin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "email|required",
            "password" => "required"
        ]);

        if (Auth::guard("staff")->attempt($request->only("email", "password"))) {
            $staff = Auth::guard("staff")->user();

            $token = $staff->createToken("muarra-mobile")->plainTextToken;

            return Response::json([
                "message" => "login success",
                "data" => [
                    "token" => $token
                ]
            ])->setStatusCode(200);
        }

        return Response::json([
            "message" => "Login Failed",
            "data" => NULL
        ])->setStatusCode(401);
    }

    public function signout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return Response::json([
            'message' => 'Logout Success'
        ], 200);
    }
}
