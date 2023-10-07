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
            // Update data pada tabel pesanans
            $dataPesanan = $request->only([
                'nama_toko',
                'alamat_toko',
                'tanggal',
                'return',
                'terjual',
                'sample',
            ]);

            // Hitung ulang total harga berdasarkan produk yang dipilih
            $totalHarga = 0;
            foreach ($request->dataProduk as $produkId => $amount) {
                $produk = Produk::find($produkId);
                if ($produk) {
                    $totalHarga += $produk->harga * $amount;
                }
            }

            $dataPesanan['total_harga'] = $totalHarga;

            // Update data pada tabel pesanans
            $pesanan->update($dataPesanan);

            // Data produk baru yang akan disinkronkan
            $dataToSync = collect($request->dataProduk)
                ->map(fn($value) => ['amount' => $value]);

            // Update data pada tabel pesanan_produk
            $pesanan->produks()->sync($dataToSync);

            // Muat data terbaru
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
            // Hapus data dari tabel pesanan_produk yang terkait dengan pesanan
            $pesanan->produks()->detach();

            // Hapus data pesanan
            $pesanan->delete();

            return response()->json(['message' => 'Pesanan berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    function store(Request $request, User $user)
    {
        try {
            // Menyiapkan data pesanan
            $dataPesanan = $request->only([
                'nama_toko',
                'alamat_toko',
                'tanggal',
                'status',
                'return',
                'terjual',
                'sample',
            ]);

            // Hitung total harga berdasarkan produk yang dipilih
            $totalHarga = 0;
            foreach ($request->dataProduk as $produkId => $amount) {
                $produk = Produk::find($produkId);
                if ($produk) {
                    $totalHarga += $produk->harga * $amount;
                }
            }

            $dataPesanan['total_harga'] = $totalHarga;

            // Buat pesanan
            $pesanan = $user->pesanans()->create($dataPesanan);

            // Buat data pesanan_produk
            $dataToSync = collect($request->dataProduk)
                ->map(fn($value) => ['amount' => $value]);

            $pesanan->produks()->sync($dataToSync);

            // Muat data terbaru
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