<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Produk extends Model
{
    use HasFactory;
    public $timestamps = false;
    function pesanans(): BelongsToMany
    {
        return $this->belongsToMany(Pesanan::class);
    }
}