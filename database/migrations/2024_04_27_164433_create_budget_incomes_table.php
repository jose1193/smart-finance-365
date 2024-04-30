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
        Schema::create('budget_incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_id')
            ->constrained('operations')
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
        Schema::dropIfExists('budget_incomes');
    }
};
