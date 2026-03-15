@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="max-w-lg mx-auto bg-white p-8 rounded-xl shadow-md">
        <h2 class="text-xl font-bold mb-6 text-gray-800">Konfirmasi Kirim WhatsApp</h2>

        <form action="{{ route('whatsapp.send') }}" method="POST">
            @csrf
            <input type="hidden" name="struk_id" value="{{ $struk->id }}">

            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Nomor WhatsApp Pelanggan:</label>
                <input type="text" name="phone" value="{{ $struk->no_telepon }}" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-green-500 outline-none">
                <small class="text-gray-400">Gunakan format 628xxx</small>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Pesan Tambahan:</label>
                <textarea name="message" rows="3" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-green-500 outline-none">Halo {{ $struk->nama_pelanggan }}, ini adalah struk belanja Anda. Terima kasih!</textarea>
            </div>

            <div class="flex gap-3">
                <a href="{{ url()->previous() }}" class="flex-1 text-center bg-gray-100 text-gray-600 py-3 rounded-lg font-bold">Batal</a>
                <button type="submit" class="flex-1 bg-green-600 text-white py-3 rounded-lg font-bold hover:bg-green-700 transition">
                    Kirim Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection