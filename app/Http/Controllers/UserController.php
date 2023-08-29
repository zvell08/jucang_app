<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function storeUser(Request $request)
    {
        $data = [
            'name' => $request->name,
            'no_tlp' => $request->no_tlp,
            'tipe' => $request->tipe,
            'password' => bcrypt($request->password),
            'user_id' => auth()->id()
        ];

        try {
            $user = User::create($data);
            return response()->json($user);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal Tambah User',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    public function login(Request $request)
    {
        $credentials = $request->only('name', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->plainTextToken;

            return response()->json(['token' => $token, 'user' => $user]);
        }

        return response()->json(['message' => 'Invalid login credentials'], 401);
    }
}