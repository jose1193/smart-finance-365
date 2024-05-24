<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\SubcategoryToAssign;
use App\Models\ProcessOperationSubcategories;
use App\Models\OperationSubcategories;
use App\Models\BudgetIncome;
use App\Models\Operation;
use App\Models\CategoriesToAssign;
use App\Models\StatuOptions;

use App\Models\ProcessOperation;
use App\Models\ProcessBudgetIncome;

use App\Models\Budget;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http; // <-- guzzle query api
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\GeneratedOperation;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Log;

use Ramsey\Uuid\Uuid;

class ProcessOperationIncome extends Component
{
    use WithPagination;
    
public  $last_processed_at,$process_operation_date,$process_operation_date_end,$operation_description, $operation_amount,$operation_date, $operation_status, $category_id, $data_id;

public $search = '';
public $categoriesRender;
public $statusOptionsRender;

public $data2;
public $operation_currency; 
public $operation_currency_total; 
public $isOpen = 0;
protected $listeners = ['render','delete','currencyChanged','deleteMultiple']; 

public $subcategory_id = [];
public $showSubcategories = false;
public $subcategoryMessage;
public $selectedCategoryId;

public $selectedCurrencyFrom;
public $listCurrencies;
public $quotes;
public $operation_currency_type;

public $registeredSubcategoryItem;

public $selectedCurrencyFromARS;

public $selectAll = false;
public $checkedSelected = [];

 public $sortBy = 'process_operations.id'; // Columna predeterminada para ordenar
 public $sortDirection = 'asc'; // Dirección predeterminada para ordenar
 public $perPage = 10; 
 
 public $budgets, $budget_id;
 
    public function authorize()
{
    return true;
}


// ---- Date Matching Validation START ----//
protected $rules = [
        'process_operation_date' => 'required',
        'process_operation_date_end' => 'required',
    ];

    protected $messages = [
        'same_day' => 'The start and end dates must have the same day of the month.',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        // Convert dates to Y-m-d format if they are not in this format already
        if ($propertyName === 'process_operation_date' || $propertyName === 'process_operation_date_end') {
            $this->convertDateToYmd($propertyName);
        }

        // Custom validation for the same day
        if ($this->process_operation_date && $this->process_operation_date_end) {
            $this->validateCustomDay();
            
        }
    }

