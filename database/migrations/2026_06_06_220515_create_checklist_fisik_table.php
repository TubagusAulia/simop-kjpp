<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklist_fisik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('properti_id')->constrained('properti')->onDelete('cascade');
            $table->string('nama_item');
            $table->enum('tipe', ['wajib', 'opsional'])->default('wajib');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_fisik');
    }
};
