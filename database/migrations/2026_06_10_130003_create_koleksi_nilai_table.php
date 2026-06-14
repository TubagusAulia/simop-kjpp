<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('koleksi_nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('properti_id')->constrained('properti')->onDelete('cascade');
            $table->enum('status', ['proses', 'selesai'])->default('proses');
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('koleksi_nilai');
    }
};
