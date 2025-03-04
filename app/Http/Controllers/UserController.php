<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        try {
            $users = User::all();
            return $this->jsonResponse('success', 'Berhasil mengambil data user', $users);
        } catch (\Exception $e) {
            return $this->jsonResponse('error', 'Terjadi kesalahan pada server', $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:8',
            ], [
                'name.required' => 'Nama wajib diisi!',
                'email.required' => 'Email wajib diisi!',
                'email.unique' => 'Email sudah digunakan!',
                'password.min' => 'Password minimal 8 karakter'
            ]);

            if($validator->fails()){
                $firstErrorMessage = $validator->errors()->first();
                return $this->jsonResponse('error', $firstErrorMessage, null, 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return $this->jsonResponse('success', 'User berhasil dibuat', $user, 201);
        } catch (\Exception $e) {
            return $this->jsonResponse('error', 'Terjadi kesalahan pada server', $e->getMessage(), 500);
        }
    }

    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return $this->jsonResponse('success', 'User berhasil ditemukan', $user);
        } catch (ModelNotFoundException $e) {
            return $this->jsonResponse('error', 'User tidak ditemukan', null, 404);
        } catch (\Exception $e) {
            return $this->jsonResponse('error', 'Terjadi kesalahan pada server', $e->getMessage(), 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string',
                'email' => 'sometimes|required|string|email|unique:users,email,'.$id,
                'password' => 'sometimes|required|string|min:8',
            ], [
                'name.required' => 'Nama wajib diisi!',
                'email.required' => 'Email wajib diisi!',
                'email.unique' => 'Email sudah digunakan!',
                'password.min' => 'Password minimal 8 karakter'
            ]);

            if($validator->fails()){
                $firstErrorMessage = $validator->errors()->first();
                return $this->jsonResponse('error', $firstErrorMessage, null, 422);
            }

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return $this->jsonResponse('success', 'User berhasil diperbarui', $user, 200);
        } catch (\Exception $e) {
            return $this->jsonResponse('error', 'Terjadi kesalahan pada server', $e->getMessage(), 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return $this->jsonResponse('success', 'User berhasil dihapus', null, 200);
        } catch (ModelNotFoundException $e) {
            return $this->jsonResponse('error', 'User tidak ditemukan', null, 404);
        } catch (\Exception $e) {
            return $this->jsonResponse('error', 'Terjadi kesalahan pada server', $e->getMessage(), 500);
        }
    }
}