    protected function convertDateToYmd($propertyName)
    {
        try {
            if ($propertyName === 'process_operation_date' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->process_operation_date)) {
                $fechaCarbon = Carbon::createFromFormat('d/m/Y', $this->process_operation_date);
                $this->process_operation_date = $fechaCarbon->format('Y-m-d');
            }

            if ($propertyName === 'process_operation_date_end' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->process_operation_date_end)) {
                $fechaCarbon = Carbon::createFromFormat('d/m/Y', $this->process_operation_date_end);
                $this->process_operation_date_end = $fechaCarbon->format('Y-m-d');
            }
        } catch (Exception $e) {
            $this->addError($propertyName, 'The date format is invalid.');
        }
    }

 protected function validateCustomDay()
{
    try {
        // Intentar parsear la fecha en el formato d/m/Y
        $startDate = Carbon::createFromFormat('d/m/Y', $this->process_operation_date);
    } catch (\Exception $e) {
        // Si falla, asumir que la fecha está en el formato Y-m-d H:i:s
        $startDate = Carbon::parse($this->process_operation_date);
    }

    try {
        // Intentar parsear la fecha en el formato d/m/Y
        $endDate = Carbon::createFromFormat('d/m/Y', $this->process_operation_date_end);
    } catch (\Exception $e) {
        // Si falla, asumir que la fecha está en el formato Y-m-d H:i:s
        $endDate = Carbon::parse($this->process_operation_date_end);
    }

    // Inicializar una bandera para seguimiento de errores
    $hasErrors = false;

    // Validar que la fecha de inicio no sea inferior a la fecha actual
    if ($startDate->lessThan(Carbon::now()->startOfDay())) {
        $this->addError('process_operation_date', __('messages.start_date_cannot_be_past'));
        $hasErrors = true;
    }

    // Validar que el día sea igual en ambos campos
    if ($startDate->day !== $endDate->day) {
        $this->addError('process_operation_date', __('messages.same_day'));
        $this->addError('process_operation_date_end', __('messages.same_day'));
        $hasErrors = true;
    }

    // Validar que la fecha de inicio no sea igual a la fecha de finalización
    if ($startDate->equalTo($endDate)) {
        $this->addError('process_operation_date_end', __('messages.end_date_must_be_greater'));
        $hasErrors = true;
    }

    // Validar que la fecha de inicio no sea mayor que la fecha de finalización
    if ($startDate->greaterThan($endDate)) {
        $this->addError('process_operation_date_end', __('messages.end_date_cannot_be_less'));
        $hasErrors = true;
    }

    

    // Resetear los errores si no hay errores
    if (!$hasErrors) {
        $this->resetErrorBag(['process_operation_date', 'process_operation_date_end']);
    }
}




    // ---- Date Matching Validation END ----//


 public function render()
{
    $query = ProcessOperation::with([
        'category.mainCategories',
        'user',
        'statuOption',
        'operationProcessSubcategories.subcategory',
        'processBudgetIncomes.budget',
        'latestGeneratedOperation'
    ])
    ->whereHas('user', function($query) {
        $query->where('id', auth()->id());
    })
    ->whereHas('category', function($query) {
        $query->where('main_category_id', 1);
    })
    ->where(function ($query) {
        $query->whereHas('category', function ($query) {
            $query->where('category_name', 'like', '%' . $this->search . '%');
        })
        ->orWhere('operation_description', 'like', '%' . $this->search . '%')
        ->orWhere('id', 'like', '%' . $this->search . '%');
    });

    if ($this->sortBy === 'process_operations.operation_date') {
        $query->orderBy('operation_date', $this->sortDirection);
    } else {
        $query->orderBy($this->sortBy, $this->sortDirection);
    }

    $data = $query->paginate($this->perPage);

    $assignedCategories = CategoriesToAssign::where('user_id_assign', auth()->user()->id)
        ->pluck('category_id');

    $this->categoriesRender = Category::where('main_category_id', 1)
        ->whereIn('id', $assignedCategories)
        ->orWhere(function ($query) use ($assignedCategories) {
            $query->whereNotIn('id', $assignedCategories)
                ->whereNotExists(function ($subQuery) {
                    $subQuery->select(DB::raw(1))
                        ->from('categories_to_assigns')
                        ->whereColumn('categories_to_assigns.category_id', 'categories.id');
                })
                ->where('main_category_id', 1); 
        })
        ->orderBy('id', 'desc')
        ->get();

    $this->statusOptionsRender = StatuOptions::where('main_category_id', 1)
        ->orderBy('id', 'asc')
        ->get();

    $this->budgets = Budget::where('user_id', auth()->id())
        ->orderBy('id', 'desc')
        ->get();

    return view('livewire.process-operation-income', [
        'data' => $data
    ]);
}



    
    // Método para cambiar la cantidad de elementos por página
    public function updatedPerPage()
    {
        $this->resetPage(); // Resetear la página al cambiar la cantidad de elementos por página
    }

    
    //----------- ordering columns start --------------//
