<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriMainan extends Model
{
    use HasFactory;

    protected $table = 'kategori_mainans';
    protected $fillable = ['nama', 'icon'];

    public function mainans()
    {
        return $this->hasMany(Mainan::class, 'id_kategori');
    }
}
