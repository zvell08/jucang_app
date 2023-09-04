<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pesananproduk;
use App\Models\User;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    function store(Request $request, User $user)
    {
        // return $user;
        // $user = User::find($request->user_id);
        // return $user;
        // return $request->produk_id;
        $pesanan = $user->pesanans()->create([
            'nama_toko' => $request->nama_toko,
            'alamat_toko' => $request->alamat_toko,
            'tanggal' => $request->tanggal,
            'status' => $request->status,
            'return' => $request->return,
            'terjual' => $request->terjual,
            'sample' => $request->sample,

        ]);

        $pesanan->produks()->attach($request->produk_id);

        $data = $pesanan->load('produks');

        return response()->json($data);
    }


    function many(User $user)
    {
        return response()->json($user->pesanans()->with('produks')->get()->groupBy('status'));
        // return $user->pesanans()->with('produks')->get()->groupBy('status');
    }

    public function update(Request $request, $pesanan)
    {
        $pesanans = Pesanan::find($pesanan);
        $pesanans->return = $request->input('return');
        $pesanans->terjual = $request->input('terjual');
        $pesanans->sample = $request->input('sample');
        $pesanans->status = $request->input('status');
        $pesanans->save();

        return response()->json('berhasil');
    }

    public function delete(Request $request, $pesanan)
    {
        $data = Pesanan::find($pesanan);
        $data->delete();
        return response()->json("berhasil");
    }




}