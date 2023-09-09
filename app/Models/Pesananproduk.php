<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesananproduk extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['pesanan_id'];
}