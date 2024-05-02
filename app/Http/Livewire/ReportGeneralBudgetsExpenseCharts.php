<?php

namespace App\Http\Livewire;
use App\Models\Operation;
use App\Models\MainCategories;
use App\Models\EmailManagement;
use App\Models\User;
use App\Models\Category;
use App\Models\Budget;
use App\Models\BudgetExpense;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportGeneralBudgetsExpenseCharts extends Component
{
    public $years = [];
    public $categoryName,$categoryName2;
    public $selectedUser6;
    public $selectedMonth2;
    public $selectedYear4;
    public $showChart6 = false;
    public $users;
    public $operationsFetchMonths;
    public $selectedMonthName2;
    public $totalMonthAmount;
    public $totalMonthAmountCurrency;
  
    public $userNameSelected5;
    public $budget;
    public $budgetData;
    protected $listeners = ['userSelectedChart6','MonthSelectedBudget2','YearSelectedChart5'];

    public $topTenBudgetExpenses;
    
    public $SelectMainCurrencyTypeRender = 'USD';
     
    public $report_date;
    public $selectedMonthNameEs;
    public $mainCurrencyTypeRender;

    public $BudgetExpenseChart1,$BudgetExpenseChart2,$BudgetExpenseChart3;

    public function userSelectedChart6($userId)
    {
       
        

        $this->selectedUser6 = $userId;
        $this->updateBudgetExpenseData();
    }

     public function MonthSelectedBudget2($selectedMonthId)
    {
       
        $this->selectedMonth2 = $selectedMonthId;
        $this->updateBudgetExpenseData();
    }


    public function YearSelectedChart5($selectedYear3Id)
    {
       
        $this->selectedYear4 = $selectedYear3Id;
        $this->updateBudgetExpenseData();
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
        
       
         
    }

    public function render()
    {
        return view('livewire.report-general-budgets-expense-charts');
    }

 // REPORT GENERAL BUDGET EXPENSE MONTHLY CHART

public function updateBudgetExpenseData()
{
    $this->updateBudgetExpenseDataInternal();
    $this->topTenBudgetExpenses = $this->fetchTopTenBudgetExpenses(10);
    
    $this->BudgetExpenseChart1 = 'BudgetExpenseChart1-' . uniqid();
    $this->BudgetExpenseChart2 = 'BudgetExpenseChart2-' . uniqid();
    $this->BudgetExpenseChart3 = 'BudgetExpenseChart3-' . uniqid();
}

private function updateBudgetExpenseDataInternal()
{
    $this->userNameSelected5 = User::find($this->selectedUser6);
    $this->operationsFetchMonths = $this->fetchMonthData();
   
    $this->showChart6 = true;

    if ($this->selectedMonth2) {
        $this->selectedMonthName2 = Carbon::create()->month($this->selectedMonth2)->format('F');

       $this->selectedMonthNameEs = Carbon::create()->month($this->selectedMonth2)->locale('es')->isoFormat('MMMM');

    }
    
    $now = Carbon::now('America/Argentina/Buenos_Aires');
    $this->report_date =  $now->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');

 $this->budget = Budget::where('budget_month', $this->selectedMonth2)
                      ->where('user_id', $this->selectedUser6)
                      ->whereYear('budget_date', $this->selectedYear4)
                      ->first();

                      // Verificar si $this->budget no es nulo
if ($this->budget) {
    // Aplicar el filtro del tipo de moneda si SelectMainCurrencyTypeRender está establecido y no es 'USD'
    if ($this->SelectMainCurrencyTypeRender && $this->SelectMainCurrencyTypeRender !== 'USD') {
        $this->budget = $this->budget->budget_currency_type === $this->SelectMainCurrencyTypeRender
            ? number_format($this->budget->budget_operation, 0) . ' '. $this->SelectMainCurrencyTypeRender
            : null; // o algún otro valor que indique que no debe mostrarse
    } else {
       $this->budget = number_format($this->budget->budget_currency_total, 0) . ' '. $this->SelectMainCurrencyTypeRender;
    }
} 


}


private function buildBaseQuery()
{
    return Operation::with(['category.mainCategories', 'status', 'operationSubcategories', 'budgetExpenses']) 
        ->join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', 2)
        ->join('statu_options', 'operations.operation_status', '=', 'statu_options.id')
        ->leftJoin('operation_subcategories', 'operation_subcategories.operation_id', '=', 'operations.id') 
        ->leftJoin('subcategories', 'operation_subcategories.subcategory_id', '=', 'subcategories.id') 
        ->join('budget_expenses', 'operations.id', '=', 'budget_expenses.operation_id')
        ->leftJoin('budgets', 'budgets.id', '=', 'budget_expenses.budget_id')
        ->leftJoin('categories as budget_category', 'budget_category.id', '=', 'budget_expenses.category_id');
}

private function applyFiltersToQuery($query)
{
    if ($this->selectedUser6) {
        $query->where('operations.user_id', $this->selectedUser6);
    }

    if ($this->selectedMonth2) {
        $query->whereMonth('operations.operation_date', $this->selectedMonth2);
    }

    if ($this->selectedYear4) {
        $query->whereYear('operations.operation_date', $this->selectedYear4);
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

    $this->mainCurrencyTypeRender = $this->buildBaseQuery()
        ->where('operations.user_id', $this->selectedUser6)
        ->where('operations.operation_currency_type', '!=', 'USD')
        ->distinct()
        ->pluck('operations.operation_currency_type');


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

private function fetchTopTenBudgetExpenses($limit)
{
    return $this->fetchData($limit);
}






public function resetFields6()
{
    $this->selectedUser6 = null;
    $this->selectedMonth2 = null;
    $this->selectedYear4 = null;
    $this->showChart6 = false;
}


}