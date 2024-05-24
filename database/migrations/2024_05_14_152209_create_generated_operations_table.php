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
        Schema::create('generated_operations', function (Blueprint $table) {
            $table->id();
            
            $table->string('operation_description');
            $table->string('operation_currency_type');
            $table->string('operation_amount');
            $table->string('operation_currency');
            $table->string('operation_currency_total');
            $table->string('operation_date');
            $table->foreignId('operation_status')
            ->constrained('statu_options')
            ->onUpdate('cascade')
            ->onDelete('cascade');
           
            $table->integer('budget_id')->nullable();


            $table->string('process_operation_date_job');
            
            $table->string('last_processed_at')->nullable();
            $table->foreignId('process_operation_id')
            ->constrained('process_operations')
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
        Schema::dropIfExists('generated_operations');
    }
};
