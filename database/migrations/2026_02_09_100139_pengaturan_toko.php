<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengaturan_toko', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko');
            $table->string('logo_toko')->nullable();
            $table->string('no_telepon');
            $table->string('nama_bank');
            $table->string('no_rekening');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
