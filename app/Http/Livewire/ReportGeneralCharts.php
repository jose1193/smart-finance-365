<?php

namespace App\Http\Livewire;
use App\Models\Operation;
use App\Models\MainCategories;
use App\Models\User;
use App\Models\Category;

use Livewire\Component;
use Carbon\Carbon;


class ReportGeneralCharts extends Component
{
     
    public $years = [];
    public $categoryName,$categoryName2;
    public $selectedYear;
    public $selectedYear2;
    public $selectedYear3;
    public $selectedUser;
    public $selectedUser2;
    public $selectedUser3;
    public $selectedUser4;
    public $selectedCategoryId;
    public $selectedMonth;
    public $showChart = false;
    public $showChart2 = false;
    public $showChart3 = false;
    public $showChart4 = false;
    public $incomeData = [];
    public $expenseData = [];
    public $ArrayCategories = [];
    public $incomeData3 = [];
    public $expenseData3 = [];
    public $users;
    public $categoriesRender;
    public $isOpen = 0;
    
    public $date_start;
    public $date_end;
  
    public $totalGeneral = 0;
    public $categoryNameSelected;
    public $operationsFetchMonths;
    public $selectedMonthName;
    public $totalMonthAmount;
    public $totalMonthAmountCurrency;
    public $userNameSelected2;
    public $userNameSelected4;

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
           $this->categoriesRender = Category::join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->orderBy('categories.id', 'asc')
        ->select('categories.id', 'categories.category_name', 'main_categories.title as main_category_title')
        ->get();
    }

    public function render()
    {
         
        return view('livewire.report-general-charts');
    }

   
public function updateChartData()
{
    $this->updateChartDataInternal();

    // Emite un evento para notificar que los datos se han actualizado
    $this->emit('dataUpdated');
}


private function updateChartDataInternal()
{
    $this->incomeData = [];
    $this->expenseData = [];

    for ($i = 1; $i <= 12; $i++) {
        // Consulta de ingresos
        $incomeData[] = $this->fetchChartData(1, $i);

        // Consulta de gastos
        $expenseData[] = $this->fetchChartData(2, $i);
    }

    $this->incomeData = $incomeData;
    $this->expenseData = $expenseData;
    $this->categoryName = MainCategories::where('id', 1)->value('title');
    $this->categoryName2 = MainCategories::where('id', 2)->value('title');
    $this->showChart = true;
   
 
     
}

private function fetchChartData($mainCategoryId, $month)
{
    $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', $mainCategoryId);

    if ($this->selectedUser) {
        $query->where('operations.user_id', $this->selectedUser);
    }

    if ($this->selectedYear) {
        $query->whereYear('operations.operation_date', $this->selectedYear);
    }

    return $query
        ->whereMonth('operations.operation_date', $month)
        ->sum('operations.operation_amount');
}



 // REPORT GENERAL BETWEEN DATES
public function updateBetweenData()
{
    $this->updateBetweenDataInternal();
  

    
}


private function updateBetweenDataInternal()
{
    
   $this->incomeData3 = [];
    $this->expenseData3 = [];

    for ($i = 1; $i <= 12; $i++) {
        // Consulta de ingresos
        $incomeData3[] = $this->fetchBetweenData(1, $i);

        // Consulta de gastos
        $expenseData3[] = $this->fetchBetweenData(2, $i);
    }

    $this->incomeData3 = $incomeData3;
    $this->expenseData3 = $expenseData3;

    $this->categoryName = MainCategories::where('id', 1)->value('title');
    $this->categoryName2 = MainCategories::where('id', 2)->value('title');
    $this->showChart3 = true;
}

private function fetchBetweenData($mainCategoryId, $month)
{
   $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', $mainCategoryId);

    if ($this->selectedUser3) {
        $query->where('operations.user_id', $this->selectedUser3);
    }

    if ($this->date_start) {
        $query->whereDate('operations.operation_date', '>=', $this->date_start);
    }

    if ($this->date_end) {
        $query->whereDate('operations.operation_date', '<=', $this->date_end);
    }

    return $query
        ->whereMonth('operations.operation_date', $month)
        ->sum('operations.operation_amount');
}




  // REPORT GENERAL CATEGORIES DATES

public function updateCategoriesData()
{
    $this->updateCategoriesDataInternal();
   
}


private function updateCategoriesDataInternal()
{
    $this->ArrayCategories = [];
    $totalGeneral = 0;

    for ($i = 1; $i <= 12; $i++) {
        // Consulta de ingresos
        $income = $this->fetchCategoriesData(1, $i);

        // Consulta de gastos
        $expense = $this->fetchCategoriesData(2, $i);

        // Calcular la suma general
        $total = $income + $expense;
        $totalGeneral += $total;

        $this->ArrayCategories[] = [
            'month' => $i,
            'total' => $total,
        ];
    }

    $this->totalGeneral = $totalGeneral;
    $this->userNameSelected2 = User::find($this->selectedUser2);
    $this->categoryName = MainCategories::where('id', 1)->value('title');
    $this->categoryName2 = MainCategories::where('id', 2)->value('title');
    $this->showChart2 = true;
}



private function fetchCategoriesData($mainCategoryId, $month)
{
    $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', $mainCategoryId);

    if ($this->selectedUser2) {
        $query->where('operations.user_id', $this->selectedUser2);
    }

    if ($this->selectedCategoryId) {
        $query->where('operations.category_id', $this->selectedCategoryId);
    }

    if ($this->selectedYear2) {
        $query->whereYear('operations.operation_date', $this->selectedYear2);
    }

     $this->categoryNameSelected = Category::find($this->selectedCategoryId);
     

    return $query
        ->whereMonth('operations.operation_date', $month)
        ->sum('operations.operation_amount');
}


  // REPORT GENERAL MONTH TABLE

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
    return $this->operationsFetchMonths->sum('operation_amount');
}


 private function fetchMonthData()
    {
        $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
            ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->join('statu_options', 'operations.operation_status', '=', 'statu_options.id'); 
        if ($this->selectedUser4) {
            $query->where('operations.user_id', $this->selectedUser4);
        }

        if ($this->selectedMonth) {
            $query->whereMonth('operations.operation_date', $this->selectedMonth);
        }

        if ($this->selectedYear3) {
            $query->whereYear('operations.operation_date', $this->selectedYear3);
        }

        return $query->select(
        'operations.operation_amount',
        'operations.operation_currency',
         'operations.operation_currency_total',
        'categories.category_name as category_title',
        'statu_options.status_description as status_description',
         'operations.operation_status as operation_status',
        'operations.operation_description',
        'main_categories.title as main_category_title'
    )->orderBy('operations.id', 'desc')->get();

    }

//FUNCTION RESET FIELDS REPORT GENERAL
public function resetFields1()
{
    $this->selectedUser = null;
    $this->selectedYear = null;
   
    $this->showChart = false;
}


public function resetFields2()
{
    $this->selectedUser = null;
    $this->selectedYear = null;
    $this->selectedCategoryId = null;
    $this->showChart2 = false;
}



public function resetFields3()
{
    $this->selectedUser = null;
    $this->selectedYear = null;
    $this->date_start = null;
    $this->date_end = null;
    $this->showChart3 = false;
}


}