<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $struk->id }}</title>
    <style>
        /* CSS murni untuk DomPDF */
        body { font-family: sans-serif; font-size: 12px; color: #333; margin: 0; padding: 0; }
        .container { padding: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th { background: #f3f4f6; padding: 10px; text-align: left; border-bottom: 2px solid #e5e7eb; }
        .table td { padding: 10px; border-bottom: 1px solid #f3f4f6; }
        
        .header { width: 100%; margin-bottom: 30px; }
        .shop-name { font-size: 20px; font-weight: bold; color: #1e3a8a; }
        .invoice-title { font-size: 18px; font-weight: bold; text-align: right; text-transform: uppercase; color: #9ca3af; }
        
        .info-section { width: 100%; margin-bottom: 20px; }
        .info-box { vertical-align: top; width: 50%; }
        
        .total-section { margin-top: 30px; width: 100%; }
        .total-box { float: right; width: 40%; }
        .total-row td { padding: 5px 0; }
        .grand-total { font-size: 16px; font-weight: bold; color: #1e3a8a; border-top: 2px solid #1e3a8a; }
        
        .footer { margin-top: 50px; text-align: center; color: #9ca3af; font-size: 10px; }
        .clearfix { clear: both; }
    </style>
</head>
<body>
    <div class="container">
        <table class="header">
            <tr>
                <td>
                    <div class="shop-name">{{ $toko->nama_toko ?? 'TOKO KASIR' }}</div>
                    <div style="color: #6b7280;">{{ $toko->alamat_toko ?? 'Alamat Belum Diatur' }}</div>
                </td>
                <td class="invoice-title">Invoice</td>
            </tr>
        </table>

        <table class="info-section">
            <tr>
                <td class="info-box">
                    <div style="font-weight: bold; text-transform: uppercase; font-size: 10px; color: #9ca3af;">Ditujukan Untuk:</div>
                    <div style="font-size: 14px; font-weight: bold; margin-top: 5px;">{{ $struk->nama_pelanggan }}</div>
                    <div>{{ $struk->no_telepon }}</div>
                </td>
                <td class="info-box" style="text-align: right;">
                    <div style="font-weight: bold; text-transform: uppercase; font-size: 10px; color: #9ca3af;">Nomor Struk:</div>
                    <div style="font-size: 14px; font-weight: bold; margin-top: 5px; color: #1e3a8a;">#INV-{{ str_pad($struk->id, 5, '0', STR_PAD_LEFT) }}</div>
                    <div>Tanggal: {{ $struk->created_at->format('d/m/Y') }}</div>
                </td>
            </tr>
        </table>

        <table class="table">
            <thead>
                <tr>
                    <th>Deskripsi Produk</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Harga</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($struk->items as $item)
                <tr>
                    <td>{{ $item->produk->nama_produk }}</td>
                    <td style="text-align: center;">{{ $item->qty }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->produk->harga_satuan, 0, ',', '.') }}</td>
                    <td style="text-align: right; font-weight: bold;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-box">
                <table width="100%">
                    <tr class="total-row">
                        <td style="color: #6b7280;">Total Harga</td>
                        <td style="text-align: right; font-weight: bold;">Rp {{ number_format($struk->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="total-row">
                        <td style="color: #16a34a;">Bayar</td>
                        <td style="text-align: right; color: #16a34a; font-weight: bold;">Rp {{ number_format($struk->jumlah_bayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="total-row grand-total">
                        <td style="padding-top: 10px;">Sisa Tagihan</td>
                        <td style="text-align: right; padding-top: 10px;">Rp {{ number_format($struk->sisa_tagihan, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="footer">
            <p>Terima kasih telah berbelanja di {{ $toko->nama_toko ?? 'toko kami' }}!</p>
            <p>Ini adalah dokumen sah yang dihasilkan secara otomatis.</p>
        </div>
    </div>
</body>
</html>