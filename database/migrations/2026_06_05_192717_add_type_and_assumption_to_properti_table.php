<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properti', function (Blueprint $table) {
            $table->string('tipe_properti')->default('tanah_kosong')->after('proyek_id');
            $table->boolean('asumsi_khusus')->default(false)->after('kategori');
        });
    }

    public function down(): void
    {
        Schema::table('properti', function (Blueprint $table) {
            $table->dropColumn(['tipe_properti', 'asumsi_khusus']);
        });
    }
};
