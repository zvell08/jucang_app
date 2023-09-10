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
        // return response()->json($user->pesanans()->with('produks')->get()->groupBy('status'));
        // return $user->pesanans()->with('produks')->get()->groupBy('status');
        //beda
        // $pesananWithProduks = $user->pesanans()->with('produks')->get();

        // // Loop melalui setiap pesanan dan tambahkan jumlah produk ke dalam masing-masing pesanan
        // $pesananWithProduks->each(function ($pesanan) {
        //     $pesanan->produks->each(function ($produk) {
        //         // Ambil jumlah produk dari atribut pivot
        //         $produk->amount = $produk->pivot->amount;
        //     });
        // });

        // // Kelompokkan pesanan berdasarkan status
        // $groupedPesanan = $pesananWithProduks->groupBy('status');

        // return response()->json($groupedPesanan);
        //beda
        // Ambil pesanan dengan produk terkait
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

    public function update(Request $request, $pesanan)
    {
        // $data = $request->only(['return', 'terjual', 'sample', 'status']);
        // Pesanan::where('id', $pesanan)->update($data);

        // return response()->json('berhasil');

        try {
            $data = $request->only(['return', 'terjual', 'sample', 'status']);

            Pesanan::where('id', $pesanan)->update($data);
            return response()->json('berhasil');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function delete(Request $request, $pesanan)
    {
        // $data = Pesanan::find($pesanan);
        // $data->delete();
        // return response()->json("berhasil");
        try {
            $data = Pesanan::findOrFail($pesanan);
            $data->delete();

            return response()->json('berhasil');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }


    function store(Request $request, User $user)
    {
        // $dataToSync = [];

        // // Membuat struktur data yang sesuai dengan yang Anda inginkan
        // foreach ($request->produk_id as $index => $produkId) {
        //     $dataToSync[$produkId] = ['amount' => $request->amount[$index]];
        // }

        // $dataToSync = [];
        // $dataFromJson = json_decode($request->getContent(), true);
        // return json_decode($request->dataProduk, true);
        // Membuat struktur data yang sesuai dengan yang Anda inginkan

        // foreach ($dataFromJson['dataProduk'] as $produkId => $amount) {
        //     $dataToSync[$produkId] = ['amount' => $amount];
        // }
        //beda atasnya aja (foreach sama collect)

        // $dataToSync = collect($request->dataProduk)
        //     // ->filter(fun)
        //     ->map(fn($value) => ['amount' => $value]);


        // // return $dataToSync->toArray();

        // $pesanan = $user->pesanans()->create([
        //     'nama_toko' => $request->nama_toko,
        //     'alamat_toko' => $request->alamat_toko,
        //     'tanggal' => $request->tanggal,
        //     'status' => $request->status,
        //     'return' => $request->return,
        //     'terjual' => $request->terjual,
        //     'sample' => $request->sample
        // ]);

        // // Melakukan sync dengan data yang sudah disiapkan
        // $pesanan->produks()->sync($dataToSync);

        // $data = $pesanan->load([
        //     'produks' => function ($query) {
        //         $query->select('produks.id', 'produks.nama_produk', 'pesanan_produk.amount');
        //     }
        // ]);

        // return response()->json($data);
        try {
            $pesanan = $user->pesanans()->create($request->only([
                'nama_toko',
                'alamat_toko',
                'tanggal',
                'status',
                'return',
                'terjual',
                'sample'
            ]));

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
    public function all()
    {
        // Mengambil semua pesanan dari database
        $pesanan = Pesanan::with(['produks:id,nama_produk,pesanan_produk.amount'])->get()->groupBy('status'); // Pastikan model memiliki relasi dengan produk jika diperlukan

        return response()->json($pesanan);
    }


    // function store(Request $request, User $user)
    // {
    //     // Mendapatkan data JSON dari permintaan
    // $jsonData = json_decode($request->getContent(), true);

    // // Membuat pesanan dalam tabel pesanans
    // $pesanan = $user->pesanans()->create([
    //     'nama_toko' => $jsonData['nama_toko'],
    //     'alamat_toko' => $jsonData['alamat_toko'],
    //     'tanggal' => $jsonData['tanggal'],
    //     'status' => $jsonData['status'],
    //     'return' => $jsonData['return'],
    //     'terjual' => $jsonData['terjual'],
    //     'sample' => $jsonData['sample']
    // ]);

    // // Menyiapkan data pesanan_produks untuk disinkronkan
    // $dataToSync = [];

    // foreach ($jsonData['dataProduk'] as $produkId => $amount) {
    //     // Membuat data yang sesuai untuk tabel pesanan_produks
    //     $dataToSync[$produkId] = ['amount' => $amount];
    // }



    //     // Menyinkronkan data pesanan_produks dengan pesanan yang telah dibuat
    //     $pesanan->produks()->sync($dataToSync);

    //     // Mengambil data pesanan beserta relasinya dari database
    //     $data = $pesanan->load('produks');

    //     return response()->json($data);
    // }



}