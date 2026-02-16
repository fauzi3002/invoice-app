<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('struk_items', function (Blueprint $table) {
            $table->id();

            // Relasi ke struk
            $table->foreignId('struk_id')
                  ->constrained('struks')
                  ->onDelete('cascade');

            // Relasi ke produk
            $table->foreignId('produk_id')
                  ->constrained('produk')
                  ->onDelete('cascade');

            $table->bigInteger('harga');
            $table->integer('qty');
            $table->bigInteger('subtotal');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('struk_items');
    }
};
