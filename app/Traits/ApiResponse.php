<?php

namespace App\Traits;

trait ApiResponse {

    public function success(string $message , int $statusCode = 200){
        return response()->json([
            'success' => true,
            'message'=>$message,
            'data' =>(object)[],
            'error'=>(object)[]
        ],$statusCode);

    }

    public function error(array $errors , string $message , int $statusCode = 404){
        return response()->json([
            'success' => false,
            'message'=>$message,
            'data' =>(object)[],
            'error'=>(object)$errors
        ],$statusCode);

    }

    public function data(array $data , string $message , int $statusCode = 200){
        return response()->json([
            'success' => true,
            'message'=>$message,
            'data' =>(object)$data,
            'error'=>(object)[]
        ],$statusCode);

    }
}