public function sortBy($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'desc';
        }

        $this->sortBy = $column;
    }
    
     //----------- end ordering columns --------------//


      // SELECT SUBCATEGORY_ID TO REGISTERSUBCATEGORYITEM

    public function updateSubCategoryUser()
    {
        // Asigna el valor de subcategory_id a registeredSubcategoryItem
        $this->registeredSubcategoryItem = $this->subcategory_id;
    }

     // END SELECT SUBCATEGORY_ID TO REGISTERSUBCATEGORYITEM

        public function fetchData()
    {
    // Hacer la solicitud HTTP a la API de monedas
    $response = Http::get('https://api.bluelytics.com.ar/v2/latest'); 
    $this->data2 = $response->json();
    }


    
        public function fetchDataCurrencies()
{
    // Hacer la solicitud HTTP a la API de monedas
    $response = Http::get('http://api.currencylayer.com/list', [
        'access_key' => 'd3314ac151faa4aaed99cefe494d4fc2',
    ]);

    // Si la llamada fue exitosa, parsea la respuesta
    if ($response->successful()) {
        $this->listCurrencies = json_decode($response->body(), true);

        // Excluir la moneda Argentine Peso (ARS)
        unset($this->listCurrencies['currencies']['ARS']);

        // Devuelve el array de monedas
        return $this->listCurrencies['currencies'];
    } else {
        // La llamada a la API no fue exitosa
        return null;
    }
}


    public function showSelectedCurrency()
    {
    // Verifica si $this->selectedCurrencyFrom es vacío o nulo
    if (empty($this->selectedCurrencyFrom)) {
         $this->operation_currency = null;
        $this->emit('currencyChanged');
        return;
    }

        $this->fetchData();

        if ($this->selectedCurrencyFrom === 'Blue-ARS' && isset($this->data2['blue']['value_sell'])) {
        $formattedCurrency = number_format($this->data2['blue']['value_sell'], 2, ',', '.');
        $this->operation_currency = $formattedCurrency;

        $this->operation_currency_type = $this->selectedCurrencyFrom;
    } else {
        // Realiza la solicitud HTTP para obtener la tasa de cambio de USD a la moneda seleccionada
        $response = Http::get('http://api.currencylayer.com/live', [
            'access_key' => 'd3314ac151faa4aaed99cefe494d4fc2',
            'currencies' => $this->selectedCurrencyFrom,
            'source' => 'USD',
        ]);

        if ($response->successful()) {
    $data = $response->json();
    $quoteKey = "USD{$this->selectedCurrencyFrom}";

    if (isset($data['quotes']) && isset($data['quotes'][$quoteKey])) {
    $rawValue = $data['quotes'][$quoteKey];

    // Ajuste para manejar la conversión y el redondeo
    $roundedValue = round($rawValue, 2);

    // Aplica el formato después del redondeo con espacio como separador de miles
    $this->operation_currency = number_format($roundedValue, 2, '.', ' ');

    $this->operation_currency_type = $this->selectedCurrencyFrom;
}

            else {
                $this->operation_currency = 'N/A';
                $this->operation_currency_type = $this->selectedCurrencyFrom;
                // Manejar el caso en el que la clave 'quotes' o la clave específica no está presente
            }
        } else {
            // Manejar el caso en el que la solicitud no fue exitosa
        }
    }
      // Emitir evento para reiniciar los valores
    $this->emit('currencyChanged');
     $this->emit('modalOpenedAutonumeric4');
            $this->selectedCurrencyFromARS = $this->selectedCurrencyFrom === 'Blue-ARS' ? 'ARS' : $this->selectedCurrencyFrom;  
    
}



