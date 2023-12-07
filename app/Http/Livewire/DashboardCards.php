<?php

namespace App\Http\Livewire;
use App\Models\Operation;
use App\Models\Budget;
use Livewire\Component;
use App\Models\Category;
use App\Models\MainCategories;
use Carbon\Carbon;

class DashboardCards extends Component
{
    public  $total_users_operations,$income,$expense,$account_balance;
    public $labelBudget,$labelCountOperation,$labelIncome;

    public function render()
    {
 

      if (auth()->user()->hasRole('Admin')) {
    $currentMonth = Carbon::now()->month; 
    $currentMonth2 = Carbon::now()->format('F');
    
    $this->labelBudget = 'Total Users Budget  ' . $currentMonth2;
    $this->labelCountOperation = 'Total Users Operations '. $currentMonth2;
    $this->labelIncome = 'Total Users Income '. $currentMonth2;
    $this->labelExpense = 'Total Users Expense '. $currentMonth2;

    $this->income = Operation::whereHas('category', function ($query) {
        $query->where('main_category_id', 1); // 1 es el ID de la categoría 'income'
    })
    ->whereMonth('created_at', $currentMonth) // Filtra por el mes actual
    ->sum('operation_currency_total');

    $this->expense = Operation::whereHas('category', function ($query) {
        $query->where('main_category_id', 2); // 2 es el ID de la categoría 'expense'
    })
    ->whereMonth('created_at', $currentMonth) // Filtra por el mes actual
    ->sum('operation_currency_total');

    $this->account_balance = Budget::whereMonth('created_at', $currentMonth)->sum('budget_currency_total');

    $this->total_users_operations = Operation::whereMonth('created_at', $currentMonth)->count();

   
}
else {
    $currentMonth = Carbon::now()->month;
    $currentMonth2 = Carbon::now()->format('F');
    $this->labelBudget = 'General Budget ' . $currentMonth2; 
    $this->labelCountOperation = 'Total Operations '.  $currentMonth2;
    $this->labelIncome = ' Income '. $currentMonth2;
    $this->labelExpense = 'Expense '. $currentMonth2;

  
// Calcula la suma de 'operation_currency_total' para la categoría 'income' (ID 1)
$this->income = Operation::where('user_id', auth()->id())
    ->whereHas('category', function ($query) {
        $query->where('main_category_id', 1); // 1 es el ID de la categoría 'income'
    })
    ->whereMonth('created_at', $currentMonth) // Filtra por el mes actual
    ->sum('operation_currency_total');

// Calcula la suma de 'operation_currency_total' para la categoría 'expense' (ID 2)
$this->expense = Operation::where('user_id', auth()->id())
    ->whereHas('category', function ($query) {
        $query->where('main_category_id', 2); // 2 es el ID de la categoría 'expense'
    })
    ->whereMonth('created_at', $currentMonth) // Filtra por el mes actual
    ->sum('operation_currency_total');

$this->account_balance = Budget::where('user_id', auth()->id())
    ->whereMonth('created_at', $currentMonth) // Filtra por el mes actual
    ->sum('budget_currency_total');


$this->total_users_operations = Operation::where('user_id', auth()->id())
    ->whereMonth('created_at', $currentMonth) // Filtra por el mes actual
    ->count();
}


        return view('livewire.dashboard-cards');
    }
}
