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

    public $main_category_id;
    public $date_start;
    public $date_end;
    public $mainCategoriesRender;

    public $topTenOperations;

    public $SelectMainCurrencyTypeRender = 'USD';

    public $report_date;
    public $selectedMonthNameEs;
    public $mainCurrencyTypeRender;

    protected $listeners = ['userSelected4','MonthSelected','YearSelected3'];

    public $MonthlyChart1,$MonthlyChart2,$MonthlyChart3;

    public function userSelected4($userId)
    {
        // Aquí puedes ejecutar la lógica que desees con el $userId
        $this->selectedUser4 = $userId;
         $this->mainCurrencyTypeRender = Operation::where('user_id', $userId)
    ->where('operation_currency_type', '!=', 'USD')
    ->distinct()
    ->pluck('operation_currency_type');

        $this->updateMonthData();
    }

     public function MonthSelected($selectedMonthId)
    {
       
        $this->selectedMonth = $selectedMonthId;
        $this->updateMonthData();
    }


    public function YearSelected3($selectedYear3Id)
    {
       
        $this->selectedYear3 = $selectedYear3Id;
        $this->updateMonthData();
    }

public function months()
{
    $currentYear = Carbon::now()->year;
    $months = [];

    for ($i = 1; $i <= 12; $i++) {
        $dateInMonth = Carbon::create($currentYear, $i, 1);
        $monthName = $dateInMonth->translatedFormat('F');


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
       $this->mainCategoriesRender = MainCategories::orderBy('id', 'asc')->get();
       
         
    }

    public function render()
    {
        return view('livewire.report-general-month-charts');
    }


    
  public function updateMonthData()
{
    $this->emit('initializeFlatpickr');
    $this->updateMonthDataInternal();
    $this->topTenOperations = $this->fetchTopOperations(10);
    $this->MonthlyChart1 = 'MonthChart1-' . uniqid();
    $this->MonthlyChart2 = 'MonthChart2-' . uniqid();
    $this->MonthlyChart3 = 'MonthChart3-' . uniqid();
}

private function updateMonthDataInternal()
{
    $this->userNameSelected4 = User::find($this->selectedUser4);
    $this->operationsFetchMonths = $this->fetchMonthData();

    $this->showChart4 = true;

    
    if ($this->selectedMonth) {
    $selectedDate = Carbon::create()->month($this->selectedMonth);
    $this->selectedMonthName = $selectedDate->translatedFormat('F');

    $this->selectedMonthNameEs = Carbon::create()->month($this->selectedMonth)->locale('es')->isoFormat('MMMM');

}

$now = Carbon::now('America/Argentina/Buenos_Aires');
$this->report_date =  $now->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');
}



private function buildBaseQuery()
{
    return Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id');
}

private function applyFiltersToQuery($query)
{
    if ($this->selectedUser4) {
        $query->where('operations.user_id', $this->selectedUser4);
    }

    if ($this->selectedMonth) {
        $query->whereMonth('operations.operation_date', $this->selectedMonth);
    }

    if ($this->selectedYear3) {
        $query->whereYear('operations.operation_date', $this->selectedYear3);
    }

    if ($this->date_start && $this->date_end) {
        $query->whereBetween('operations.operation_date', [$this->date_start, $this->date_end]);
    } elseif ($this->date_start) {
        $query->whereDate('operations.operation_date', '>=', $this->date_start);
    } elseif ($this->date_end) {
        $query->whereDate('operations.operation_date', '<=', $this->date_end);
    }

    if ($this->main_category_id !== null && $this->main_category_id !== '') {
        $query->where('main_categories.id', $this->main_category_id);
    }

    // Apply currency type filter if SelectMainCurrencyTypeRender is set and not 'USD'
    if ($this->SelectMainCurrencyTypeRender && $this->SelectMainCurrencyTypeRender !== 'USD') {
        $query->where('operations.operation_currency_type', $this->SelectMainCurrencyTypeRender);
    }

    return $query;
}

private function fetchData($limit = null)
{
    $query = $this->buildBaseQuery();
    $query = $this->applyFiltersToQuery($query);

    $this->budget = $this->fetchBudgetData();
    $this->budgetData = $this->fetchBudgetDataString();

    $query->select(
        'categories.category_name as category_title',
        'main_categories.title as main_category_title',
        'categories.main_category_id as main_category_id'
    );

    if ($this->SelectMainCurrencyTypeRender === 'USD') {
        $query->selectRaw('SUM(operations.operation_currency_total) as total_currency');
    } else {
        $query->selectRaw('SUM(operations.operation_amount) as total_amount');
    }

    $query->groupBy('categories.category_name', 'main_categories.title', 'categories.main_category_id');

    if ($limit !== null) {
        if ($this->SelectMainCurrencyTypeRender === 'USD') {
            $query->orderBy('total_currency', 'desc')->limit($limit);
        } else {
            $query->orderBy('total_amount', 'desc')->limit($limit);
        }
    }

    return $query->get();
}



private function fetchMonthData()
{
    return $this->fetchData();
}

private function fetchTopOperations($limit)
{
    return $this->fetchData($limit);
}



private function fetchBudgetData()
{
    $query = Budget::where('budget_month', $this->selectedMonth)
                 ->where('user_id', $this->selectedUser4)
                 ->where('budget_year', $this->selectedYear3);

    // Aplicar el filtro del tipo de moneda si SelectMainCurrencyTypeRender está establecido y no es 'USD'
    if ($this->SelectMainCurrencyTypeRender && $this->SelectMainCurrencyTypeRender !== 'USD') {
        $query->where('budget_currency_type', $this->SelectMainCurrencyTypeRender);
    }

    return $query->first();
}

private function fetchBudgetDataString()
{
    $budget = $this->fetchBudgetData();
    
    if ($budget) {
        $budgetAmount = $budget->budget_operation ?? $budget->budget_currency_total;
        return $budgetAmount;
    }

    return 'Budget N/A';
}


public function resetFields4()
{
    $this->selectedUser4 = null;
    $this->selectedMonth = null;
    $this->selectedYear3 = null;
    $this->main_category_id = null;
    $this->date_start = null;
    $this->date_end = null;
    $this->showChart4 = false;
}


}