<?php

namespace App\Http\Livewire;
use App\Models\Category;
use App\Models\Operation;
use App\Models\Subcategory;
use App\Models\SubcategoryToAssign;
use App\Models\OperationSubcategories;
use App\Models\CategoriesToAssign;
use App\Models\StatuOptions;
use Livewire\WithPagination;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; // <-- guzzle query api

class DashboardTable extends Component
{
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

public $registeredSubcategoryItem;
public $user_id;
public $registeredSubcategory;

public $selectedCurrencyFromARS;

    public function authorize()
{
    return true;
}


    public function render()
    {
        
      $dataQuery = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
    ->join('users', 'operations.user_id', '=', 'users.id')
    ->join('main_categories', 'main_categories.id', '=', 'categories.main_category_id')
    ->join('statu_options', 'operations.operation_status', '=', 'statu_options.id')
    ->where(function ($query) {
        $query->where('operations.operation_description', 'like', '%' . $this->search . '%')
            ->orWhere('categories.category_name', 'like', '%' . $this->search . '%')
            ->orWhere('users.name', 'like', '%' . $this->search . '%');
    })
    ->select('operations.*', 'main_categories.title','categories.category_name', 'statu_options.status_description', 'users.name')
    ->orderBy('operations.id', 'desc');

        if (auth()->user()->hasRole('Admin')) {
    $data = $dataQuery->paginate(10);
        } elseif (auth()->user()->hasRole('User')) {
    $data = $dataQuery->where('users.id', auth()->user()->id)->paginate(10);
        } else {
    // Lógica para otros roles si es necesario
        }

    $this->statusOptionsRender = StatuOptions::orderBy('id', 'asc')->get();
       
        return view('livewire.dashboard-table', [
            'data' => $data]);
    }


    // Categories assigned to users
    private function getAssignedCategoriesForUser($userId)
{
    $assignedCategories = CategoriesToAssign::where('user_id_assign', $userId)
        ->pluck('category_id');

    return Category::whereIn('categories.id', $assignedCategories)
        ->orWhere(function ($query) use ($assignedCategories) {
            $query->whereNotIn('categories.id', $assignedCategories)
                ->whereNotExists(function ($subQuery) {
                    $subQuery->select(DB::raw(1))
                        ->from('categories_to_assigns')
                        ->whereColumn('categories_to_assigns.category_id', 'categories.id');
                });
        })
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->select('categories.id', 'categories.category_name', 'main_categories.title as main_category_title')
        ->orderBy('categories.main_category_id', 'asc') // Ordenar por categoría principal
        ->orderBy('categories.id', 'asc') // Luego por ID de categoría
        ->get();
}


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
    $this->emit('modalOpenedAutonumericDashboard');
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
        $this->emit('modalOpenedAutonumericDashboard');
        $this->fetchDataCurrencies();
        
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields(){
         $this->reset();
         $this->resetValidation();
    }


public function store()
{
   
$fechaRecibida = $this->operation_date; 
$fechaCarbon = Carbon::createFromFormat('d/m/Y', $fechaRecibida);
$fechaEnFormato= $fechaCarbon->format('Y-m-d');
$this->operation_date = $fechaEnFormato;


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
   
   
     

    $operation = Operation::updateOrCreate(['id' => $this->data_id], $validatedData);

    session()->flash('message', $this->data_id ? 'Data Updated Successfully.' : 'Data Created Successfully.');
    
    
    $this->SubcategoryOperationAssignment($operation);



    $this->closeModal();
   
}



public function SubcategoryOperationAssignment(Operation $operation)
{
   
      // Si $this->subcategory_id es una cadena, conviértela en un array
    $subcategoryIds = is_array($this->subcategory_id) ? $this->subcategory_id : explode(',', $this->subcategory_id);

    $subcategories = [];

    $subcategories = Subcategory::whereIn('id', $subcategoryIds)->get();

    if ($operation) {
        // Eliminar las asignaciones existentes en OperationSubcategories solo si hay nuevas subcategorías
        if ($subcategories->isNotEmpty()) {
            // Obtén las subcategorías existentes para esta operación
            $existingSubcategories = OperationSubcategories::where('operation_id', $operation->id)->pluck('subcategory_id')->toArray();

            // Determina las subcategorías que deben eliminarse
            $subcategoriesToDelete = array_diff($existingSubcategories, $subcategoryIds);
            
            // Elimina las subcategorías que ya no están presentes
            if (!empty($subcategoriesToDelete)) {
                OperationSubcategories::where('operation_id', $operation->id)
                    ->whereIn('subcategory_id', $subcategoriesToDelete)
                    ->delete();
            }

            // Agrega o actualiza las asignaciones en OperationSubcategories
            foreach ($subcategories as $subcategory) {
                $subcategoryId = $subcategory->id;
                $operationId = $operation->id;

                OperationSubcategories::updateOrCreate(
                    [
                        'operation_id' => $operationId,
                        'subcategory_id' => $subcategoryId,
                        'user_id_subcategory' => auth()->user()->id,
                    ]
                );
            }

            session()->flash('message', __('Data Updated Successfully'));
        } else {
            // Si no se proporcionan nuevas subcategorías, simplemente eliminar las existentes
            OperationSubcategories::where('operation_id', $operation->id)->delete();
            
           
        }
    } else {
        // Manejar el caso en el que $operation es nulo
        session()->flash('error', __('Invalid operation'));
    }

    $this->resetInputFields();
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
        
        $this->registeredSubcategory = $list->operationSubcategories->first();
        $this->user_id = $list->user_id;
        $this->updatedCategoryId($list->category_id);

        // Obtener las categorías asignadas al usuario actual
         $this->categoriesRender = $this->getAssignedCategoriesForUser($this->user_id);

    }


public function updatedCategoryId($value)
    {

    // Lógica para obtener las subcategorías asignadas al usuario autenticado en la categoría seleccionada
    $userSubcategories = SubcategoryToAssign::where('user_id_subcategory', $this->user_id)
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
   $this->registeredSubcategoryItem = $this->registeredSubcategory ? $this->registeredSubcategory->subcategory_id : null;

}

 public function delete($id)
{
    $operation = Operation::find($id);
    $description = $operation->operation_description; // Obteniendo la descripción antes de eliminarla
    $operation->delete();
    session()->flash('message', $description. ' Deleted Successfully' );
}

}