// CALCULATE CURRENCY
public function updatedOperationAmount()
{
    // Reemplaza comas por nada para manejar el formato con comas
  $cleanedValue = str_replace([',', '.'], '', $this->operation_amount);
    // Reemplaza el espacio por nada para manejar el formato con espacios
  $cleanedCurrency = preg_replace('/[\s,\.]/', '', $this->operation_currency);


    // Verifica si el valor es un número
    if (is_numeric($cleanedValue) && is_numeric($cleanedCurrency) && $cleanedCurrency != 0) {
        // Realiza la operación de división y redondeo después de la división
        $result = $cleanedValue / $cleanedCurrency;

        // Aplica la condición: si el resultado es menor a 1, lo deja así, de lo contrario, lo redondea
        if ($result < 1) {
            $this->operation_currency_total = number_format($result, 2, '.', '');
        } else {
            $this->operation_currency_total = number_format($result, 2, ',', '.');
        }
    } else {
           $this->operation_currency_total = number_format(floatval($cleanedValue) / 100, 2, ',', '.');// O cualquier otro valor predeterminado
    }
}


  //CLEAN UP VALUES AFTER EACH CURRENCY CHANGE
 public function currencyChanged()
{
 
        $this->operation_currency_total = null;
        $this->operation_amount = null;
    
}


    public function create()
    {
       
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
        
        $this->emit('modalOpened'); // Emitir un evento cuando el modal se abre
          $this->emit('modalOpenedAutonumeric4'); 
        $this->fetchDataCurrencies();
        
        
        
       
    }

    public function closeModal()
    {
        $this->isOpen = false;
         $this->resetInputFields();
        
    }

    private function resetInputFields(){
         $this->reset();
         $this->resetValidation();
    }

       
    public function edit($id)
{
    
    // Custom validation for the same day
        $this->validateCustomDay();
   $list = ProcessOperation::with(['generatedOperations', 'category', 'operationProcessSubcategories'])
        ->leftJoin('process_budget_incomes', 'process_operations.id', '=', 'process_budget_incomes.process_operation_id')
        ->leftJoin('budgets', 'process_budget_incomes.budget_id', '=', 'budgets.id')
        ->findOrFail($id);


    $this->data_id = $id;
    $this->operation_description = $list->operation_description;
    $this->operation_amount = number_format($list->operation_amount, 2, '.', ',');
    $this->operation_currency = $list->operation_currency;
    $this->operation_currency_total = number_format($list->operation_currency_total, 2, '.', ',');
    $this->operation_status = $list->operation_status;
    $this->selectedCurrencyFrom = $list->operation_currency_type;
    $this->operation_currency_type = $list->operation_currency_type;
    $this->operation_date = Carbon::parse($list->operation_date)->format('d/m/Y');
    $this->process_operation_date = Carbon::parse($list->process_operation_date)->format('d/m/Y');
    $this->process_operation_date_end = Carbon::parse($list->process_operation_date_end)->format('d/m/Y');
    $this->openModal();
    $this->updatedOperationAmount();
    $this->selectedCategoryId = $list->category_id;
    $this->showSubcategories = true;

    // Obtener la información de la operación con posible categoría nula
    $process_operation = ProcessOperation::with('category', 'operationProcessSubcategories')->findOrFail($id);
    $this->category_id = optional($process_operation->category)->id;
    
    $registeredSubcategory = $process_operation->operationProcessSubcategories->first();
    
    $this->updatedCategoryId($this->category_id, optional($registeredSubcategory)->subcategory_id);

    // Asignar el budget_id
    $this->budget_id = $list->budget_id ? $list->budget_id : 'na';

   
}



// ---- FUNCTION STORE START ----//
public function store()
{
    // Custom validation for operation_date
    $this->validateAndFormatDate('process_operation_date');
    $this->validateAndFormatDate('process_operation_date_end');

    // Realizar las validaciones personalizadas
    $this->validateCustomDay();

    // Si hay errores de validación, detener el flujo
    if ($this->getErrorBag()->isNotEmpty()) {
        return;
    }

    $validationRules = [
        'operation_description' => 'required|string|max:255',
        'operation_currency_type' => 'required',
        'operation_amount' => 'required',
        'operation_currency' => 'required',
        'operation_currency_total' => 'required',
        'operation_status' => 'required',
        'category_id' => 'required|exists:categories,id',
        'budget_id' => 'nullable',
        'process_operation_date' => 'required',
        'process_operation_date_end' => 'required',
        'last_processed_at' => 'nullable',
        'registeredSubcategoryItem' => 'nullable',
    ];

    $validatedData = $this->validate($validationRules);

    // Agregar user_id al array validado
    $validatedData['user_id'] = auth()->user()->id;

    // Calcular el mes y el año a partir de expense_date usando Carbon
    $operationDate = Carbon::createFromFormat('Y-m-d', $validatedData['process_operation_date']);
    $validatedData['operation_month'] = $operationDate->format('m');
    $validatedData['operation_year'] = $operationDate->format('Y');

    // Procesamiento de cantidades y monedas
    $validatedData = $this->processAmountsAndCurrencies($validatedData);

    // Actualización o creación de operación
    $this->processTodayOrFutureOperation($validatedData);

    $this->closeModal();
}

