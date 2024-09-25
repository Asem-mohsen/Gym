<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Traits\ApiResponse;

class RegisterController extends Controller
{
    use ApiResponse ;

    public function register(RegisterRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'roleId' => 2, // system user 
        ]);

        $user->token = $user->createToken($request->device_name)->plainTextToken;

        $data = [
            'user' => $user,
            'token_type' => 'Bearer',
            'device_name' => $request->device_name,
            'operating_system' => $request->operating_system,
        ];

        return $this->data($data, 'User registered successfully');

    }
}
