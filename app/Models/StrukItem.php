<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrukItem extends Model
{
    protected $table = 'struk_items';

    protected $fillable = [
        'struk_id',
        'produk_id',
        'harga',
        'qty',
        'subtotal',
    ];

    public function struk()
    {
        return $this->belongsTo(Struk::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

}
