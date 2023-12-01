<?php

namespace App\Http\Livewire;
use App\Models\Operation;
use App\Models\MainCategories;
use App\Models\User;
use App\Models\Category;

use Livewire\Component;
use Carbon\Carbon;


class ReportGeneralBetweenCharts extends Component
{
    public $years = [];
    public $categoryName,$categoryName2;
    public $selectedYear3;
    public $selectedUser3;
    public $showChart3 = false;
    public $incomeData3 = [];
    public $expenseData3 = [];
    public $users;
    public $date_start;
    public $date_end;
    public $userNameSelected3;

    protected $listeners = ['userSelectedChartBetween'];

    public function userSelectedChartBetween($userId)
    {
        // Aquí puedes ejecutar la lógica que desees con el $userId
        $this->selectedUser3 = $userId;
        $this->updateBetweenData();
    }


    public function mount()
    {
        $this->years = Operation::distinct()->pluck('operation_year');
         $this->users = User::orderBy('id', 'desc')->get();
          
    }


    public function render()
    {
        return view('livewire.report-general-between-charts');
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

    if ($this->date_start && $this->date_end) {
        $query->whereBetween('operations.operation_date', [$this->date_start, $this->date_end]);
    } elseif ($this->date_start) {
        $query->whereDate('operations.operation_date', '>=', $this->date_start);
    } elseif ($this->date_end) {
        $query->whereDate('operations.operation_date', '<=', $this->date_end);
    }

    return $query
        ->whereMonth('operations.operation_date', $month)
        ->sum('operations.operation_currency_total');
}




public function resetFields3()
{
    $this->selectedUser3 = null;
   
    $this->date_start = null;
    $this->date_end = null;
    $this->showChart3 = false;
}
}
