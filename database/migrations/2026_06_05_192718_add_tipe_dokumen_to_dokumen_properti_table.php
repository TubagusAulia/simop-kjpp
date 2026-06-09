<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokumen_properti', function (Blueprint $table) {
            $table->string('tipe_dokumen')->after('uploaded_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('dokumen_properti', function (Blueprint $table) {
            $table->dropColumn('tipe_dokumen');
        });
    }
};
