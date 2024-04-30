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
        Schema::create('process_budget_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('process_operation_id')
            ->constrained('process_operations')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('budget_id')
            ->constrained('budgets')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('category_id')
            ->constrained('categories')
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
        Schema::dropIfExists('process_budget_expenses');
    }
};
