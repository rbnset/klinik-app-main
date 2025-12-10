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
        Schema::create('detail_tindakans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekam_medis_detail_id')
                ->constrained('rekam_medis_details')->cascadeOnDelete();
            $table->foreignId('tindakan_id')->constrained('tindakans');
            $table->decimal('qty', 10, 2)->default(1);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_tindakans');
    }
};
