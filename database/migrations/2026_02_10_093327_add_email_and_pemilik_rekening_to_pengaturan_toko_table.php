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
        Schema::table('pengaturan_toko', function (Blueprint $table) {
            $table->string('email')->nullable()->after('no_telepon');
            $table->string('pemilik_rekening')->nullable()->after('nama_bank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturan_toko', function (Blueprint $table) {
            $table->string('email')->nullable()->after('no_telepon');
            $table->string('pemilik_rekening')->nullable()->after('nama_bank');
        });
    }
};
