<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\SubcategoryToAssign;
use App\Models\OperationSubcategories;
use App\Models\CategoriesToAssign;
use App\Models\StatuOptions;
use App\Models\Operation;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http; // <-- guzzle query api
use Illuminate\Support\Facades\DB;

class IncomesOperationsAdmin extends Component
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
protected $listeners = ['render','delete','currencyChanged','userSelected7','userSelectedUpdated' => 'updateSelect','updateDataIncomeOperations' => 'updateData']; 

public $subcategory_id = [];
public $showSubcategories = false;
public $subcategoryMessage;
public $selectedCategoryId;

public $selectedCurrencyFrom;
public $listCurrencies;
public $quotes;
public $operation_currency_type;

public $registeredSubcategoryItem;

public $selectedUser7;
public $users;
public $showData = true;
public $data;
public $user_selected;

public $selectedCurrencyFromARS;

 protected $rules = [
        'user_selected' => 'required', // Agrega las reglas de validación que necesites
    ];

    public function authorize()
{
    return true;
}

public function userSelected7($userId)
    {
        $this->selectedUser7 = $userId;
         $this->updateData();
        
    }

    
 // CHANGE USER CATEGORY ASSIGNED
public function updateCategoryUser()
{
    
    $this->refreshCategories($this->user_selected);
    $this->category_id = null;
   $this->showSubcategories = false; 
    $this->emit('userSelectedUpdated', $this->user_selected);
     
}

public function updateSelect($userSelected)
{
    $this->user_selected = $userSelected;
  
    $this->refreshCategories(); 
    
}

public function refreshCategories()
{
    
    $this->updateData();
    $assignedCategories = CategoriesToAssign::where('user_id_assign', $this->user_selected)
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
        ->orderBy('id', 'asc')
        ->get();
       
  
}
 // END FUNCTION CHANGE USER CATEGORY ASSIGNED

  // SELECT SUBCATEGORY_ID TO REGISTERSUBCATEGORYITEM

    public function updateSubCategoryUser()
    {
         
        // Asigna el valor de subcategory_id a registeredSubcategoryItem
        $this->registeredSubcategoryItem = $this->subcategory_id;
    }

     // END SELECT SUBCATEGORY_ID TO REGISTERSUBCATEGORYITEM

     
    public function mount()
    
    { 
        $this->users = User::orderBy('id', 'desc')->get();
        $this->updateData();
       
    }


     public function render()
    {
        
        return view('livewire.incomes-operations-admin');
    }


    // SHOW USER DATA TABLE
public function updateData() 

{
$this->emit('reinitDataTable');
$this->showData= true;
$this->data = $this->updateDataIncomeOperations();


}
public function updateDataIncomeOperations()
{
    $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('users', 'operations.user_id', '=', 'users.id')
        ->join('main_categories', 'main_categories.id', '=', 'categories.main_category_id')
        ->join('statu_options', 'operations.operation_status', '=', 'statu_options.id')
        ->leftJoin('operation_subcategories', 'operation_subcategories.operation_id', '=', 'operations.id')
        ->leftJoin('subcategories', 'operation_subcategories.subcategory_id', '=', 'subcategories.id')
        ->where('categories.main_category_id', 1)
        ->where('operations.operation_description', 'like', '%' . $this->search . '%')
        ->select(
            'operations.*', 'users.username',
            'categories.category_name',
            'statu_options.status_description',
            DB::raw('COALESCE(subcategories.subcategory_name, "N/A") as display_name')
        )
        ->orderBy('operations.id', 'desc');

    // Add condition for the selected user only if configured
    if ($this->selectedUser7) {
        $this->applyUserConditions($query);
    } else {
        // If no user is selected, get all users
        $this->categoriesRender = $this->getAllCategories();
        $this->statusOptionsRender = $this->getAllStatusOptions();
    }

    return $query->get();
}

