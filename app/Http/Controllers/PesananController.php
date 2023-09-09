<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pesananproduk;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    // function store(Request $request, User $user)
    // {
    //     // return $user;
    //     // $user = User::find($request->user_id);
    //     // return $user;
    //     // return $request->produk_id;
    //     $pesanan = $user->pesanans()->create([
    //         'nama_toko' => $request->nama_toko,
    //         'alamat_toko' => $request->alamat_toko,
    //         'tanggal' => $request->tanggal,
    //         'status' => $request->status,
    //         'return' => $request->return,
    //         'terjual' => $request->terjual,
    //         'sample' => $request->sample
    //     ]);







    //     // $items = $request->input('items');
    //     // foreach ($items as $item) k_id']) && isset($item['amount'])) {
    //     //         $produk_id = $item[{
    //     //     if (isset($item['produ'produk_id'];
    //     //         $amount = $item['amount'];

    //     //         // Simpan detail pesanan produk
    //     //         PesananProduk::create([
    //     //             'pesanan_id' => $pesanan->id,
    //     //             'produk_id' => $produk_id,
    //     //             'amount' => $amount,
    //     //         ]);
    //     //     }
    //     // }
    //     // return response()->json(['message' => 'Pesanan telah berhasil dibuat'], 201);

    //     //batas
    //     // [5,1]
    //     // [4,6]
    //     // $test = [
    //     //     2 => ['amount' => 4],
    //     //     4 => ['amount' => 6],
    //     // ];
    //     $pesanan->produks()->sync($request->produk_id);

    //     $data = $pesanan->load('produks');

    //     return response()->json($data);
    // }


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


    function store(Request $request, User $user)
    {
        $dataToSync = [];

        // Membuat struktur data yang sesuai dengan yang Anda inginkan
        foreach ($request->produk_id as $index => $produkId) {
            $dataToSync[$produkId] = ['amount' => $request->amount[$index]];
        }

        $pesanan = $user->pesanans()->create([
            'nama_toko' => $request->nama_toko,
            'alamat_toko' => $request->alamat_toko,
            'tanggal' => $request->tanggal,
            'status' => $request->status,
            'return' => $request->return,
            'terjual' => $request->terjual,
            'sample' => $request->sample
        ]);

        // Melakukan sync dengan data yang sudah disiapkan
        $pesanan->produks()->sync($dataToSync);

        $data = $pesanan->load('produks');

        return response()->json($data);
    }





}