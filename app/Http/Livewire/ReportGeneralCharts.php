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
    public $selectedUser;
    public $selectedCategoryId;
    public $showChart = false;
    public $showChart2 = false;
    public $showChart3 = false;
    public $incomeData = [];
    public $expenseData = [];
    public $ArrayCategories = [];
    public $users;
    public $categoriesRender;
    public $isOpen = 0;
    
    public $date_start;
    public $date_end;
  
    public $totalGeneral = 0;
    public $categoryNameSelected;

    
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
    
   $this->incomeData = [];
    $this->expenseData = [];

    for ($i = 1; $i <= 12; $i++) {
        // Consulta de ingresos
        $incomeData[] = $this->fetchBetweenData(1, $i);

        // Consulta de gastos
        $expenseData[] = $this->fetchBetweenData(2, $i);
    }

    $this->incomeData = $incomeData;
    $this->expenseData = $expenseData;

    $this->categoryName = MainCategories::where('id', 1)->value('title');
    $this->categoryName2 = MainCategories::where('id', 2)->value('title');
    $this->showChart3 = true;
}

private function fetchBetweenData($mainCategoryId, $month)
{
   $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', $mainCategoryId);

    if ($this->selectedUser) {
        $query->where('operations.user_id', $this->selectedUser);
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

    $this->categoryName = MainCategories::where('id', 1)->value('title');
    $this->categoryName2 = MainCategories::where('id', 2)->value('title');
    $this->showChart2 = true;
}



private function fetchCategoriesData($mainCategoryId, $month)
{
    $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', $mainCategoryId);

    if ($this->selectedUser) {
        $query->where('operations.user_id', $this->selectedUser);
    }

    if ($this->selectedCategoryId) {
        $query->where('operations.category_id', $this->selectedCategoryId);
    }

    if ($this->selectedYear) {
        $query->whereYear('operations.operation_date', $this->selectedYear);
    }

     $this->categoryNameSelected = Category::find($this->selectedCategoryId);
     

    return $query
        ->whereMonth('operations.operation_date', $month)
        ->sum('operations.operation_amount');
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
