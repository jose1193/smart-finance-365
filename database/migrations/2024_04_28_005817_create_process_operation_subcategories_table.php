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
        Schema::create('process_operation_subcategories', function (Blueprint $table) {
           $table->id();
            $table->foreignId('process_operation_id')
            ->constrained('process_operations')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('subcategory_id')
            ->constrained('subcategories')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('user_id_subcategory')
            ->constrained('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_operation_subcategories');
    }
};
