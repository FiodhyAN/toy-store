<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mainan extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'harga', 'foto', 'id_kategori'];

    public function kategori()
    {
        return $this->belongsTo(KategoriMainan::class, 'id_kategori');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_mainan');
    }
}
