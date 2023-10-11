<?php

namespace App\Http\Livewire;
use App\Models\Category;
use App\Models\Operation;
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
    ->where(function ($query) {
        $query->where('operations.operation_description', 'like', '%' . $this->search . '%')
            ->orWhere('categories.category_name', 'like', '%' . $this->search . '%')
            ->orWhere('users.name', 'like', '%' . $this->search . '%');
    })
    ->select('operations.*', 'categories.category_name', 'statu_options.status_description', 'users.name')
    ->orderBy('operations.id', 'desc')
    ->paginate(10);



   $this->categoriesRender = Category::join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
    ->orderBy('categories.id', 'asc')
    ->select('categories.id', 'categories.category_name', 'main_categories.title as main_category_title')
    ->get();


    $this->statusOptionsRender = StatuOptions::orderBy('id', 'asc')->get();
       
        return view('livewire.dashboard-table', [
            'data' => $data]);
    }

    public function create()
    {
         $this->authorize('manage admin');
        $this->resetInputFields();
        $this->openModal();
         $this->fetchData(); // Llama a la función fetchData para obtener los datos
    // Define el valor por defecto en la propiedad
    $this->operation_currency = $this->data2['blue']['value_sell'];
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields(){
         $this->reset();
    }

       
public function store()
{
    $this->authorize('manage admin');

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


public function edit($id)
    {
         $this->authorize('manage admin');
        $list = Operation::findOrFail($id);
        $this->data_id = $id;
        $this->operation_description = $list->operation_description;
       $this->operation_amount = number_format($list->operation_amount, 2, '.', ',');
         $this->operation_currency = number_format($list->operation_currency, 2, '.', ',');
        $this->operation_currency_total = number_format($list->operation_currency_total, 2, '.', ',');
          $this->operation_status = $list->operation_status;
         $this->category_id = $list->category_id;
     
        $this->openModal();
    }
public function delete($id)
    {
         $this->authorize('manage admin');
        Operation::find($id)->delete();
        session()->flash('message', 'Data Deleted Successfully.');
    }

}
