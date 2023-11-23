<?php

namespace App\Http\Livewire;
use App\Models\Operation;
use Livewire\Component;
use App\Models\Category;
use App\Models\MainCategories;

class DashboardCards extends Component
{
    public  $total_users_operations,$income,$expense,$account_balance;
    public function render()
    {
        // Calcula la suma de 'operation_currency_total' para la categoría 'income' (ID 1)
    $this->income = Operation::where('user_id', auth()->id())
    ->whereHas('category', function ($query) {
        $query->where('main_category_id', 1); // 1 es el ID de la categoría 'income'
    })
    ->sum('operation_currency_total');

    // Calcula la suma de 'operation_currency_total' para la categoría 'expense' (ID 2)
    $this->expense = Operation::where('user_id', auth()->id())
    ->whereHas('category', function ($query) {
        $query->where('main_category_id', 2); // 2 es el ID de la categoría 'expense'
    })
    ->sum('operation_currency_total');


        
         $this->account_balance = Operation::where('user_id', auth()->id())->sum('operation_currency_total');
        $this->total_users_operations = Operation::where('user_id', auth()->id())->count();
        return view('livewire.dashboard-cards');
    }
}
