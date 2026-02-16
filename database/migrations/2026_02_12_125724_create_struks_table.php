<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('struks', function (Blueprint $table) {
            $table->id();

            // Data pelanggan
            $table->string('nama_pelanggan');
            $table->string('no_telepon')->nullable();
            $table->text('alamat')->nullable();

            // Ringkasan pembayaran
            $table->bigInteger('total_harga');
            $table->bigInteger('jumlah_bayar')->default(0);
            $table->bigInteger('sisa_tagihan')->default(0);

            $table->enum('status_pembayaran', [
                'pending',
                'partial',
                'lunas'
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('struks');
    }
};
