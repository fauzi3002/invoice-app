<?php

namespace App\Exports;

use App\Models\Struk;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StrukExport implements FromView
{
    protected $struk;
    protected $toko;

    public function __construct($struk, $toko)
    {
        $this->struk = $struk;
        $this->toko = $toko;
    }

    public function view(): View
    {
        return view('exports.invoice_excel', [
            'struk' => $this->struk,
            'toko'  => $this->toko,
        ]);
    }
}
