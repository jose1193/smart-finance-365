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

    public function userSelectedChart6($userId)
    {
       
         $this->mainCurrencyTypeRender = Operation::where('user_id', $userId)
    ->where('operation_currency_type', '!=', 'USD')
    ->distinct()
    ->pluck('operation_currency_type');

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
        $monthName = $dateInMonth->format('F');

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
    $this->topTenBudgetExpenses = $this->fetchTopTenBudgetExpenses();
}

private function updateBudgetExpenseDataInternal()
{
    $this->userNameSelected5 = User::find($this->selectedUser6);
    $this->operationsFetchMonths = $this->fetchMonthData();
    $this->totalMonthAmount = $this->fetchTotalMonthAmount(); 
    $this->totalMonthAmountCurrency = $this->fetchTotalMonthAmountCurrency(); 
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
    $query = $this->buildOperationQuery();
    return $this->executeOperationQuery($query);
}


private function buildOperationQuery()
{
    return Operation::with(['category.mainCategories', 'status', 'operationSubcategories', 'budgetExpenses']) 
                    ->join('categories', 'operations.category_id', '=', 'categories.id')
                    ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
                    ->join('statu_options', 'operations.operation_status', '=', 'statu_options.id')
                    ->leftJoin('operation_subcategories', 'operation_subcategories.operation_id', '=', 'operations.id') 
                    ->leftJoin('subcategories', 'operation_subcategories.subcategory_id', '=', 'subcategories.id') 
                    ->leftJoin('budget_expenses', 'operations.id', '=', 'budget_expenses.operation_id')
                    ->leftJoin('budgets', 'budgets.id', '=', 'budget_expenses.budget_id')
                    ->leftJoin('categories as budget_category', 'budget_category.id', '=', 'budget_expenses.category_id')
                    ->whereHas('category.mainCategories', function ($query) {
                        $query->where('id', 2); // Utiliza la clave foránea correcta
                    })
                    ->when($this->selectedUser6, function ($query, $selectedUser6) {
                        return $query->where('operations.user_id', $selectedUser6);
                    })
                    ->when($this->selectedMonth2, function ($query, $selectedMonth2) {
                        return $query->whereMonth('operations.operation_date', $selectedMonth2);
                    })
                    ->when($this->selectedYear4, function ($query, $selectedYear4) {
                        return $query->whereYear('operations.operation_date', $selectedYear4);
                    })
                    ->when($this->SelectMainCurrencyTypeRender, function ($query, $SelectMainCurrencyTypeRender) {
                        return $query->where('operations.operation_currency_type', $SelectMainCurrencyTypeRender);
                    })
                    ->select(
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
                        'budgets.budget_operation as budget_operation',
                        'budget_expenses.budget_id'
                    )
                    ->orderBy('operations.id', 'desc');
            }



private function executeOperationQuery($query)
{
    return $query->get();
}


private function fetchTopTenBudgetExpenses()
{
    $query = $this->buildOperationQuery();

    return $query->orderBy('operations.operation_currency_total', 'desc')
                 ->limit(10)
                 ->get();
}


public function resetFields6()
{
    $this->selectedUser6 = null;
    $this->selectedMonth2 = null;
    $this->selectedYear4 = null;
    $this->showChart6 = false;
}


}
