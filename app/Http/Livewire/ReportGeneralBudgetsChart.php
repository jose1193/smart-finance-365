<?php

namespace App\Http\Livewire;
use App\Models\Operation;
use App\Models\MainCategories;
use App\Models\EmailManagement;
use App\Models\User;
use App\Models\Budget;
use App\Models\BudgetExpense;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReportGeneralBudgetsChart extends Component
{
     public $years = [];
    public $categoryName,$categoryName2;
    public $selectedYear4;
 
    public $selectedUser5;
    public $userNameSelected;
    
    
    public $showChart5 = false;
 
    public $incomeData = [];
    public $expenseData = [];
    public $expenseTopTen = [];
    public $incomeTopTen = [];

    public $budgetDataCurrency = [];
    public $totalBudgetCurrency = [];
    public $users;
   
    public $isOpen = 0;
    protected $listeners = ['userSelectedChart5','YearSelectedChart4'];

    public $date_start;
    public $date_end;

    public $mainCurrencyTypeRender;
    public $SelectMainCurrencyTypeRender = 'USD';

    public function userSelectedChart5($userId)
    {
        
        
        $this->selectedUser5 = $userId;

     $this->mainCurrencyTypeRender = Operation::where('user_id', $userId)
    ->where('operation_currency_type', '!=', 'USD')
    ->distinct()
    ->pluck('operation_currency_type');

        $this->updateChartBudgetData();
    }

    public function YearSelectedChart4($selectedYearId)
    {
       
        $this->selectedYear4 = $selectedYearId;
        $this->updateChartBudgetData();
    }
    
    public function mount()
    {
        $this->years = Operation::distinct()->pluck('operation_year');
         $this->users = User::orderBy('id', 'desc')->get();
          
    }


    public function render()
    {
        return view('livewire.report-general-budgets-chart');
    }

     
public function updateChartBudgetData()
{
    $this->emit('initializeFlatpickr2');
    $this->updateChartBudgetDataInternal();

}


private function updateChartBudgetDataInternal()
{
    $this->incomeData = [];
    $this->expenseData = [];
    $this->incomeDataCurrency = [];
    $this->expenseDataCurrency = [];
    $this->budgetDataCurrency = [];

    $this->expenseTopTen = [];
    $this->incomeTopTen = [];

    for ($i = 1; $i <= 12; $i++) {
        $data = $this->fetchData(1, $i);

        $this->incomeData[] = $data['operation_amount'];
        $this->incomeDataCurrency[] = $data['operation_total'];
        
        $data = $this->fetchData(2, $i);

        $this->expenseData[] = $data['operation_amount'];
        $this->expenseDataCurrency[] = $data['operation_total'];

        $data = $this->fetchBudgetData($i);

        $this->budgetDataCurrency[] = $data['budget_currency_total'];
        
    }

    $this->totalIncome = array_sum($this->incomeData);
    $this->totalIncomeCurrency = array_sum($this->incomeDataCurrency);
    $this->totalExpense = array_sum($this->expenseData);
    $this->totalExpenseCurrency = array_sum($this->expenseDataCurrency);
    
    $this->totalBudgetCurrency = array_sum($this->budgetDataCurrency);

    $this->userNameSelected = User::find($this->selectedUser5);

    $this->categoryName = MainCategories::where('id', 1)->value('title');
    $this->categoryName2 = MainCategories::where('id', 2)->value('title');
    $this->showChart5 = true;
    
    $this->expenseTopTen = $this->fetchTopTenExpenses();
    $this->incomeTopTen = $this->fetchTopTenIncome();
}



private function fetchData($mainCategoryId, $month)
{
    $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', $mainCategoryId);

    if ($this->selectedUser5) {
        $query->where('operations.user_id', $this->selectedUser5);
    }

    if ($this->selectedYear4) {
        $query->whereYear('operations.operation_date', $this->selectedYear4);
    }

    if ($this->date_start && $this->date_end) {
        $query->whereBetween('operations.operation_date', [$this->date_start, $this->date_end]);
    } elseif ($this->date_start) {
        $query->whereDate('operations.operation_date', '>=', $this->date_start);
    } elseif ($this->date_end) {
        $query->whereDate('operations.operation_date', '<=', $this->date_end);
    }

    $data = [
        'operation_amount' => $query
            ->whereMonth('operations.operation_date', $month)
            ->sum('operations.operation_amount'),
        'operation_currency_total' => $query
            ->whereMonth('operations.operation_date', $month)
            ->sum('operations.operation_currency_total'),
    ];

    // Calcula la suma de 'operation_currency_total' sin aplicar el filtro
    $data['operation_total'] = $query
        ->whereMonth('operations.operation_date', $month)
        ->sum('operations.operation_currency_total');

    // Aplica el filtro 'SelectMainCurrencyTypeRender' si es diferente de 'USD'
    if ($this->SelectMainCurrencyTypeRender && $this->SelectMainCurrencyTypeRender !== 'USD') {
        $data['operation_total'] = $query
            ->whereMonth('operations.operation_date', $month)
            ->where('operations.operation_currency_type', $this->SelectMainCurrencyTypeRender)
            ->sum('operations.operation_amount');
    }

    return $data;
}


private function fetchBudgetData($month)
{
    $query = Budget::where('user_id', $this->selectedUser5);

    if ($this->selectedYear4) {
        $query->whereYear('budget_date', $this->selectedYear4);
    }

    $data = [
        'budget_currency_total' => $query
            ->whereMonth('budget_date', $month)
            ->sum('budget_currency_total')
    ];

    // Aplica el filtro 'SelectMainCurrencyTypeRender' si es diferente de 'USD'
    if ($this->SelectMainCurrencyTypeRender && $this->SelectMainCurrencyTypeRender !== 'USD') {
        $data = [
        'budget_currency_total' => $query
            ->whereMonth('budget_date', $month)
              ->where('budget_currency_type', $this->SelectMainCurrencyTypeRender)
            ->sum('budget_operation')
    ];

    }
    
    return $data;
}




private function fetchTopTenExpenses()
{
    $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', 2) // Assuming 2 is the ID for the expense main category
        ->groupBy('categories.id', 'categories.category_name'); // Agrupar por categoría

    if ($this->selectedUser5) {
        $query->where('operations.user_id', $this->selectedUser5);
    }

    if ($this->selectedYear4) {
        $query->whereYear('operations.operation_date', $this->selectedYear4);
    }

    if ($this->date_start && $this->date_end) {
        $query->whereBetween('operations.operation_date', [$this->date_start, $this->date_end]);
    } elseif ($this->date_start) {
        $query->whereDate('operations.operation_date', '>=', $this->date_start);
    } elseif ($this->date_end) {
        $query->whereDate('operations.operation_date', '<=', $this->date_end);
    }

    $topTenExpenses = $query
        ->selectRaw('categories.category_name, SUM(operations.operation_currency_total) as total_expenses')
        ->orderByDesc('total_expenses')
        ->limit(10)
        ->get()
        ->toArray();

        // Aplica el filtro 'SelectMainCurrencyTypeRender' si es diferente de 'USD'
    if ($this->SelectMainCurrencyTypeRender && $this->SelectMainCurrencyTypeRender !== 'USD') {
         $topTenExpenses = $query
        ->selectRaw('categories.category_name, SUM(operations.operation_amount) as total_expenses')
         ->where('operation_currency_type', $this->SelectMainCurrencyTypeRender)
        ->orderByDesc('total_expenses')
        ->limit(10)
        ->get()
        ->toArray();

    }

    return $topTenExpenses;
}



private function fetchTopTenIncome()
{
    $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', 1)
        ->groupBy('categories.id', 'categories.category_name'); // Agrupa por categoría

    if ($this->selectedUser5) {
        $query->where('operations.user_id', $this->selectedUser5);
    }

    if ($this->selectedYear4) {
        $query->whereYear('operations.operation_date', $this->selectedYear4);
    }

    if ($this->date_start && $this->date_end) {
        $query->whereBetween('operations.operation_date', [$this->date_start, $this->date_end]);
    } elseif ($this->date_start) {
        $query->whereDate('operations.operation_date', '>=', $this->date_start);
    } elseif ($this->date_end) {
        $query->whereDate('operations.operation_date', '<=', $this->date_end);
    }

    $incomeTopTen = $query
        ->selectRaw('categories.category_name, SUM(operations.operation_currency_total) as total_income')
        ->orderByDesc('total_income')
        ->limit(10)
        ->get()
        ->toArray();

// Aplica el filtro 'SelectMainCurrencyTypeRender' si es diferente de 'USD'
    if ($this->SelectMainCurrencyTypeRender && $this->SelectMainCurrencyTypeRender !== 'USD') {
         $incomeTopTen = $query
        ->selectRaw('categories.category_name, SUM(operations.operation_amount) as total_income')
         ->where('operation_currency_type', $this->SelectMainCurrencyTypeRender)
        ->orderByDesc('total_income')
        ->limit(10)
        ->get()
        ->toArray();

    }

    return $incomeTopTen;
}


//FUNCTION RESET FIELDS REPORT GENERAL
public function resetFields5()
{
    $this->selectedUser5 = null;
    $this->selectedYear4 = null;
   
    $this->showChart5 = false;
}


}
