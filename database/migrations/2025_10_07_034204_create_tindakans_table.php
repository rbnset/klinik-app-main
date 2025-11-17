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
        Schema::create('tindakans', function (Blueprint $table) {
            $table->id(); 
            $table->string('nama_tindakan', 50);
            $table->text('deskripsi')->nullable();
            $table->decimal('tarif', 12, 2)->default(0);
            $table->enum('role', ['dokter', 'bidan'])->default('dokter'); // kolom role
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindakans');
    }
};
