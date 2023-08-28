<?php

use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanan_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Pesanan::class)->constrained();
            $table->foreignIdFor(Produk::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_produk');
    }
};