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
use Illuminate\Support\Facades\DB;

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
    DB::transaction(function () {
        $currentDay = Carbon::now()->format('d');
        $processOperations = ProcessOperation::with('processBudgetIncome', 'processBudgetExpense', 'operationProcessSubcategories')
                                             ->where('process_operation_date', $currentDay)
                                              ->where(function ($query) {
                                                 // Agregar condición para seleccionar registros no procesados hoy o cuya fecha de último procesamiento no coincide con la fecha actual
                                                 $query->whereNull('last_processed_at')
                                                       ->orWhereDate('last_processed_at', '!=', Carbon::today());
                                             })
                                             ->get();

        foreach ($processOperations as $processOperation) {
            $parsedDate = Carbon::parse($processOperation->operation_date);
            
            // Crea una nueva fecha basada en el año y mes actual con el día de la fecha original
            $newOperationDate = Carbon::now()->setDay($parsedDate->day);

            // Ajusta el día al máximo posible del mes si el día original no existe en el nuevo mes
            if (!$newOperationDate->isSameMonth(Carbon::now())) {
                $newOperationDate->setDay($newOperationDate->daysInMonth);
            }

            $operation = Operation::create([
                'operation_description' => $processOperation->operation_description,
                'operation_currency_type' => $processOperation->operation_currency_type,
                'operation_amount' => $processOperation->operation_amount,
                'operation_currency' => $processOperation->operation_currency,
                'operation_currency_total' => $processOperation->operation_currency_total,
                'operation_status' => $processOperation->operation_status,
                'operation_date' => $newOperationDate->format('Y-m-d'), // Usa la nueva fecha ajustada
                'operation_month' => $newOperationDate->format('m'),
                'operation_year' => $newOperationDate->format('Y'),
                'category_id' => $processOperation->category_id,
                'user_id' => $processOperation->user_id,
                
            ]);

            $this->createRelatedRecords($processOperation, $operation);

             // Actualiza last_processed_at después de procesar los registros relacionados
            $processOperation->update(['last_processed_at' => now()]);
        }
    });
}


protected function createRelatedRecords($processOperation, $operation)
{
    if ($processOperation->processBudgetIncome->isNotEmpty()) {
        foreach ($processOperation->processBudgetIncome as $budgetIncome) {
            BudgetIncome::create([
                'operation_id' => $operation->id,
                'budget_id' => $budgetIncome->budget_id,
                'category_id' => $operation->category_id,
            ]);
        }
    }

    if ($processOperation->processBudgetExpense->isNotEmpty()) {
        foreach ($processOperation->processBudgetExpense as $budgetExpense) {
            BudgetExpense::create([
                'operation_id' => $operation->id,
                'budget_id' => $budgetExpense->budget_id,
                'category_id' => $operation->category_id,
            ]);
        }
    }

    if ($processOperation->operationProcessSubcategories->isNotEmpty()) {
        foreach ($processOperation->operationProcessSubcategories as $processSubcategory) {
            OperationSubcategories::create([
                'operation_id' => $operation->id,
                'subcategory_id' => $processSubcategory->subcategory_id,
                'user_id_subcategory' => $operation->user_id,
            ]);
        }
    }
}

}
