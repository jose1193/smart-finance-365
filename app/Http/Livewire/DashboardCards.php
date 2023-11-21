<?php

namespace App\Http\Livewire;
use App\Models\Operation;
use Livewire\Component;

class DashboardCards extends Component
{
    public  $total_users_operations,$paid,$unpaid,$account_balance;
    public function render()
    {
        $this->paid = Operation::where('operation_status', 1)->where('user_id', auth()->id())->sum('operation_currency_total');
         $this->unpaid = Operation::where('operation_status', 2)->where('user_id', auth()->id())->sum('operation_currency_total');
          $this->account_balance = Operation::where('user_id', auth()->id())->sum('operation_currency_total');
        $this->total_users_operations = Operation::where('user_id', auth()->id())->count();
        return view('livewire.dashboard-cards');
    }
}
