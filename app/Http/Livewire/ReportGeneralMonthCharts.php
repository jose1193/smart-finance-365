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

    protected $listeners = ['userSelected4','MonthSelected','YearSelected3'];

    public function userSelected4($userId)
    {
        // Aquí puedes ejecutar la lógica que desees con el $userId
        $this->selectedUser4 = $userId;
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
    return $this->fetchTotalMonthAmountCurrency();
}

private function buildBaseQuery()
{
    return Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->join('statu_options', 'operations.operation_status', '=', 'statu_options.id')
        ->leftJoin('operation_subcategories', 'operation_subcategories.operation_id', '=', 'operations.id')
        ->leftJoin('subcategories', 'operation_subcategories.subcategory_id', '=', 'subcategories.id');
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

    return $query;
}

private function fetchBudgetData()
{
    return Budget::where('budget_month', $this->selectedMonth)
                 ->where('user_id', $this->selectedUser4)
                 ->where('budget_year', $this->selectedYear3)
                 ->first();
}

private function fetchBudgetDataString()
{
    $budget = $this->fetchBudgetData();
    return $budget ?  $budget->budget_currency_total  : 'Budget N/A';
}

private function fetchMonthData()
{
    $query = $this->buildBaseQuery();
    $query = $this->applyFiltersToQuery($query);

    $this->budget = $this->fetchBudgetData();
    $this->budgetData = $this->fetchBudgetDataString();

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
            'subcategories.subcategory_name',
             'categories.main_category_id as main_category_id', 
    )
    ->orderBy('operations.id', 'desc')
    ->get();
}

private function fetchTopOperations($limit)
{
    $query = $this->buildBaseQuery();
    $query = $this->applyFiltersToQuery($query);

    $this->budget = $this->fetchBudgetData();
    $this->budgetData = $this->fetchBudgetDataString();
    
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
            'subcategories.subcategory_name',
             'categories.main_category_id as main_category_id', 
    )
    ->orderBy('operations.operation_currency_total', 'desc')
    ->limit($limit)
    ->get();
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
