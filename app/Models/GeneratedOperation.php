<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneratedOperation extends Model
{
    use HasFactory;

     protected $fillable = [
        
        'operation_description',
        'operation_currency_type',
        'operation_amount',
        'operation_currency',
        'operation_currency_total',

        'process_operation_id',
        'process_operation_date_job',
        'operation_date',
        'operation_status',
        'budget_id',
        'last_processed_at',

        
    ];


    public function ProcessOperation()
    {
        return $this->belongsTo(ProcessOperation::class, 'process_operation_id');
    }

    public function statuOption()
    {
        return $this->belongsTo(StatuOptions::class, 'operation_status');
    }
    
    
}
