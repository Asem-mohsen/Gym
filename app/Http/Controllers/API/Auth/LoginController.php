<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    use ApiResponse ;

    public function login(LoginRequest $request)
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::guard('sanctum')->user();

            $accessToken = $user->createToken($request->device_name)->plainTextToken;

            $data = [
                'user' => $user ,
                'token'=> $accessToken
            ];

            return $this->data($data , 'user logged in successfully', 200);
        }

        return $this->error(['message' => 'Authentication failed'], 'Please make sure that your data is valid' , 401);
    }
}
