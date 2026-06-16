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
        Schema::table('proyek', function (Blueprint $table) {
            $table->boolean('finish_requested')->default(false)->after('current_phase');
            $table->foreignId('finish_requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('finish_requested_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyek', function (Blueprint $table) {
            $table->dropForeign(['finish_requested_by']);
            $table->dropColumn(['finish_requested', 'finish_requested_by', 'finish_requested_at']);
        });
    }
};
