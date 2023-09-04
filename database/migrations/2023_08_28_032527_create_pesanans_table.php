<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->string('nama_toko');
            $table->string('alamat_toko');
            $table->date('tanggal');
            $table->integer('return')->nullable();
            $table->integer('terjual')->nullable();
            $table->integer('sample')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }
    // ->restrictOnDelete()->cascadeOnUpdate()

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};