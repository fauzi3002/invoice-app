<?php

namespace App\Http\Controllers;

use App\Models\Struk;
use App\Models\Produk;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
    $data = [
        'total_pendapatan' => Struk::sum('jumlah_bayar'),
        'total_piutang'    => Struk::sum('sisa_tagihan'),
        'invoice_pending'  => Struk::where('status_pembayaran', 'Pending')->count(),
        'stok_menipis'     => Produk::where('stok', '<=', 5)->count(),
        'recent_struks'    => Struk::latest()->take(5)->get(),
        'produks'          => Produk::all(), // Jika ingin list produk tetap ada
    ];
    return view('dashboard.index', $data);
    }
}
