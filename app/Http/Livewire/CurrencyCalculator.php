<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http; // <-- guzzle query api

class CurrencyCalculator extends Component
{
    public $data; 

public function fetchData()
{
    // Hacer la solicitud HTTP a la API de monedas
    $response = Http::get('https://api.bluelytics.com.ar/v2/latest'); // Reemplaza con la URL correcta de la API
    $this->data = $response->json();
}

public function render()
{
    $this->fetchData(); // Llama a la función fetchData para obtener los datos

    return view('livewire.currency-calculator');
}

    
public function Calculator()
{
    $this->fetchData(); // Llama a la función fetchData para obtener los datos

    return view('livewire.calculator', [
        'data' => $this->data
    ]);
}


}
