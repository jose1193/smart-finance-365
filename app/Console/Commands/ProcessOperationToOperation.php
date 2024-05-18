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
use Illuminate\Support\Facades\Http;


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
            $currentDate = Carbon::today()->toDateString();

            $processOperations = $this->getProcessOperations($currentDate);

            foreach ($processOperations as $processOperation) {
                $this->processGeneratedOperations($processOperation, $currentDate);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    });
}

private function getProcessOperations($currentDate)
{
    
    return ProcessOperation::with([
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
}

private function processGeneratedOperations($processOperation, $currentDate)
{
    foreach ($processOperation->generatedOperations as $generatedOperation) {
        if ($this->shouldProcessGeneratedOperation($generatedOperation, $currentDate)) {
            $this->processSingleOperation($processOperation, $generatedOperation);
        }
    }
}

private function shouldProcessGeneratedOperation($generatedOperation, $currentDate)
{
    
    return is_null($generatedOperation->last_processed_at) || 
           Carbon::parse($generatedOperation->last_processed_at)->toDateString() != $currentDate;
}





private function processSingleOperation($processOperation, $generatedOperation)
{
    $parsedDate = Carbon::parse($generatedOperation->operation_date)->toDateString(); // Solo la fecha

    // Consulta la API de CurrencyLayer para obtener la tasa de cambio
    $currencyType = $generatedOperation->operation_currency_type;
    $amount = $generatedOperation->operation_amount;
    $rate = 1.0; // Default rate if currency is USD

    if ($currencyType != 'USD') {
        try {
            $response = Http::get('http://api.currencylayer.com/live', [
                'access_key' => 'd3314ac151faa4aaed99cefe494d4fc2',
                'currencies' => $currencyType,
                'source' => 'USD',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $quoteKey = "USD{$currencyType}";

                if (isset($data['quotes']) && isset($data['quotes'][$quoteKey])) {
                    $rate = $data['quotes'][$quoteKey];
                } else {
                    throw new \Exception('Error: No se encontró la tasa de cambio para la moneda especificada.');
                }
            } else {
                throw new \Exception('Error: No se pudo obtener la tasa de cambio desde la API.');
            }
        } catch (\Exception $e) {
            // Manejo de errores específico
            throw new \Exception("Error: Falló la obtención de la tasa de cambio - {$e->getMessage()}");
        }

        // Convertir la cantidad a USD usando la tasa de cambio
        $convertedAmount = $amount / $rate;
        $convertedAmount = round($convertedAmount, 2); // Redondear a dos decimales
    } else {
        $convertedAmount = $amount;
    }

    $formattedRate = $this->formatCurrency($rate);

    if (!$this->operationExists($generatedOperation->operation_description)) {
        $operation = Operation::create([
            'operation_description' => $generatedOperation->operation_description,
            'operation_currency_type' => $currencyType,
            'operation_amount' => $amount,
            'operation_currency' => $formattedRate, // Guardar la tasa de cambio formateada
            'operation_currency_total' => $convertedAmount,
            'operation_status' => $generatedOperation->operation_status,
            'operation_date' => $parsedDate,
            'operation_month' => Carbon::parse($parsedDate)->format('m'),
            'operation_year' => Carbon::parse($parsedDate)->format('Y'),
            'category_id' => $processOperation->category_id,
            'user_id' => $processOperation->user_id,
        ]);

        $this->createRelatedRecords($processOperation, $operation);

        $generatedOperation->update(['last_processed_at' => now()]);
    }
}

private function formatCurrency($currency)
{
    if (is_numeric($currency)) {
        // Eliminar espacios
        $currency = preg_replace('/\s+/', '', $currency);

        // Convertir a float correctamente
        $currency = str_replace(',', '.', $currency);

        return number_format(floatval($currency), 2, ',', '.'); // Ajuste de formato
    } else {
        return $currency;
    }
}



private function operationExists($description)
{
    return Operation::where('operation_description', $description)->exists();
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
