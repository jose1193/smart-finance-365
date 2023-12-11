<?php

namespace App\Http\Livewire;
use Livewire\Component;
use App\Models\Budget;
use App\Models\User;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http; // <-- guzzle query api
use Illuminate\Support\Facades\DB;


class Budgets extends Component
{
public $budget_operation, $budget_currency_type, $budget_currency, $budget_currency_total, $budget_date, $data_id;
public $search = '';
public $user_id;
public $users;
public $data2;

public $isOpen = 0;
protected $listeners = ['render','delete','currencyChanged']; 

public $selectedCurrencyFrom;
public $listCurrencies;
public $quotes;

use WithPagination;

public function authorize()
{
    return true;
}


    public function render()
{
    if (auth()->user()->hasRole('Admin')) {
    $this->users = User::orderBy('id', 'asc')->get();
    $this->user_id = $this->users->isNotEmpty() ? $this->users->first()->id : null;
    } 
    else {
    $this->user_id = auth()->id();
    }


    $query = Budget::join('users', 'budgets.user_id', '=', 'users.id')
        ->select('budgets.*', 'users.name')
        ->where(function ($query) {
            $query->where('budget_operation', 'like', '%' . $this->search . '%')
                ->orWhere('budget_date', 'like', '%' . $this->search . '%');
        });

    if (auth()->user()->hasRole('Admin')) {
        $data = $query->orderBy('budgets.id', 'desc')->paginate(10);
    } elseif (auth()->user()->hasRole('User')) {
        $data = $query->where('budgets.user_id', auth()->user()->id)
            ->orderBy('budgets.id', 'desc')
            ->paginate(10);
    }

    return view('livewire.budgets', [
        'data' => $data // Pasar los resultados paginados a la vista
    ]);
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
         $this->budget_currency = null;
        $this->emit('currencyChanged');
        return;
    }

        $this->fetchData();

        if ($this->selectedCurrencyFrom === 'Blue-ARS' && isset($this->data2['blue']['value_sell'])) {
        $this->budget_currency = $this->data2['blue']['value_sell'];
        $this->budget_currency_type = $this->selectedCurrencyFrom;
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
    $this->budget_currency = number_format($roundedValue, 2, '.', ' ');

    $this->budget_currency_type = $this->selectedCurrencyFrom;
}

            else {
                $this->budget_currency = 'N/A';
                $this->budget_currency_type = $this->selectedCurrencyFrom;
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
public function updatedBudgetOperation()
{
    // Reemplaza comas por nada para manejar el formato con comas
    $cleanedValue = str_replace(',', '', $this->budget_operation);

    // Reemplaza el espacio por nada para manejar el formato con espacios
    $cleanedCurrency = str_replace(' ', '', $this->budget_currency);

    // Verifica si el valor es un número
    if (is_numeric($cleanedValue) && is_numeric($cleanedCurrency) && $cleanedCurrency != 0) {
        // Realiza la operación de división y redondeo después de la división
        $result = $cleanedValue / $cleanedCurrency;

        // Aplica la condición: si el resultado es menor a 1, lo deja así, de lo contrario, lo redondea
        if ($result < 1) {
            $this->budget_currency_total = number_format($result, 2, '.', '');
        } else {
            $this->budget_currency_total = number_format($result);
        }
    } else {
        $this->budget_currency_total = $cleanedValue; // O cualquier otro valor predeterminado
    }
}



  //CLEAN UP VALUES AFTER EACH CURRENCY CHANGE
 public function currencyChanged()
{
 
        $this->budget_currency_total = null;
        $this->budget_operation = null;
    
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
         $this->updatedBudgetOperation();
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
    
    $list = Budget::findOrFail($id);

    // Obtener información del usuario asociado al presupuesto
    $user = User::find($list->user_id);

    $this->data_id = $id;
    $this->budget_currency_type = $list->budget_currency_type;
    $this->budget_operation = number_format($list->budget_operation, 0, '.', ',');
    $this->budget_currency = $list->budget_currency;
    $this->budget_currency_total = number_format($list->budget_currency_total, 2, '.', ',');
    $this->selectedCurrencyFrom = $list->budget_currency_type;
    $this->budget_date = Carbon::parse($list->budget_date)->format('d/m/Y');

    // Agregar datos del usuario al componente Livewire
    $this->user_id = $list->user_id;
    

    $this->openModal();
}

    
public function store()
{

    $fechaRecibida = $this->budget_date; 
    $fechaCarbon = Carbon::createFromFormat('d/m/Y', $fechaRecibida);
    $fechaEnFormato = $fechaCarbon->format('Y-m-d');
    $this->budget_date = $fechaEnFormato;

    // Validación personalizada para verificar el límite máximo por fecha
    $existingRecord = Budget::where('user_id', auth()->user()->id)
    ->whereYear('budget_date', $fechaCarbon->year)
    ->whereMonth('budget_date', $fechaCarbon->month)
    ->first();

    if ($existingRecord && !$this->data_id) {
    // Si se está intentando insertar y ya hay un registro para la fecha, emite un mensaje de error
    session()->flash('error', 'Solo se permite un registro por Mes.');
    $this->closeModal();
    }



    else {
    $validationRules = [
        'user_id' => 'required',
        'budget_currency_type' => 'required',
        'budget_operation' => 'required',
        'budget_currency' => 'required',
        'budget_currency_total' => 'required',
        'budget_date' => 'required|date',
    ];
    
    $validatedData = $this->validate($validationRules);

  
    // Calcular el mes y el año a partir de expense_date usando Carbon
    $operationDate = \Carbon\Carbon::createFromFormat('Y-m-d', $validatedData['budget_date']);
    $validatedData['budget_month'] = $operationDate->format('m');
    $validatedData['budget_year'] = $operationDate->format('Y');

    // Elimina cualquier carácter no numérico, como comas y puntos
    $numericValue = str_replace(['.', ','], '', $validatedData['budget_operation']);
    $numericValue2 = str_replace(['.', ','], '', $validatedData['budget_currency_total']);
    

    // Asigna la cadena, sin convertirla a un entero
    $validatedData['budget_operation'] = $numericValue;
    $validatedData['budget_currency_total'] = $numericValue2;

    $model = Budget::updateOrCreate(['id' => $this->data_id], $validatedData);

    session()->flash('message', $this->data_id ? 'Data Updated Successfully.' : 'Data Created Successfully.');


    $this->closeModal();
   
}
}

    public function delete($id)
    {
         $this->authorize('manage admin');
        Budget::find($id)->delete();
        session()->flash('message', 'Data Deleted Successfully.');
    }



}
