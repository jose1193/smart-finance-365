<?php

namespace App\Http\Livewire;
use App\Models\Operation;
use App\Models\MainCategories;
use App\Models\User;
use App\Models\Category;

use Livewire\Component;
use Carbon\Carbon;


class ReportGeneralMainCharts extends Component
{
    public $years = [];
    public $categoryName,$categoryName2;
    public $selectedYear;
 
    public $selectedUser;
    
    
    
    public $showChart = false;
 
    public $incomeData = [];
    public $expenseData = [];
    
    public $users;
   
    public $isOpen = 0;
    
    protected $listeners = ['userSelectedChart','YearSelectedChart'];

    public function userSelectedChart($userId)
    {
        
        
        $this->selectedUser = $userId;
        $this->updateChartData();
    }

    public function YearSelectedChart($selectedYearId)
    {
       
        $this->selectedYear = $selectedYearId;
        $this->updateChartData();
    }
    
    public function mount()
    {
        $this->years = Operation::distinct()->pluck('operation_year');
         $this->users = User::orderBy('id', 'desc')->get();
          
    }


    public function render()
    {
        return view('livewire.report-general-main-charts');
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
        ->sum('operations.operation_currency_total');
}


//FUNCTION RESET FIELDS REPORT GENERAL
public function resetFields1()
{
    $this->selectedUser = null;
    $this->selectedYear = null;
   
    $this->showChart = false;
}



}
