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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('id')->constrained('roles');
            $table->string('phone', 30)->nullable()->after('email');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('phone');
            $table->softDeletes();
            $table->index('role_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropConstrainedForeignId('role_id');
            $table->dropColumn(['phone', 'status', 'deleted_at']);
        });
    }
};