private function applyUserConditions($query)
{
    $query->where('users.id', $this->selectedUser7);

    $assignedCategories = CategoriesToAssign::where('user_id_assign', $this->selectedUser7)
        ->pluck('category_id');

    $this->categoriesRender = $this->getUserCategories($assignedCategories);

    $this->statusOptionsRender = $this->getAllStatusOptions();
}

private function getAllCategories()
{
    return Category::where('main_category_id', 1)
        ->orderBy('id', 'asc')
        ->get();
}

private function getUserCategories($assignedCategories)
{
    return Category::where('main_category_id', 1)
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
        ->orderBy('id', 'asc')
        ->get();
}

private function getAllStatusOptions()
{
    return StatuOptions::where('main_category_id', 1)
        ->orderBy('id', 'asc')
        ->get();
}




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
    $this->emit('modalOpenedAutonumeric2');
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
        $this->updateData();
        $this->openModal();
         $this->user_selected = $this->selectedUser7;
    }

    public function openModal()
    {
        $this->isOpen = true;
         $this->emit('modalOpenedAutonumeric2'); 
        $this->emit('modalOpened'); // Emitir un evento cuando el modal se abre
        $this->fetchDataCurrencies();
       
     

        
       
    }

    public function closeModal()
    {
        $this->isOpen = false;
         $this->resetInputFields();
        
    }

     private function resetInputFields(){
        $this->reset([
        'operation_description',
        'selectedCurrencyFrom',
        'operation_amount',
        'operation_currency',
        'operation_currency_type',
        'operation_currency_total',
        'operation_date',
        'category_id',
        
        'registeredSubcategoryItem',
        'operation_status',
        'user_selected',
       
    ]);
    $this->showSubcategories = false; 
         $this->resetValidation(); 
           $this->updateData();
    }

       
    public function edit($id)
    {
        $this->authorize('manage admin');
        $list = Operation::findOrFail($id);
        $this->data_id = $id;
        $this->operation_description = $list->operation_description;
        $this->operation_amount = number_format($list->operation_amount, 2, '.', ',');
        $this->operation_currency = $list->operation_currency;
        $this->operation_currency_total = number_format($list->operation_currency_total, 2, '.', ',');
        $this->operation_status = $list->operation_status;
        $this->category_id = $list->category_id;
        $this->selectedCurrencyFrom = $list->operation_currency_type;
        $this->operation_currency_type=$list->operation_currency_type;
        $this->operation_date =  Carbon::parse($list->operation_date)->format('d/m/Y');
        $this->openModal();
        $this->updatedOperationAmount();
       
        $this->selectedCategoryId = $list->category_id;
        $this->showSubcategories = true;

        $this->user_selected = $list->user_id; 


        $registeredSubcategory = $list->operationSubcategories->first();
        $this->updatedCategoryId($list->category_id, optional($registeredSubcategory)->subcategory_id);
        
        $this->emit('reinitDataTable');
        $this->updateData();

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
        'user_selected' => 'required',
        
    ];
    
    $validatedData = $this->validate($validationRules);

  
    // Agregar user_id al array validado
    $validatedData['user_id'] = $this->user_selected ?? $this->selectedUser7;


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
   
    // SEND UPDATE USER  RENDER
    $this->selectedUser7 = $validatedData['user_id']; 


    $operation = Operation::updateOrCreate(['id' => $this->data_id], $validatedData);

    session()->flash('message', $this->data_id ? 'Data Updated Successfully.' : 'Data Created Successfully.');
    
    
    $this->SubcategoryOperationAssignment($operation);



    $this->closeModal();

    // UPDATE USER  RENDER
   $this->emit('updateDataIncomeOperations');
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
     $this->updateData();
    $userId = $this->selectedUser7;

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
        : 'The category has no subcategories. Please follow the registration process.';
    
    // Configura la subcategoría registrada como seleccionada
    $this->registeredSubcategoryItem = $registeredSubcategoryId;
}



    public function delete($id)
    {
         $this->authorize('manage admin');
        Operation::find($id)->delete();
        session()->flash('message', 'Data Deleted Successfully.');
         $this->updateData();
    }


}