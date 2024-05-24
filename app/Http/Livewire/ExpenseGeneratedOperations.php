<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\SubcategoryToAssign;
use App\Models\OperationSubcategories;
use App\Models\CategoriesToAssign;
use App\Models\StatuOptions;

use App\Models\GeneratedOperation;
use App\Models\Budget;
use App\Models\BudgetExpense;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http; // <-- guzzle query api
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;




class ExpenseGeneratedOperations extends Component
{
    use WithPagination;
    
public  $operation_description, $operation_amount,$operation_date, $operation_status, $category_id, $data_id;

public $search = '';



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

 public $sortBy = 'generated_operations.operation_date'; // Columna predeterminada para ordenar
 public $sortDirection = 'asc'; // Dirección predeterminada para ordenar
 public $perPage = 10; 
 
 

    public $categoriesRender = [];
    public $statusOptionsRender = [];
    public $budgets = [];
    
public $budget_id;

public $operationId;

public $process_operation_date_job,$process_operation_id,$last_processed_at;
    
 
   
    public function authorize()
{
    return true;
}
    
public function mount($uuid)
    {
        $this->operationId = $uuid;
        $this->loadCategories();
        $this->loadStatusOptions();
        $this->loadBudgets();
    }


    


public function loadCategories()
    {
        $assignedCategories = CategoriesToAssign::where('user_id_assign', auth()->user()->id)
            ->pluck('category_id');

        $this->categoriesRender = Category::where('main_category_id', 2)
            ->whereIn('id', $assignedCategories)
            ->orWhere(function ($query) use ($assignedCategories) {
                $query->whereNotIn('id', $assignedCategories)
                      ->whereNotExists(function ($subQuery) {
                          $subQuery->select(DB::raw(1))
                                   ->from('categories_to_assigns')
                                   ->whereColumn('categories_to_assigns.category_id', 'categories.id');
                      })
                      ->where('main_category_id', 2);
            })
            ->orderBy('id', 'desc')
            ->get();
    }

    public function loadStatusOptions()
    {
        $this->statusOptionsRender = StatuOptions::where('main_category_id', 2)
            ->orderBy('id', 'asc')
            ->get();
    }

