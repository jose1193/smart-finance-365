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
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http; // <-- guzzle query api
use Illuminate\Support\Facades\DB;

class IncomesOperations extends Component
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

public $subcategory_id;
public $showSubcategories = false;
public $subcategoryMessage;
public $selectedCategoryId;

public $selectedCurrencyFrom;
public $listCurrencies;
public $quotes;
public $operation_currency_type;


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
    ->where('users.id', auth()->id())
    ->where('categories.main_category_id', 1)
    ->where('operations.operation_description', 'like', '%' . $this->search . '%')
    ->select(
        'operations.*',
        'categories.category_name',
        'statu_options.status_description',
        DB::raw('COALESCE(subcategories.subcategory_name, "N/A") as display_name')
    )
    ->orderBy('operations.id', 'desc')
    ->paginate(10);

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
    ->orderBy('id', 'asc')
    ->get();



    $this->statusOptionsRender = StatuOptions::where('main_category_id', 1)
                                  ->orderBy('id', 'asc')
                                  ->get();

        return view('livewire.incomes-operations', [
            'data' => $data]);
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
        $this->operation_currency = number_format($this->data2['blue']['value_sell'], 0,".");
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
    
}



// CALCULATE CURRENCY
public function updatedOperationAmount()
{
    // Reemplaza comas por nada para manejar el formato con comas
    $cleanedValue = str_replace(',', '', $this->operation_amount);

    // Reemplaza el espacio por nada para manejar el formato con espacios
    $cleanedCurrency = str_replace(' ', '', $this->operation_currency);

    // Verifica si el valor es un número
    if (is_numeric($cleanedValue) && is_numeric($cleanedCurrency) && $cleanedCurrency != 0) {
        // Realiza la operación de división y redondeo después de la división
        $result = $cleanedValue / $cleanedCurrency;

        // Aplica la condición: si el resultado es menor a 1, lo deja así, de lo contrario, lo redondea
        if ($result < 1) {
            $this->operation_currency_total = number_format($result, 2, '.', '');
        } else {
            $this->operation_currency_total = number_format($result);
        }
    } else {
        // Maneja el caso en el que los valores no sean numéricos, o la moneda sea cero, por ejemplo, asignando un valor predeterminado o mostrando un mensaje de error.
        $this->operation_currency_total = number_format($cleanedValue, 0, '.', '.'); // O cualquier otro valor predeterminado
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
        $list = Operation::findOrFail($id);
        $this->data_id = $id;
        $this->operation_description = $list->operation_description;
        $this->operation_amount = number_format($list->operation_amount, 0, '.', ',');
        $this->operation_currency = $list->operation_currency;
        $this->operation_currency_total = number_format($list->operation_currency_total, 2, '.', ',');
        $this->operation_status = $list->operation_status;
        $this->category_id = $list->category_id;
     
        $this->openModal();
        $this->updatedOperationAmount();
       
        $this->selectedCategoryId = $list->category_id;
        $this->showSubcategories = true;
        $this->updatedCategoryId($list->category_id);

    }


public function store()
{
    

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

    // Calcular el mes y el año a partir de expense_date usando Carbon
    $operationDate = \Carbon\Carbon::createFromFormat('Y-m-d', $validatedData['operation_date']);
    $validatedData['operation_month'] = $operationDate->format('m');
    $validatedData['operation_year'] = $operationDate->format('Y');

    // Elimina cualquier carácter no numérico, como comas y puntos
    $numericValue = str_replace(['.', ','], '', $validatedData['operation_amount']);

    

    // Asigna la cadena, sin convertirla a un entero
    $validatedData['operation_amount'] = $numericValue;

   
     

    $operation = Operation::updateOrCreate(['id' => $this->data_id], $validatedData);

    session()->flash('message', $this->data_id ? 'Data Updated Successfully.' : 'Data Created Successfully.');
    
    
    
      // Llamada a la función para asignar usuarios a operaciones subcategorías
    $this->SubcategoryOperationAssignment($operation);


    $this->closeModal();
    $this->resetInputFields();
}


public function SubcategoryOperationAssignment(Operation $operation)
{
    // Obtener la subcategoría asociada a la categoría
    $subcategories = Subcategory::find($this->subcategory_id);

    if ($operation && $subcategories) {
        if (in_array('all', $this->subcategory_id) || empty($this->subcategory_id)) {
            // Eliminar las asignaciones existentes en OperationSubcategories
            OperationSubcategories::where('operation_id', $operation->id)->delete();
        } else {
            // Obtener las subcategorías asociadas al usuario autenticado
            $userSubcategories = SubcategoryToAssign::join('subcategories', 'subcategory_to_assigns.subcategory_id', '=', 'subcategories.id')
                ->where('subcategory_to_assigns.user_id_subcategory', auth()->user()->id)
                ->whereIn('subcategory_to_assigns.subcategory_id', $subcategories->pluck('id'))
                ->pluck('subcategory_to_assigns.subcategory_id');

            foreach ($subcategories as $subcategory) {
                $subcategoryId = $subcategory->id;

                // Verificar si la subcategoría está asignada al usuario
                if ($userSubcategories->contains($subcategoryId)) {
                    // Aquí se obtienen los valores necesarios de la instancia de $operation
                    $operationId = $operation->id;

                    // Registrar en OperationSubcategories
                    OperationSubcategories::updateOrCreate(
                        [
                            'operation_id' => $operationId,
                            'subcategory_id' => $subcategoryId,
                            'user_id_subcategory' => auth()->user()->id,
                        ]
                    );
                }
            }
        }

        session()->flash('message', 'Data Created Successfully.');
    } else {
        session()->flash('error', 'Category or Subcategories not found.');
    }

    $this->resetInputFields();
}




public function updatedCategoryId($value)
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
}



    public function delete($id)
    {
         $this->authorize('manage admin');
        Operation::find($id)->delete();
        session()->flash('message', 'Data Deleted Successfully.');
    }


}