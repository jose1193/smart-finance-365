<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\SubcategoryToAssign;
use App\Models\OperationSubcategories;
use App\Models\CategoriesToAssign;
use App\Models\StatuOptions;
use App\Models\Operation;
use App\Models\Budget;
use App\Models\BudgetExpense;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http; // <-- guzzle query api
use Illuminate\Support\Facades\DB;

class Operations extends Component
{
    use WithPagination;
    
public  $operation_description, $operation_amount,$operation_date, $operation_status, $category_id, $data_id;
public $search = '';
public $categoriesRender;
public $statusOptionsRender;

public $data2;
public $operation_currency; 
public $operation_currency_total; 
public $isOpen = 0;
protected $listeners = ['render','delete','currencyChanged']; 

public $subcategory_id = [];
public $showSubcategories = false;
public $subcategoryMessage;
public $selectedCategoryId;

public $selectedCurrencyFrom;
public $listCurrencies;
public $quotes;
public $operation_currency_type;

public $budgets;
public $budget_id;

public $registeredSubcategoryItem;

public $selectedCurrencyFromARS;

    public function authorize()
{
    return true;
}


    public function render()
    {
    $data = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
    ->join('users', 'operations.user_id', '=', 'users.id')
    ->join('main_categories', 'main_categories.id', '=', 'categories.main_category_id')
    ->join('statu_options', 'operations.operation_status', '=', 'statu_options.id')
    ->leftJoin('operation_subcategories', 'operation_subcategories.operation_id', '=', 'operations.id')
    ->leftJoin('subcategories', 'operation_subcategories.subcategory_id', '=', 'subcategories.id')
    ->leftJoin('budget_expenses', 'budget_expenses.operation_id', '=', 'operations.id')
    ->leftJoin('budgets', 'budget_expenses.budget_id', '=', 'budgets.id')
    ->where('users.id', auth()->id())
    ->where('categories.main_category_id', 2)
    ->where(function ($query) {
        $query->where('categories.category_name', 'like', '%' . $this->search . '%')
              ->orWhere('operations.operation_description', 'like', '%' . $this->search . '%');
    })
    ->select(
        'operations.*',
        'categories.category_name','budgets.budget_currency_total',
        'statu_options.status_description',
        DB::raw('COALESCE(subcategories.subcategory_name, "N/A") as display_name'),
        'budget_expenses.operation_id as budget_expense_operation_id',
        'budgets.budget_date as date',
        'budgets.id as budget_id'
    )
    ->orderBy('operations.id', 'desc')
    ->paginate(10);


    $assignedCategories = CategoriesToAssign::where('user_id_assign', auth()->user()->id)
    ->pluck('category_id');

    $this->categoriesRender = Category::where('main_category_id', 2)
    ->whereIn('id', $assignedCategories)
    ->orWhere(function ($query) use ($assignedCategories) {
        $query->whereNotIn('id', $assignedCategories)
              ->whereNotExists(function ($subQuery) {
                  $subQuery->select(DB::raw(2))
                           ->from('categories_to_assigns')
                           ->whereColumn('categories_to_assigns.category_id', 'categories.id');
              })
              ->where('main_category_id', 2); 
    })
    ->orderBy('id', 'asc')
    ->get();

    
    $this->statusOptionsRender = StatuOptions::where('main_category_id', 2)
                                  ->orderBy('id', 'asc')
                                  ->get();
       
    $this->budgets = Budget::where('user_id', auth()->id())
        ->orderBy('id', 'desc')
        ->get();

     

        return view('livewire.expenses-operations', [
            'data' => $data]);
    }

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
    $response = Http::get('https://api.bluelytics.com.ar/v2/latest'); // Reemplaza con la URL correcta de la API
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
    $this->emit('modalOpenedAutonumeric5');
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
         
        $this->authorize('manage admin');
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->emit('modalOpened'); // Emitir un evento cuando el modal se abre
          $this->emit('modalOpenedAutonumeric5'); 
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
        $this->authorize('manage admin');
        $list = Operation::leftJoin('budget_expenses', 'operations.id', '=', 'budget_expenses.operation_id')
        ->leftJoin('budgets', 'budget_expenses.budget_id', '=', 'budgets.id')
        ->findOrFail($id);
        $this->data_id = $id;
        $this->operation_description = $list->operation_description;
        $this->operation_amount = number_format($list->operation_amount, 2, '.', ',');
        $this->operation_currency = $list->operation_currency;
        $this->operation_currency_total = number_format($list->operation_currency_total, 2, '.', ',');
        $this->operation_status = $list->operation_status;

        // Obtener la información de la operación con posible categoría nula
        $operation = Operation::with('category', 'operationSubcategories')->findOrFail($id);
       
        $this->category_id = $operation->category->id;

        $this->selectedCurrencyFrom = $list->operation_currency_type;
        $this->operation_currency_type=$list->operation_currency_type;
        $this->operation_date =  Carbon::parse($list->operation_date)->format('d/m/Y');
        $this->openModal();
        $this->updatedOperationAmount();
       
        $this->selectedCategoryId = $list->category_id;
        $this->showSubcategories = true;

       // Obtener la subcategoría asociada, si existe
        $registeredSubcategory = $operation->operationSubcategories->first();
        
         $this->updatedCategoryId($this->category_id, optional($registeredSubcategory)->subcategory_id);

        // Asignar el budget_id
       $this->budget_id = $list->budget_id ? $list->budget_id : 'na';

        
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
        'category_id' => 'required|exists:categories,id',
        
    ];
    
    $validatedData = $this->validate($validationRules);

    // Agregar user_id al array validado
    $validatedData['user_id'] = auth()->user()->id;
    $validatedData['budget_id'] = $this->budget_id;

    // Calcular el mes y el año a partir de expense_date usando Carbon
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
   

    $operation = Operation::updateOrCreate(['id' => $this->data_id], $validatedData);

    session()->flash('message', $this->data_id ? 'Data Updated Successfully.' : 'Data Created Successfully.');
    
    
    
      // Llamada a la función para asignar usuarios a operaciones subcategorías
    $this->SubcategoryOperationAssignment($operation);

   // Llama a la función solo si 'budget_id' está presente
$this->BudgetExpense($validatedData['budget_id'] ?? null, $operation);



   

    $this->closeModal();
    $this->resetInputFields();
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


public function updatedCategoryId($value,$registeredSubcategoryId = null)
{
    $userId = auth()->user()->id;

    // Lógica para obtener las subcategorías asignadas al usuario autenticado en la categoría seleccionada
    $userSubcategories = SubcategoryToAssign::where('user_id_subcategory', $userId)
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
        : 'The category has no subcategories. Please follow the registration process.';

    // Configura la subcategoría registrada como seleccionada
    $this->registeredSubcategoryItem = $registeredSubcategoryId;
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
            session()->flash('message', __('Data Deleted Successfully'));
        } else {
            // Realiza un updateOrCreate si $budgetId tiene un valor diferente de 'NO'
            BudgetExpense::updateOrCreate(
                ['operation_id' => $operationId, 'budget_id' => $budgetId, 'category_id' => $categoryId],
                // Puedes agregar aquí otros campos que desees actualizar o crear
            );
            session()->flash('message', __('Data Created/Updated Successfully'));
        }
    } else {
        // session()->flash('info', __('Invalid operation'));
    }
}



    public function delete($id)
    {
         $this->authorize('manage admin');
        Operation::find($id)->delete();
        session()->flash('message', 'Data Deleted Successfully.');
    }


}
