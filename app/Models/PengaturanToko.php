<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanToko extends Model
{
    protected $table = 'pengaturan_toko';

    protected $fillable = [
        'nama_toko',
        'logo_toko',
        'tanda_tangan',
        'no_telepon',
        'email',
        'nama_bank',
        'pemilik_rekening',
        'no_rekening',
        'alamat_toko',
    ];
}