    public function loadBudgets()
    {
        $this->budgets = Budget::where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->get();
    }



public function render()
{
    $query = GeneratedOperation::with('ProcessOperation')
        ->whereHas('ProcessOperation', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->where('process_operation_uuid', $this->operationId);

    // Añadir la lógica de búsqueda
    if ($this->search) {
        $query->where('generated_operations.operation_description', 'like', '%' . $this->search . '%');
    }

    if ($this->sortBy === 'generated_operations.operation_date') {
        $query->orderBy('generated_operations.operation_date', $this->sortDirection);
    } else {
        $query->orderBy($this->sortBy, $this->sortDirection);
    }

    $generatedOperations = $query->paginate($this->perPage);

    return view('livewire.expense-generated-operations', [
        'data' => $generatedOperations,
        'categoriesRender' => $this->categoriesRender,
        'statusOptionsRender' => $this->statusOptionsRender,
        'budgets' => $this->budgets,
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
            $this->sortDirection = 'asc';
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
    // Construir la consulta para obtener el registro específico
    $query = GeneratedOperation::with('ProcessOperation')
        ->whereHas('ProcessOperation', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->where('process_operation_uuid', $this->operationId);

    // Obtener el registro específico usando findOrFail
    $list = $query->findOrFail($id);

    // Asignar valores a las propiedades del componente
    $this->data_id = $id;
    $this->operation_description = $list->operation_description;
    $this->operation_amount = number_format($list->operation_amount, 2, '.', ',');
    $this->operation_currency = $list->operation_currency;
    $this->operation_currency_total = number_format($list->operation_currency_total, 2, '.', ',');
    $this->operation_status = $list->operation_status;
    $this->selectedCurrencyFrom = $list->operation_currency_type;
    $this->operation_currency_type = $list->operation_currency_type;
    $this->operation_date = Carbon::parse($list->operation_date)->format('d/m/Y');
    $this->budget_id = $list->budget_id ? $list->budget_id : 'na';

    // Abrir el modal
    $this->openModal();

    // Actualizar la cantidad de operación
    $this->updatedOperationAmount();

    
}


public function store()
{
   
       // Custom validation for operation_date
if (empty($this->operation_date)) {
    $this->addError('operation_date', 'The date field is required.');
} else {
    // Verificar si la fecha tiene el formato 'Y-m-d'
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->operation_date)) {
        // Si ya está en el formato 'Y-m-d', no es necesario convertirlo
        
    } else {
        // Convertir y formatear la fecha si no tiene el formato 'Y-m-d'
        $fechaCarbon = Carbon::createFromFormat('d/m/Y', $this->operation_date);
        $this->operation_date = $fechaCarbon->format('Y-m-d');
    }
}


    $validationRules = [
        'operation_description' => 'required|string|max:255',
        'operation_currency_type' => 'required',
        'operation_amount' => 'required',
        'operation_currency' => 'required',
        'operation_currency_total' => 'required',
        'operation_status' => 'required',
        'operation_date' => 'required|date',
       
        'budget_id' => 'nullable',
    ];
    
    $validatedData = $this->validate($validationRules);

  
    
     $validatedData['budget_id'] = $this->budget_id;

    // Calcular el mes y el año  usando Carbon
    $operationDate = \Carbon\Carbon::createFromFormat('Y-m-d', $validatedData['operation_date']);
    $validatedData['operation_month'] = $operationDate->format('m');
    $validatedData['operation_year'] = $operationDate->format('Y');

    // Elimina cualquier carácter no numérico, como comas y puntos
   $numericValue = str_replace([',', '.'], '', $validatedData['operation_amount']);
    $numericValue2 = str_replace([',', '.'], '', $validatedData['operation_currency_total']);
    $numericValue3 = preg_replace('/[\s,\.]/', '', $validatedData['operation_currency']);

    // Divide los valores por 100 y formatea con dos decimales
    $formattedValue = number_format($numericValue / 100, 2, '.', '');
    $formattedValue2 = number_format($numericValue2 / 100, 2, '.', '');
    
    if (is_numeric($numericValue3)) {
    $formattedValue3 = number_format(floatval($numericValue3) / 100, 2, ',', '.');
    } else {
    $formattedValue3 = $validatedData['operation_currency'];
    }

    // Asigna la cadena, sin convertirla a un entero
    $validatedData['operation_amount'] = $formattedValue;
    $validatedData['operation_currency_total'] = $formattedValue2;
    $validatedData['operation_currency'] = $formattedValue3;
   
     // Manejar budget_id
    $validatedData['budget_id'] = $this->budget_id === 'na' || $this->budget_id === '' ? null : (int) $this->budget_id;



    $operation = GeneratedOperation::updateOrCreate(['id' => $this->data_id], $validatedData);

     session()->flash('message', 
    $this->data_id ? __('messages.data_updated_successfully') : __('messages.data_created_successfully'));
    
    
    return redirect()->route('expense-generated.operations',  ['uuid' => $this->operationId]);
   
}


public function SubcategoryOperationAssignment(Operation $operation)
{
    // Verificar si $this->registeredSubcategoryItem no es 'N/A' y no está vacío
    if ($this->registeredSubcategoryItem != 'N/A' && !empty($this->registeredSubcategoryItem)) {
        // Buscar una subcategoría existente para la operación
        $existingSubcategory = OperationSubcategories::where('operation_id', $operation->id)->first();

        if ($existingSubcategory) {
            // Si la subcategoría ya existe, actualizarla
            $existingSubcategory->update([
                'subcategory_id' => $this->registeredSubcategoryItem,
                'user_id_subcategory' => $operation->user_id,
            ]);
        } else {
            // Si no existe, crear una nueva subcategoría
            OperationSubcategories::create([
                'operation_id' => $operation->id,
                'subcategory_id' => $this->registeredSubcategoryItem,
                'user_id_subcategory' => $operation->user_id,
            ]);
        }
    } else {
        // Si $this->registeredSubcategoryItem es 'N/A' o está vacío, eliminar registros en OperationSubcategories
        OperationSubcategories::where('operation_id', $operation->id)->delete();
    }

    $this->resetInputFields();
}


public function BudgetExpense($budgetId, Operation $operation)
{
    $operationId = $operation->id;
    $categoryId = $operation->category_id;

    if ($operationId) {
        // Verifica si $budgetId está vacío o es igual a 'NO'
        if (empty($budgetId) || $budgetId === 'na') {
            // Elimina la entrada existente si $budgetId está vacío o es 'NO'
            BudgetExpense::where(['operation_id' => $operationId])->delete();
             session()->flash('message', __('messages.data_created_successfully'));
        } else {
            // Realiza un updateOrCreate si $budgetId tiene un valor diferente de 'NO'
            BudgetExpense::updateOrCreate(
                ['operation_id' => $operationId, 'budget_id' => $budgetId, 'category_id' => $categoryId],
                // Puedes agregar aquí otros campos que desees actualizar o crear
            );
            session()->flash('message', __('messages.data_created_successfully'));
        }
    } else {
        // session()->flash('info', __('Invalid operation'));
    }
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



    public function delete($id)
{
    $operation = GeneratedOperation::find($id);
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
    return Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('users', 'operations.user_id', '=', 'users.id')
        ->join('main_categories', 'main_categories.id', '=', 'categories.main_category_id')
        ->join('statu_options', 'operations.operation_status', '=', 'statu_options.id')
        ->leftJoin('operation_subcategories', 'operation_subcategories.operation_id', '=', 'operations.id')
        ->leftJoin('subcategories', 'operation_subcategories.subcategory_id', '=', 'subcategories.id')
        ->where('users.id', Auth::id()) // Filtra por el ID del usuario autenticado
        ->where('main_categories.id', 1) // Filtra por la categoría principal con ID 1
        ->pluck('operations.id')
        ->toArray();
  
   
}

public function confirmDelete()
{
    $this->emit('showConfirmation'); // Emite un evento para mostrar la confirmación
    
}

public function deleteMultiple()
{
    if (count($this->checkedSelected) > 0) {
        Operation::whereIn('id', $this->checkedSelected)->delete();
        $this->checkedSelected = [];
        session()->flash('message', __('messages.data_deleted_successfully'));
        $this->selectAll = false;
        
    }
   
    
}

 //---- END FUNCTION DELETE MULTIPLE ----//

 
}