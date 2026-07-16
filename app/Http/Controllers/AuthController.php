<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email',
            'password' => 'required|string|min:6',
            'kode_admin' => 'nullable|string', 
        ]);

        
        $peran = 'user';

        if (!empty($data['kode_admin'])) {
            $kodeRahasia = config('services.admin_registration.code');

            if ($kodeRahasia && $data['kode_admin'] === $kodeRahasia) {
                $peran = 'admin';
            } else {
                
                throw ValidationException::withMessages([
                    'kode_admin' => ['Kode admin yang kamu masukkan salah.'],
                ]);
            }
        }

        $pengguna = Pengguna::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'peran' => $peran,
        ]);

        $token = $pengguna->createToken('token_akses')->plainTextToken;

        return response()->json([
            'pengguna' => $pengguna,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $pengguna = Pengguna::where('email', $data['email'])->first();

        if (!$pengguna || !Hash::check($data['password'], $pengguna->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        $token = $pengguna->createToken('token_akses')->plainTextToken;

        return response()->json([
            'pengguna' => $pengguna,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Berhasil logout']);
    }

    public function profil(Request $request)
    {
        return response()->json($request->user());
    }
}