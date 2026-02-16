<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Struk extends Model
{
    protected $table = 'struks';

    protected $fillable = [
        'nama_pelanggan',
        'no_telepon',
        'alamat',
        'total_harga',
        'jumlah_bayar',
        'sisa_tagihan',
        'status_pembayaran',
    ];

    public function items()
    {
        return $this->hasMany(StrukItem::class);
    }

    public function getSisaTagihanAttribute()
    {
        $total = $this->total_harga;
        $dibayar = $this->jumlah_bayar ?? 0;

        $sisa = $total - $dibayar;

        return $sisa > 0 ? $sisa : 0;
    }

}
