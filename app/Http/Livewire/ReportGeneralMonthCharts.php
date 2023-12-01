<?php

namespace App\Http\Livewire;
use App\Models\Operation;
use App\Models\MainCategories;
use App\Models\User;
use App\Models\Category;
use App\Models\Budget;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportGeneralMonthCharts extends Component
{
    public $years = [];
    public $categoryName,$categoryName2;
    public $selectedUser4;
    public $selectedMonth;
    public $selectedYear3;
    public $showChart4 = false;
    public $users;
    public $operationsFetchMonths;
    public $selectedMonthName;
    public $totalMonthAmount;
    public $totalMonthAmountCurrency;
  
    public $userNameSelected4;
    public $budget;
    public $budgetData;

public function months()
{
    $months = [];
    for ($i = 1; $i <= 12; $i++) {
        $monthName = Carbon::now()->month($i)->format('F');
        $months[] = [
            'number' => $i,
            'name' => $monthName,
        ];
    }
    return $months;
}

    public function mount()
    {
        
        $this->years = Operation::distinct()->pluck('operation_year');
         $this->users = User::orderBy('id', 'desc')->get();
       
       
         
    }

    public function render()
    {
        return view('livewire.report-general-month-charts');
    }


    

  // REPORT GENERAL MONTH CHART

public function updateMonthData()
{
    $this->updateMonthDataInternal();
    
}


private function updateMonthDataInternal()
{
    $this->userNameSelected4 = User::find($this->selectedUser4);
    $this->operationsFetchMonths = $this->fetchMonthData();
    
    $this->totalMonthAmount = $this->fetchTotalMonthAmount(); 
    $this->totalMonthAmountCurrency = $this->fetchTotalMonthAmountCurrency(); 
    
    $this->showChart4 = true;
    if ($this->selectedMonth) {
        $selectedDate = Carbon::create()->month($this->selectedMonth);
        $this->selectedMonthName = $selectedDate->format('F');
    }
}


private function fetchTotalMonthAmountCurrency()
{
    return $this->operationsFetchMonths->sum('operation_currency_total');
}

private function fetchTotalMonthAmount()
{
    return $this->operationsFetchMonths->sum('operation_currency_total');
}


private function fetchMonthData()
{
    // Crear la base de la consulta
    $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->join('statu_options', 'operations.operation_status', '=', 'statu_options.id')
        ->leftJoin('operation_subcategories', 'operation_subcategories.operation_id', '=', 'operations.id') 
        ->leftJoin('subcategories', 'operation_subcategories.subcategory_id', '=', 'subcategories.id');

    // Aplicar filtro por usuario si está seleccionado
    if ($this->selectedUser4) {
        $query->where('operations.user_id', $this->selectedUser4);
    }

    // Aplicar filtro por mes si está seleccionado
    if ($this->selectedMonth) {
        $query->whereMonth('operations.operation_date', $this->selectedMonth);
    }

    // Aplicar filtro por año si está seleccionado
    if ($this->selectedYear3) {
        $query->whereYear('operations.operation_date', $this->selectedYear3);
    }

    $this->budget = Budget::where('budget_month', $this->selectedMonth)
                      ->where('user_id', $this->selectedUser4)
                      ->first();

    $this->budgetData = $this->budget ? 'Budget Month ' . $this->budget->budget_currency_total . ' $' : 'Budget Month N/A';

 
    // Ejecutar la consulta y seleccionar columnas calculadas
   return $query->select(
            'operations.operation_amount',
            'operations.operation_currency',
            'operations.operation_currency_total',
            'categories.category_name as category_title',
            'statu_options.status_description as status_description',
            'operations.operation_status as operation_status',
            'operations.operation_description',
            'operations.operation_date',
            'operations.operation_currency_type',
            'main_categories.title as main_category_title',
            'subcategories.subcategory_name' 
        )
        ->orderBy('operations.id', 'desc')
        ->get();
}


public function resetFields4()
{
    $this->selectedUser4 = null;
    $this->selectedMonth = null;
    $this->selectedYear3 = null;
    $this->showChart4 = false;
}


}
