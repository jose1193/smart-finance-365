<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProcessOperation;
use App\Models\ProcessOperationSubcategories;
use App\Models\ProcessBudgetExpense;
use App\Models\ProcessBudgetIncome;

use App\Models\OperationSubcategories;
use App\Models\Operation;
use App\Models\BudgetExpense;
use App\Models\BudgetIncome;
use App\Models\Budget;
use App\Models\Subcategory;
use Carbon\Carbon;

class ProcessOperationToOperation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    
    protected $signature = 'process:transfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer ProcessOperation records to Operation if date matches';


    /**
     * Execute the console command.
     */
    public function handle()
    {
       $currentDay = Carbon::now()->format('d');

    // Buscar registros en ProcessOperation con el día actual
    $processOperations = ProcessOperation::where('process_operation_date', $currentDay)->get();

        foreach ($processOperations as $processOperation) {
            // Crear registro en Operation
          
                Operation::create([
                'operation_description' => $processOperation->operation_description,
                'operation_currency_type' => $processOperation->operation_currency_type,
                'operation_amount' => $processOperation->operation_amount,
                'operation_currency' => $processOperation->operation_currency,
                'operation_currency_total' => $processOperation->operation_currency_total,
                'operation_status' => $processOperation->operation_status,
                'operation_date' => $processOperation->operation_date,
                'operation_month' => Carbon::parse($processOperation->operation_date)->format('m'), // Extraer el mes de la fecha de operación
                'operation_year' => Carbon::parse($processOperation->operation_date)->format('Y'), // Extraer el año de la fecha de operación
                'category_id' => $processOperation->category_id,
                'user_id' => $processOperation->user_id,
                // Agregar otros campos si es necesario
            ]);


            // Opcional: Puedes eliminar el registro de ProcessOperation si lo deseas
            // $processOperation->delete();
        }

    

    }
}