private function validateAndFormatDate($dateField)
{
    if (empty($this->$dateField)) {
        $this->addError($dateField, 'The date field is required.');
    } else {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->$dateField)) {
            try {
                $fechaCarbon = Carbon::createFromFormat('d/m/Y', $this->$dateField);
                $this->$dateField = $fechaCarbon->format('Y-m-d');
            } catch (Exception $e) {
                $this->addError($dateField, 'The date format is invalid.');
            }
        }
    }
}

private function processAmountsAndCurrencies($validatedData)
{
    $validatedData['operation_amount'] = $this->formatAmount($validatedData['operation_amount']);
    $validatedData['operation_currency_total'] = $this->formatAmount($validatedData['operation_currency_total']);
    $validatedData['operation_currency'] = $this->formatCurrency($validatedData['operation_currency']);
    return $validatedData;
}

private function formatAmount($amount)
{
    $numericValue = preg_replace('/[\s,\.]/', '', $amount);
    return number_format(floatval($numericValue) / 100, 2, '.', '');
}

private function formatCurrency($currency)
{
    if (is_numeric($currency)) {
        return number_format(floatval($currency) / 100, 2, ',', '.');
    } else {
        return $currency;
    }
}


private function processTodayOrFutureOperation($validatedData)
{
    DB::beginTransaction();

    try {
        $currentDate = now()->format('Y-m-d');

        if ($this->data_id) {
            $this->updateExistingProcessOperation($validatedData, $currentDate);
        } else {
            $this->createNewProcessOperation($validatedData, $currentDate);
        }

        DB::commit();
        session()->flash('message', __('messages.data_created_successfully'));
    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Error al procesar la operación: ' . $e->getMessage());
        session()->flash('error', 'Error: ' . $e->getMessage());
    }
}

private function updateExistingProcessOperation($validatedData, $currentDate)
{
    try {
        $existingProcessOperation = ProcessOperation::find($this->data_id);
        $isDateChanged = $existingProcessOperation && $existingProcessOperation->process_operation_date != $validatedData['process_operation_date'];

        unset($validatedData['last_processed_at']);
        $validatedData['operation_date'] = $currentDate;

        $processOperation = ProcessOperation::updateOrCreate(['id' => $this->data_id], $validatedData);

        $this->processRelatedData($processOperation, $validatedData);

        if ($isDateChanged || $validatedData['process_operation_date'] == $currentDate) {
            $this->deleteAndCreateGeneratedOperations($processOperation, $validatedData['process_operation_date'], $validatedData['process_operation_date_end']);
        }

        if ($validatedData['process_operation_date'] == $currentDate) {
            $this->handleCurrentDateOperation($validatedData, $existingProcessOperation, $currentDate);
        }
    } catch (\Exception $e) {
        Log::error('Error al actualizar o crear una operación del proceso: ' . $e->getMessage());
        return response()->json(['error' => 'Error al actualizar o crear una operación del proceso: ' . $e->getMessage()], 500);
    }
}

private function handleCurrentDateOperation($validatedData, $existingProcessOperation, $currentDate)
{
    $operationDate = Carbon::parse($validatedData['process_operation_date'])->format('Y-m-d');

    $existingOperation = Operation::where('operation_date', $operationDate)
        ->where('operation_description', $existingProcessOperation->operation_description)
        ->first();

    if ($existingOperation) {
        $this->updateOperationAndGeneratedOperations($validatedData, $existingOperation, $existingProcessOperation);
    } else {
        $validatedData['operation_date'] = $operationDate;
        $this->saveOperation($validatedData);
    }
}

private function updateOperationAndGeneratedOperations($validatedData, $existingOperation, $existingProcessOperation)
{
    $updatedOperation = $this->saveOperation($validatedData, $existingOperation->id);

    if ($existingOperation->operation_description != $validatedData['operation_description']) {
        $updatedOperation->update(['operation_description' => $validatedData['operation_description']]);

        foreach ($existingProcessOperation->generatedOperations as $generatedOperation) {
            $generatedOperation->update(['operation_description' => $validatedData['operation_description']]);
        }
    }
}

