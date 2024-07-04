<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = ['id_user', 'id_mainan', 'jumlah', 'total_harga', 'foto'];

    public function mainan()
    {
        return $this->belongsTo(Mainan::class, 'id_mainan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}