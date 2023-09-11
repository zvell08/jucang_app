<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
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
        // $credentials = $request->only('name', 'password');

        // if (Auth::attempt($credentials)) {
        //     $user = Auth::user();
        //     $token = $user->createToken('MyApp')->plainTextToken;

        //     return response()->json(['token' => $token, 'user' => $user]);
        // }

        // return response()->json(['message' => 'Invalid login credentials'], 401);

        try {
            $credentials = $request->only('name', 'password');

            if (!Auth::attempt($credentials)) {
                throw new \Exception('Invalid login credentials');
            }

            $user = Auth::user();
            $token = $user->createToken('MyApp')->plainTextToken;

            return response()->json(['token' => $token, 'user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function getUser(Request $request)
    {
        $data = User::all()->groupBy('tipe')->toArray();

        return response()->json($data);

    }

    public function all()
    {
        // Mengambil semua pesanan dari database
        $pesanan = Pesanan::with(['produks:id,nama_produk,pesanan_produk.amount'])->get()->groupBy('status'); // Pastikan model memiliki relasi dengan produk jika diperlukan

        return response()->json($pesanan);
    }
}