private function createNewProcessOperation($validatedData, $currentDate)
{
    $startDate = Carbon::parse($validatedData['process_operation_date']);
    $endDate = Carbon::parse($validatedData['process_operation_date_end']);

    $validatedData['process_operation_date'] = $startDate->format('Y-m-d');
    $validatedData['process_operation_date_end'] = $endDate->format('Y-m-d');
    $validatedData['operation_date'] = $startDate->format('Y-m-d');
    $validatedData['process_operation_uuid'] = Uuid::uuid4()->toString();
    $processOperation = ProcessOperation::create($validatedData);

   

    if ($validatedData['process_operation_date'] == $currentDate) {
        $this->saveOperation($validatedData);
    }

    // Process related data
    $this->registeredSubcategoryItem = $validatedData['registeredSubcategoryItem'];
    $this->processRelatedData($processOperation, $validatedData);
    
    // Create Generated Operations
    $this->createGeneratedOperations($processOperation, $startDate, $endDate, $currentDate);
}

private function processRelatedData($processOperation, $validatedData)
{
    $this->ProcessSubcategoryOperationAssignment($processOperation);
    $this->ProcessBudgetIncome($validatedData['budget_id'] ?? null, $processOperation);
}

private function createGeneratedOperations($processOperation, $startDate, $endDate, $currentDate)
{
    $budget = $processOperation->processBudgetIncomes()->first();
    $processOperation->budget_id = $budget ? $budget->budget_id : null;

    $currentGeneratedOperation = null;

    while ($startDate->lessThanOrEqualTo($endDate)) {
        $generatedOperation = GeneratedOperation::create([
            'process_operation_id' => $processOperation->id,
            'process_operation_date_job' => $startDate->format('Y-m-d'),
            'operation_description' => $processOperation->operation_description,
            'operation_currency_type' => $processOperation->operation_currency_type,
            'operation_amount' => $processOperation->operation_amount,
            'operation_currency' => $processOperation->operation_currency,
            'operation_currency_total' => $processOperation->operation_currency_total,
            'operation_date' => $startDate->format('Y-m-d'),
            'operation_status' => $processOperation->operation_status,
            'budget_id' => $processOperation->budget_id,
            'process_operation_uuid' => $processOperation->process_operation_uuid,
        ]);

        if ($startDate->format('Y-m-d') == $currentDate) {
            $currentGeneratedOperation = $generatedOperation;
        }

        $startDate->addMonth();
    }

    if ($currentGeneratedOperation) {
        $currentGeneratedOperation->update(['last_processed_at' => now()]);
    }
}

private function saveOperation($validatedData, $operationId = null)
{
    $operation = Operation::updateOrCreate(
        ['id' => $operationId],
        $validatedData
    );

    $this->SubcategoryOperationAssignment($operation, $validatedData['registeredSubcategoryItem']);
    $this->BudgetIncome($validatedData['budget_id'] ?? null, $operation);

    return $operation;
}

private function deleteAndCreateGeneratedOperations($processOperation, $startDate, $endDate)
{
    $startDate = Carbon::parse($startDate);
    $endDate = Carbon::parse($endDate);
    
    $processOperation->generatedOperations()->delete();

    while ($startDate->lessThanOrEqualTo($endDate)) {
        $budgetIncome = $processOperation->processBudgetIncomes()->first();

        $generatedOperation = $processOperation->generatedOperations()->create([
            'process_operation_date_job' => $startDate->format('Y-m-d'),
            'operation_description' => $processOperation->operation_description,
            'operation_currency_type' => $processOperation->operation_currency_type,
            'operation_amount' => $processOperation->operation_amount,
            'operation_currency' => $processOperation->operation_currency,
            'operation_currency_total' => $processOperation->operation_currency_total,
            'process_operation_id' => $processOperation->id,
            'operation_date' => $startDate->format('Y-m-d'),
            'operation_status' => $processOperation->operation_status,
            'budget_id' => $budgetIncome ? $budgetIncome->budget_id : null,
            'process_operation_uuid' => $processOperation->process_operation_uuid,
            
        ]);

        if ($startDate->isSameDay(now())) {
            $generatedOperation->update(['last_processed_at' => now()]);
        }

        $startDate->addMonth();
    }
}

