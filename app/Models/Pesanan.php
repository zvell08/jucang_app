<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pesanan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => $value == 1 ? 'Lunas' : 'Hutang',
        );
    }

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    function produks(): BelongsToMany
    {
        return $this->belongsToMany(Produk::class);
    }
}