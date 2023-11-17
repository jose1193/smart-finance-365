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
protected $listeners = ['render','delete']; 

public $subcategory_id;
public $showSubcategories = false;
public $subcategoryMessage;
public $selectedCategoryId;



    public function authorize()
{
    return true;
}

public function fetchData()
{
    // Hacer la solicitud HTTP a la API de monedas
    $response = Http::get('https://api.bluelytics.com.ar/v2/latest'); // Reemplaza con la URL correcta de la API
    $this->data2 = $response->json();
}

public function mount()
{
     $this->fetchData(); // Llama a la función fetchData para obtener los datos
    // Define el valor por defecto en la propiedad
    $this->operation_currency = $this->data2['blue']['value_sell'];
    
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
        DB::raw('COALESCE(subcategories.subcategory_name, categories.category_name) as display_name')
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
              });
    })
    ->orderBy('id', 'asc')
    ->get();


    $this->statusOptionsRender = StatuOptions::where('main_category_id', 1)
                                  ->orderBy('id', 'asc')
                                  ->get();

        return view('livewire.incomes-operations', [
            'data' => $data]);
    }

    public function create()
    {
         $this->authorize('manage admin');
        $this->resetInputFields();
         $this->fetchData(); // Llama a la función fetchData para obtener los datos
    // Define el valor por defecto en la propiedad
    $this->operation_currency = $this->data2['blue']['value_sell'];
     $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->emit('modalOpened'); // Emitir un evento cuando el modal se abre
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
     // Para operation_currency_total, primero lo conviertes en un número decimal (float)
    $roundedValue = round($validatedData['operation_currency_total']);

    $numericValue2 = str_replace(['.', ','], '', $roundedValue);

    // Convierte la cadena en un número entero (sin decimales) para operation_amount
    $validatedData['operation_amount'] = (int)$numericValue;

   
    // Luego, lo redondeas al número entero más cercano
    $validatedData['operation_currency_total'] = (int)$numericValue2;

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

        session()->flash('message', 'User Assignments for Subcategories Updated Successfully.');
    } else {
        session()->flash('error', 'Category or Subcategories not found.');
    }

    $this->resetInputFields();
}




public function updatedOperationAmount()
{
    // Limpia la entrada de usuario para asegurarte de que solo contenga dígitos y un punto decimal
    $cleanedValue = preg_replace('/[^0-9.]/', '', $this->operation_amount);

    // Verifica si el valor es un número
    if (is_numeric($cleanedValue) && is_numeric($this->operation_currency)) {
        // Realiza la operación de división
        $result = round((float)$cleanedValue / $this->operation_currency);
        $this->operation_currency_total = $result;
    } else {
        // Maneja el caso en el que los valores no sean numéricos, por ejemplo, asignando un valor predeterminado o mostrando un mensaje de error.
        $this->operation_currency_total = 0; // O cualquier otro valor predeterminado
    }
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