// ---- FUNCTION STORE END ----//

public function ProcessSubcategoryOperationAssignment(ProcessOperation $processOperation)
{
    
    // Verificar si $this->registeredSubcategoryItem no es 'N/A' y no está vacío
    if ($this->registeredSubcategoryItem != 'N/A' && !empty($this->registeredSubcategoryItem)) {
        // Buscar una subcategoría existente para la operación
        $existingSubcategory = ProcessOperationSubcategories::where('process_operation_id', $processOperation->id)->first();

        if ($existingSubcategory) {
            // Si la subcategoría ya existe, actualizarla
            $existingSubcategory->update([
                'subcategory_id' => $this->registeredSubcategoryItem,
                'user_id_subcategory' => $processOperation->user_id,
            ]);
        } else {
            // Si no existe, crear una nueva subcategoría
            ProcessOperationSubcategories::create([
                'process_operation_id' => $processOperation->id,
                'subcategory_id' => $this->registeredSubcategoryItem,
                'user_id_subcategory' => $processOperation->user_id,
            ]);
        }
    } else {
        // Si $this->registeredSubcategoryItem es 'N/A' o está vacío, eliminar registros en OperationSubcategories
        ProcessOperationSubcategories::where('process_operation_id', $processOperation->id)->delete();
    }

    $this->resetInputFields();
}


public function updatedCategoryId($value,$registeredSubcategoryId = null)
{
    $userId = auth()->user()->id;

    // Lógica para obtener la subcategoría registrada en OperationSubcategories
    $operationSubcategory = SubcategoryToAssign::where('user_id_subcategory', $userId)
        ->where('subcategory_id', $value)
        ->first();

    // Lógica para obtener las demás subcategorías asignadas al usuario autenticado en la categoría seleccionada
    $userSubcategories = SubcategoryToAssign::where('user_id_subcategory', $userId)
        ->where('subcategory_id', '!=', $value)
        ->whereHas('subCategory', function ($query) use ($value) {
            $query->where('category_id', $value);
        })
        ->pluck('subcategory_id');

    // Lógica para obtener todas las subcategorías en la categoría seleccionada (independientemente de la asignación a usuarios)
    $allSubcategories = Subcategory::where('category_id', $value)->pluck('id');

    // Pasa todas las subcategorías a la vista si no hay subcategorías asignadas al usuario actual
    $this->subcategory_id = $userSubcategories->isNotEmpty() ? $userSubcategories->toArray() : $allSubcategories->toArray();

    // Muestra el select2 de subcategorías solo si hay subcategorías disponibles
    $this->showSubcategories = !empty($this->subcategory_id);
        
     $this->subcategoryMessage = $this->showSubcategories
    ? null
    : __('messages.subcategory_no_subcategories');
    
    // Configura la subcategoría registrada como seleccionada
    $this->registeredSubcategoryItem = $registeredSubcategoryId;
    
}


public function ProcessBudgetIncome($budgetId, ProcessOperation $processOperation)
{
    $operationId = $processOperation->id;
    $categoryId = $processOperation->category_id;

    if ($operationId) {
        // Verifica si $budgetId está vacío o es igual a 'NO'
        if (empty($budgetId) || $budgetId === 'na') {
            // Elimina la entrada existente si $budgetId está vacío o es 'NO'
            ProcessBudgetIncome::where(['process_operation_id' => $operationId])->delete();
            session()->flash('message', __('messages.data_deleted_successfully'));
        } else {
            // Realiza un updateOrCreate si $budgetId tiene un valor diferente de 'NO'
            ProcessBudgetIncome::updateOrCreate(
                ['process_operation_id' => $operationId],
                [
                    'budget_id' => $budgetId,
                    'category_id' => $categoryId,
                    // Puedes agregar aquí otros campos que desees actualizar o crear
                ]
            );
            session()->flash('message', __('messages.data_created_or_updated_successfully'));
        }
    } else {
        session()->flash('info', __('Invalid operation'));
    }
}



