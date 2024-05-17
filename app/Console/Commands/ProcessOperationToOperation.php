<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\ProcessOperation;
use App\Models\ProcessOperationSubcategories;
use App\Models\ProcessBudgetExpense;
use App\Models\ProcessBudgetIncome;
use App\Models\GeneratedOperation;


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
        try {
            $currentDate = Carbon::today()->toDateString(); // Obtén la fecha actual en formato Y-m-d

            $processOperations = ProcessOperation::with([
                'generatedOperations' => function ($query) use ($currentDate) {
                    $query->whereDate('process_operation_date_job', $currentDate);
                },
                'processBudgetIncomes',
                'processBudgetExpenses',
                'operationProcessSubcategories'
            ])
            ->whereHas('generatedOperations', function ($query) use ($currentDate) {
                $query->whereNull('last_processed_at')
                      ->orWhereDate('last_processed_at', '!=', $currentDate);
            })
            ->get();

            foreach ($processOperations as $processOperation) {
                foreach ($processOperation->generatedOperations as $generatedOperation) {
                    // Procesar solo las operaciones generadas que no han sido procesadas hoy
                    if (is_null($generatedOperation->last_processed_at) || Carbon::parse($generatedOperation->last_processed_at)->toDateString() != $currentDate) {
                        $parsedDate = Carbon::parse($generatedOperation->operation_date);

                        // Verifica si ya existe una operación con los mismos detalles antes de crear una nueva
                        $existingOperation = Operation::where('operation_description', $generatedOperation->operation_description)
                            ->exists();

                        if (!$existingOperation) {
                            $operation = Operation::create([
                                'operation_description' => $generatedOperation->operation_description,
                                'operation_currency_type' => $generatedOperation->operation_currency_type,
                                'operation_amount' => $generatedOperation->operation_amount,
                                'operation_currency' => $generatedOperation->operation_currency,
                                'operation_currency_total' => $generatedOperation->operation_currency_total,
                                'operation_status' => $generatedOperation->operation_status,
                                'operation_date' => $parsedDate, // Usa la fecha ajustada
                                'operation_month' => $parsedDate->format('m'),
                                'operation_year' => $parsedDate->format('Y'),
                                'category_id' => $processOperation->category_id,
                                'user_id' => $processOperation->user_id,
                            ]);

                            $this->createRelatedRecords($processOperation, $operation);

                            // Actualiza last_processed_at para la operación generada
                            $generatedOperation->update(['last_processed_at' => now()]);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Manejo de excepción
            DB::rollBack();
            throw $e; // Re-lanza la excepción después de hacer rollback
        }
    });
}



protected function createRelatedRecords($processOperation, $operation)
{
    if ($processOperation->processBudgetIncomes->isNotEmpty()) {
        foreach ($processOperation->processBudgetIncomes as $budgetIncome) {
            BudgetIncome::create([
                'operation_id' => $operation->id,
                'budget_id' => $budgetIncome->budget_id,
                'category_id' => $operation->category_id,
            ]);
        }
    }

    if ($processOperation->processBudgetExpenses->isNotEmpty()) {
        foreach ($processOperation->processBudgetExpenses as $budgetExpense) {
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
