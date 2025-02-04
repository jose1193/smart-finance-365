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
 
    $currentMonth = now()->month;
    $currentYear = Carbon::now()->year; 
    $currentMonth2 = Carbon::now()->format('F');
    
    if (auth()->user()->hasRole('Admin')) {
   
    



$this->labelBudget = __('messages.dashboard_user_total_users_budget') . ' ' . $currentMonth2;
$this->labelCountOperation = __('messages.dashboard_user_total_users_operations');
$this->labelIncome = __('messages.dashboard_user_total_users_income');
$this->labelExpense = __('messages.dashboard_user_total_users_expense');


   $this->income = Operation::whereHas('category', function ($query) {
        $query->where('main_category_id', 1); // 1 es el ID de la categoría 'income'
    })
    ->whereMonth('created_at', $currentMonth) // Filtra por el mes actual
     ->whereYear('created_at',$currentYear) // Filtra por el año actual
    ->sum('operation_currency_total');


    $this->expense = Operation::whereHas('category', function ($query) {
        $query->where('main_category_id', 2); // 2 es el ID de la categoría 'expense'
    })
    ->whereMonth('created_at', $currentMonth) // Filtra por el mes actual
     ->whereYear('created_at',$currentYear) // Filtra por el año actual
    ->sum('operation_currency_total');

 $this->account_balance = Budget::whereYear('created_at', $currentYear)
    ->whereMonth('created_at', $currentMonth)
    ->sum('budget_currency_total') ?? 0;



    $this->total_users_operations = Operation::whereMonth('created_at', $currentMonth)
     ->whereYear('created_at',  $currentYear)->count();

   
}
 else {
$this->labelBudget = __('messages.dashboard_user_general_budget') . ' ' . $currentMonth2; 
$this->labelCountOperation = __('messages.dashboard_user_total_operations');
$this->labelIncome = __('messages.dashboard_user_income');
$this->labelExpense = __('messages.dashboard_user_expense');


  
// Calcula la suma de 'operation_currency_total' para la categoría 'income' (ID 1)
$this->income = Operation::where('user_id', auth()->id())
    ->whereHas('category', function ($query) {
        $query->where('main_category_id', 1); // 1 es el ID de la categoría 'income'
    })
    ->whereMonth('created_at', $currentMonth) // Filtra por el mes actual
     ->whereYear('created_at',  $currentYear) 
    ->sum('operation_currency_total');

// Calcula la suma de 'operation_currency_total' para la categoría 'expense' (ID 2)
$this->expense = Operation::where('user_id', auth()->id())
    ->whereHas('category', function ($query) {
        $query->where('main_category_id', 2); // 2 es el ID de la categoría 'expense'
    })
    ->whereMonth('created_at', $currentMonth) // Filtra por el mes actual
     ->whereYear('created_at',  $currentYear) 
    ->sum('operation_currency_total');

$account_balance = Budget::where('user_id', auth()->user()->id)
    ->whereMonth('created_at', $currentMonth)
    ->whereYear('created_at', $currentYear)
    ->first();

if ($account_balance) {
    $this->account_balance = $account_balance->budget_currency_total;
} else {
    // Manejar la situación en la que no hay presupuesto
    $this->account_balance = 0; // O cualquier otro valor predeterminado que desees
}



$this->total_users_operations = Operation::where('user_id', auth()->id())
    ->whereMonth('created_at', $currentMonth) // Filtra por el mes actual
     ->whereYear('created_at',  $currentYear) 
    ->count();
}


        return view('livewire.dashboard-cards');
    }
}