public function SubcategoryOperationAssignment(Operation $operation,$subcategory)
{
    // Verificar si $this->registeredSubcategoryItem no es 'N/A' y no está vacío
    if ($subcategory != 'N/A' && !empty($subcategory)) {
        // Buscar una subcategoría existente para la operación
        $existingSubcategory = OperationSubcategories::where('operation_id', $operation->id)->first();

        if ($existingSubcategory) {
            // Si la subcategoría ya existe, actualizarla
            $existingSubcategory->update([
                'subcategory_id' => $subcategory,
                'user_id_subcategory' => $operation->user_id,
            ]);
        } else {
            // Si no existe, crear una nueva subcategoría
            OperationSubcategories::create([
                'operation_id' => $operation->id,
                'subcategory_id' => $subcategory,
                'user_id_subcategory' => $operation->user_id,
            ]);
        }
    } else {
        // Si $this->registeredSubcategoryItem es 'N/A' o está vacío, eliminar registros en OperationSubcategories
        OperationSubcategories::where('operation_id', $operation->id)->delete();
    }

    $this->resetInputFields();
}



public function BudgetIncome($budgetId, Operation $operation)
{
    $operationId = $operation->id;
    $categoryId = $operation->category_id;

    if ($operationId) {
        // Verifica si $budgetId está vacío o es igual a 'na'
        if (empty($budgetId) || $budgetId === 'na') {
            // Elimina la entrada existente si $budgetId está vacío o es 'na'
            BudgetIncome::where(['operation_id' => $operationId])->delete();
            session()->flash('message', __('messages.data_deleted_successfully'));
        } else {
            // Realiza un updateOrCreate si $budgetId tiene un valor diferente de 'na'
            BudgetIncome::updateOrCreate(
                ['operation_id' => $operationId],
                [
                    'budget_id' => $budgetId,
                    'category_id' => $categoryId,
                    // Puedes agregar aquí otros campos que desees actualizar o crear
                ]
            );
            session()->flash('message', __('messages.data_created_or_updated_successfully'));
        }
    } else {
        session()->flash('info', __('Invalid operation'));
    }
}




    public function delete($id)
{
    $operation = ProcessOperation::find($id);
    $description = $operation->operation_description; // Obteniendo la descripción antes de eliminarla
    $operation->delete();
     session()->flash('message', $description .  __('messages.category_deleted_successfully'));
}


    //---- FUNCTION DELETE MULTIPLE ----//
 public function updatedSelectAll($value)
{
     
     
    if ($value) {
         
        $this->checkedSelected = $this->getItemsIds();

    } else {
       
        $this->checkedSelected = [];

    }
   
   
}

public function getItemsIds()
{
   
    
    // Retorna un array con los IDs de los elementos disponibles
    return ProcessOperation::join('categories', 'process_operations.category_id', '=', 'categories.id')
        ->join('users', 'process_operations.user_id', '=', 'users.id')
        ->join('main_categories', 'main_categories.id', '=', 'categories.main_category_id')
        ->join('statu_options', 'process_operations.operation_status', '=', 'statu_options.id')
        ->leftJoin('process_operation_subcategories', 'process_operation_subcategories.process_operation_id', '=', 'process_operations.id')
        ->leftJoin('subcategories', 'process_operation_subcategories.subcategory_id', '=', 'subcategories.id')
        ->where('users.id', Auth::id()) // Filtra por el ID del usuario autenticado
        ->where('main_categories.id', 1) // Filtra por la categoría principal con ID 1
        ->pluck('process_operations.id')
        ->toArray();
  
   
}

public function confirmDelete()
{
    $this->emit('showConfirmation'); // Emite un evento para mostrar la confirmación
    
}

public function deleteMultiple()
{
    if (count($this->checkedSelected) > 0) {
        ProcessOperation::whereIn('id', $this->checkedSelected)->delete();
        $this->checkedSelected = [];
        session()->flash('message', __('messages.data_deleted_successfully'));
        $this->selectAll = false;
        
    }
   
    
}

 //---- END FUNCTION DELETE MULTIPLE ----//


}
