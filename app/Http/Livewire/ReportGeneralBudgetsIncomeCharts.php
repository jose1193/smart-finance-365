<?php

namespace App\Http\Livewire;
use App\Models\Operation;
use App\Models\MainCategories;
use App\Models\EmailManagement;
use App\Models\User;
use App\Models\Category;
use App\Models\Budget;
use App\Models\BudgetIncome;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportGeneralBudgetsIncomeCharts extends Component
{
    public $years = [];
    public $categoryName,$categoryName2;
    public $selectedUser10;
    public $selectedMonth3;
    public $selectedYear7;
    public $showChart6 = false;
    public $users;
    public $operationsFetchMonths;
    public $selectedMonthName3;
    public $totalMonthAmount;
    public $totalMonthAmountCurrency;
  
    public $userNameSelected7;
    public $budget;
    public $budgetData;
    protected $listeners = ['userSelectedChart10','MonthSelectedBudget3','YearSelectedChart7'];

    public $topTenBudgetIncomes;
    
    public $SelectMainCurrencyTypeRender = 'USD';
     
    public $report_date;
    public $selectedMonthNameEs;
    public $mainCurrencyTypeRender;

    public $BudgeIncomeChart1,$BudgeIncomeChart2,$BudgeIncomeChart3;

    public function userSelectedChart10($userId)
    {
       
        

        $this->selectedUser10 = $userId;
        $this->updateBudgetIncomeData();
    }

     public function MonthSelectedBudget3($selectedMonthId)
    {
       
        $this->selectedMonth3 = $selectedMonthId;
        $this->updateBudgetIncomeData();
    }


    public function YearSelectedChart7($selectedYear3Id)
    {
       
        $this->selectedYear7 = $selectedYear3Id;
        $this->updateBudgetIncomeData();
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
        return view('livewire.report-general-budgets-income-charts');
    }

 // REPORT GENERAL BUDGET INCOME MONTHLY CHART

public function updateBudgetIncomeData()
{
    $this->updateBudgetIncomeDataInternal();
    $this->topTenBudgetIncomes = $this->fetchtopTenBudgetIncomes(10);
    
    $this->BudgeIncomeChart1 = 'BudgeIncomeChart1-' . uniqid();
    $this->BudgeIncomeChart2 = 'BudgeIncomeChart2-' . uniqid();
    $this->BudgeIncomeChart3 = 'BudgeIncomeChart3-' . uniqid();
}

private function updateBudgetIncomeDataInternal()
{
    $this->userNameSelected7 = User::find($this->selectedUser10);
    $this->operationsFetchMonths = $this->fetchMonthData();
   
    $this->showChart6 = true;

    if ($this->selectedMonth3) {
        $this->selectedMonthName3 = Carbon::create()->month($this->selectedMonth3)->format('F');

       $this->selectedMonthNameEs = Carbon::create()->month($this->selectedMonth3)->locale('es')->isoFormat('MMMM');

    }
    
    $now = Carbon::now('America/Argentina/Buenos_Aires');
    $this->report_date =  $now->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');

 $this->budget = Budget::where('budget_month', $this->selectedMonth3)
                      ->where('user_id', $this->selectedUser10)
                      ->whereYear('budget_date', $this->selectedYear7)
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
    return Operation::with(['category.mainCategories', 'status', 'operationSubcategories', 'budgetIncomes']) 
        ->join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', 1)
        ->join('statu_options', 'operations.operation_status', '=', 'statu_options.id')
        ->leftJoin('operation_subcategories', 'operation_subcategories.operation_id', '=', 'operations.id') 
        ->leftJoin('subcategories', 'operation_subcategories.subcategory_id', '=', 'subcategories.id') 
        ->leftJoin('budget_incomes', 'operations.id', '=', 'budget_incomes.operation_id')
        ->leftJoin('budgets', 'budgets.id', '=', 'budget_incomes.budget_id')
        ->leftJoin('categories as budget_category', 'budget_category.id', '=', 'budget_incomes.category_id');
}

private function applyFiltersToQuery($query)
{
    if ($this->selectedUser10) {
        $query->where('operations.user_id', $this->selectedUser10);
    }

    if ($this->selectedMonth3) {
        $query->whereMonth('operations.operation_date', $this->selectedMonth3);
    }

    if ($this->selectedYear7) {
        $query->whereYear('operations.operation_date', $this->selectedYear7);
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
        ->where('operations.user_id', $this->selectedUser10)
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

private function fetchtopTenBudgetIncomes($limit)
{
    return $this->fetchData($limit);
}






public function resetFields6()
{
    $this->selectedUser10 = null;
    $this->selectedMonth3 = null;
    $this->selectedYear7 = null;
    $this->showChart6 = false;
}


}