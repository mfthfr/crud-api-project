<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request){
        try {
            $validator = Validator::make($request->all(),[
                'email' => 'required|string|email',
                'password' => 'required|string',
            ], [
                'email.required' => 'Email wajib diisi.',
                'password.required' => 'Password wajib diisi.'
            ]);
            
            if($validator->fails()){
                $firstErrorMessage = $validator->errors()->first();
                return $this->jsonResponse('error', $firstErrorMessage, null, 422);
            }
    
            $credentials = $request->only('email', 'password');

            if(!Auth::attempt($credentials)){
                return $this->jsonResponse('error', 'Email atau password salah!', null, 401);
            }

            $user = Auth::user();
            $token = $user->createToken('api-token')->plainTextToken;

            return $this->jsonResponse('success', 'Login Berhasil', [
                'user' => $user,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse('error', 'Terjadi kesalahan pada server', $e->getMessage(), 500);
        }   
    }
}