<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\StatuOptions;
use App\Models\Operation;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http; // <-- guzzle query api
use App\Models\CategoriesToAssign;
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
     ->where('users.id', auth()->id())
      ->where('categories.main_category_id', 1)
     ->where('operations.operation_description', 'like', '%' . $this->search . '%')
     ->select('operations.*', 'categories.category_name', 'statu_options.status_description')
     ->orderBy('operations.id', 'desc')
     ->paginate(10);

     $this->categoriesRender = Category::where('main_category_id', 1)
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
         $this->reset();
        $this->resetValidation(); 
    }

    private function resetInputFields(){
         $this->reset();
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

    Operation::updateOrCreate(['id' => $this->data_id], $validatedData);

    session()->flash('message', $this->data_id ? 'Data Updated Successfully.' : 'Data Created Successfully.');

    $this->closeModal();
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
    }
public function delete($id)
    {
         $this->authorize('manage admin');
        Operation::find($id)->delete();
        session()->flash('message', 'Data Deleted Successfully.');
    }

}