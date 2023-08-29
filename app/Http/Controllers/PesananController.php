<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    function store(Request $request)
    {
        $user = User::find($request->user_id);

        $pesanan = $user->pesanans()->create([
            'nama_toko' => $request->nama_toko,
            'alamat_toko' => $request->alamat_toko,
            'tanggal' => $request->tanggal,
            'status' => $request->status,
            'return' => $request->return,
            'terjual' => $request->terjual,
            'sample' => $request->sample,

        ]);

        $pesanan->produks()->sync($request->produk_id);

        $data = $pesanan->load('produks');

        return response()->json($data);
    }


    function many(User $user)
    {
        // return response()->json($user->pesanans()->with('produks')->get()->groupBy('status'));
        return $user->pesanans()->with('produks')->get()->groupBy('status');
    }
}