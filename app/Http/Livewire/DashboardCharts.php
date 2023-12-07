<?php

namespace App\Http\Livewire;
use App\Models\Operation;
use App\Models\MainCategories;
use App\Models\Budget;
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
       
      $isUserAdmin = auth()->user()->hasRole('Admin');

for ($i = 1; $i <= 12; $i++) {
    $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->whereMonth('operations.created_at', $i)
        ->whereYear('operations.created_at', $currentYear);

    // Clonar la instancia de la consulta para evitar la acumulación de condiciones
    $incomeQuery = clone $query;
    $expenseQuery = clone $query;

    if (!$isUserAdmin) {
        // Si el usuario no es Admin, filtrar por su ID de usuario
        $incomeQuery->where('operations.user_id', auth()->id());
        $expenseQuery->where('operations.user_id', auth()->id());
    }

    $incomeData[] = $incomeQuery
        ->where('main_categories.id', 1)
        ->sum('operations.operation_currency_total');

    $expenseData[] = $expenseQuery
        ->where('main_categories.id', 2)
        ->sum('operations.operation_currency_total');
}

    $this->categoryName = MainCategories::where('id', 1)->value('title');
    $this->categoryName2 = MainCategories::where('id', 2)->value('title');
        return view('livewire.dashboard-charts', [
        'incomeData' => $incomeData,
        'expenseData' => $expenseData,
    ]);

    }
}
