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

    public function storeUserByWeb(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:A,S',

        ]);

        $data = [
            'name' => $request->name,
            'no_tlp' => $request->no_tlp,
            'tipe' => $request->tipe,
            'password' => bcrypt($request->password),
        ];

        try {
            $user = User::create($data);
            return redirect()->route('user.create')->with('success', 'Pengguna berhasil ditambahkan');
        } catch (\Throwable $th) {
            return back()->with('error', 'Gagal menambahkan pengguna: ' . $th->getMessage());
        }
    }
    public function create()
    {
        return view('layout.layout');
    }
    public function login(Request $request)
    {

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
        $data = User::where('tipe', 'S')->get()->toArray();

        return response()->json($data);

    }

    public function getRecap(Request $request)
    {
        $month = $request->input('month'); // Jika bulan tidak ada, maka default ke 0

        if ($month == 0) {
            // Ambil semua data
            $recap = Pesanan::with(['user:id,name', 'produks:id,nama_produk,pesanan_produk.amount'])
                ->orderBy('tanggal', 'asc')
                ->get();
        } elseif (in_array($month, range(1, 12))) {
            // Ambil data berdasarkan bulan
            $recap = Pesanan::with(['user:id,name', 'produks:id,nama_produk,pesanan_produk.amount'])
                ->whereMonth('tanggal', $month)
                ->orderBy('tanggal', 'asc')
                ->get();
        } else {
            // Jika bulan tidak valid
            return response()->json(['error' => 'Bulan yang dimasukkan tidak valid.'], 400);
        }

        return response()->json($recap);
    }
}