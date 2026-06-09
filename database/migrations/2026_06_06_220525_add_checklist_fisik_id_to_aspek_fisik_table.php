<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aspek_fisik', function (Blueprint $table) {
            $table->foreignId('checklist_fisik_id')->nullable()->after('properti_id')
                ->constrained('checklist_fisik')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('aspek_fisik', function (Blueprint $table) {
            $table->dropForeign(['checklist_fisik_id']);
            $table->dropColumn('checklist_fisik_id');
        });
    }
};
