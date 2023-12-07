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

    public function userSelectedChart6($userId)
    {
        // Aquí puedes ejecutar la lógica que desees con el $userId
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
       
        $this->selectedYear5 = $selectedYear3Id;
        $this->updateBudgetExpenseData();
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
        
       
         
    }

    public function render()
    {
        return view('livewire.report-general-budgets-expense-charts');
    }

    
 // REPORT GENERAL MONTH CHART

public function updateBudgetExpenseData()
{
    $this->updateBudgetExpenseDataInternal();
}

private function updateBudgetExpenseDataInternal()
{
    $this->userNameSelected5 = User::find($this->selectedUser6);
    $this->operationsFetchMonths = $this->fetchMonthData();
    $this->totalMonthAmount = $this->fetchTotalMonthAmount(); 
    $this->totalMonthAmountCurrency = $this->fetchTotalMonthAmountCurrency(); 
    
    $this->showChart6 = true;
    
    
    if ($this->selectedMonth2) {
        $selectedDate = Carbon::create()->month($this->selectedMonth2);
        $this->selectedMonthName2 = $selectedDate->format('F');
    }
     $this->budget = Budget::where('budget_month', $this->selectedMonth2)
                      ->where('user_id', $this->selectedUser6)
                      ->first();

    $this->budgetData = $this->budget ? 'Budget Month ' . $this->budget->budget_currency_total . ' $' : 'Budget Month N/A';

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
    $query = Operation::with(['category.mainCategories', 'status', 'operationSubcategories', 'budgetExpenses']) 
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
            'budget_expenses.budget_id',
        )
        ->orderBy('operations.id', 'desc')
        ->get();

      

    return $query;
}




public function resetFields6()
{
    $this->selectedUser6 = null;
    $this->selectedMonth2 = null;
    $this->selectedYear5 = null;
    $this->showChart6 = false;
}


}
