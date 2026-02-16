<table>
    <tr>
        <td colspan="3" style="font-size:20px; font-weight:bold; text-align:center;">
            INVOICE
        </td>
    </tr>

    <tr>
        <td colspan="3"></td>
    </tr>

    <tr>
        <td><strong>{{ $toko->nama_toko }}</strong></td>
        <td></td>
        <td style="text-align:right;">
            <strong>#INV-{{ str_pad($struk->id, 5, '0', STR_PAD_LEFT) }}</strong>
        </td>
    </tr>

    <tr>
        <td>{{ $toko->alamat_toko }}</td>
        <td></td>
        <td style="text-align:right;">
            {{ $struk->created_at->format('d/m/Y') }}
        </td>
    </tr>

    <tr>
        <td colspan="3"></td>
    </tr>

    <tr>
        <td><strong>Tagihan Untuk:</strong></td>
    </tr>

    <tr>
        <td>{{ $struk->nama_pelanggan }}</td>
    </tr>

    <tr>
        <td>{{ $struk->no_telepon }}</td>
    </tr>

    <tr><td colspan="3"></td></tr>

    <tr style="background-color:#1e3a8a; color:white;">
        <th>Item</th>
        <th>Qty</th>
        <th>Subtotal</th>
    </tr>

    @foreach($struk->items as $item)
    <tr>
        <td>{{ $item->produk->nama_produk }}</td>
        <td>{{ $item->qty }}</td>
        <td>{{ $item->subtotal }}</td>
    </tr>
    @endforeach

    <tr><td colspan="3"></td></tr>

    <tr>
        <td></td>
        <td><strong>Total</strong></td>
        <td><strong>{{ $struk->total_harga }}</strong></td>
    </tr>

    <tr>
        <td></td>
        <td><strong>Sisa</strong></td>
        <td><strong>{{ $struk->sisa_tagihan }}</strong></td>
    </tr>
</table>
