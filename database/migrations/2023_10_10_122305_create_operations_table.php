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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
             $table->string('operation_description');
             $table->string('operation_amount');
             $table->string('operation_currency');
             $table->string('operation_currency_total');
             $table->string('operation_status');
             $table->string('operation_date');
             $table->string('operation_month');
             $table->string('operation_year');
            $table->foreignId('category_id')
            ->constrained('categories')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('user_id')
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
        Schema::dropIfExists('operations');
    }
};
