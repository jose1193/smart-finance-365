<?php

namespace App\Http\Livewire;
use App\Models\Operation;
use App\Models\MainCategories;
use Livewire\Component;
use Carbon\Carbon;

class DashboardCharts extends Component
{
    public $categoryName,$categoryName2;
    public function render()
    {
        $incomeData = [];
        $expenseData = [];
        $currentYear = now()->year; // Obtenemos el año actual

    for ($i = 1; $i <= 12; $i++) {
        $incomeData[] = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', 1)
        ->where('operations.user_id', auth()->id())
        ->whereMonth('operations.created_at', $i)
        ->whereYear('operations.created_at', $currentYear) // Filtrar por el año actual
        ->sum('operations.operation_amount');

        $expenseData[] = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', 2)
        ->where('operations.user_id', auth()->id())
        ->whereMonth('operations.created_at', $i)
        ->whereYear('operations.created_at', $currentYear) // Filtrar por el año actual
        ->sum('operations.operation_amount');
}
    $this->categoryName = MainCategories::where('id', 1)->value('title');
    $this->categoryName2 = MainCategories::where('id', 2)->value('title');
        return view('livewire.dashboard-charts', [
        'incomeData' => $incomeData,
        'expenseData' => $expenseData,
    ]);

    }
}
