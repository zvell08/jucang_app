<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pesananproduk;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    function many(User $user)
    {


        try {
            $groupedPesanan = $user->pesanans()
                ->with(['produks:id,nama_produk,pesanan_produk.amount'])
                ->get()
                ->groupBy('status');

            return response()->json($groupedPesanan);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function update(Request $request, Pesanan $pesanan)
    {
        try {

            $dataPesanan = $request->only([
                'nama_toko',
                'alamat_toko',
                'tanggal',
                'return',
                'terjual',
                'sample',
            ]);


            $totalHarga = 0;
            foreach ($request->dataProduk as $produkId => $amount) {
                $produk = Produk::find($produkId);
                if ($produk) {
                    $totalHarga += $produk->harga * $amount;
                }
            }

            $dataPesanan['total_harga'] = $totalHarga;


            $pesanan->update($dataPesanan);

            $dataToSync = collect($request->dataProduk)
                ->map(fn($value) => ['amount' => $value]);

            $pesanan->produks()->sync($dataToSync);

            $data = $pesanan->load([
                'produks' => function ($query) {
                    $query->select('produks.id', 'produks.nama_produk', 'pesanan_produk.amount');
                }
            ]);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function delete(Pesanan $pesanan)
    {
        try {
            $pesanan->produks()->detach();

            $pesanan->delete();

            return response()->json(['message' => 'Pesanan berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    function store(Request $request, User $user)
    {
        try {
            $dataPesanan = $request->only([
                'nama_toko',
                'alamat_toko',
                'tanggal',
                'status',
                'return',
                'terjual',
                'sample',
            ]);

            $totalHarga = 0;
            foreach ($request->dataProduk as $produkId => $amount) {
                $produk = Produk::find($produkId);
                if ($produk) {
                    $totalHarga += $produk->harga * $amount;
                }
            }

            $dataPesanan['total_harga'] = $totalHarga;

            $pesanan = $user->pesanans()->create($dataPesanan);

            $dataToSync = collect($request->dataProduk)
                ->map(fn($value) => ['amount' => $value]);

            $pesanan->produks()->sync($dataToSync);

            $data = $pesanan->load([
                'produks' => function ($query) {
                    $query->select('produks.id', 'produks.nama_produk', 'pesanan_produk.amount');
                }
            ]);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}