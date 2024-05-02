<?php

namespace App\Http\Livewire;
use App\Models\Operation;
use App\Models\MainCategories;
use App\Models\Budget;
use Livewire\Component;
use Carbon\Carbon;

class DashboardCharts extends Component
{
    public $categoryName, $categoryName2;
    public $totalBudget, $totalExpenses, $totalIncome, $percentageExpense, $percentageIncome;
    
    public function render()
{
    $monthlyResults = [];
    $currentYear = now()->year; // Obtén el año actual
    $isUserAdmin = auth()->user()->hasRole('Admin');

    for ($i = 1; $i <= 12; $i++) {
        $monthlyResult = $this->calculateMonthlyResults($i, $currentYear, $isUserAdmin);
        $monthlyResults[$i] = $monthlyResult;

        // Suma los ingresos y el presupuesto de este mes al total
        $this->totalIncome += $monthlyResult['totalIncomeMonthly'];
         $this->totalExpenses += $monthlyResult['totalExpensesMonthly'];
        $this->totalBudget += $monthlyResult['totalBudgetMonthly'];
    }

    $this->categoryName = MainCategories::where('id', 1)->value('title');
    $this->categoryName2 = MainCategories::where('id', 2)->value('title');
 // Calculate percentage expense
    $this->percentageExpense = $this->totalBudget > 0 ? ($this->totalExpenses / $this->totalBudget) * 100 : 0;

    // Calculate percentage income
    $this->percentageIncome = $this->totalBudget > 0 ? ($this->totalIncome / $this->totalBudget) * 100 : 0;

    return view('livewire.dashboard-charts', [
        'monthlyResults' => $monthlyResults,
    ]);
}


    protected function calculateMonthlyResults($month, $year, $isUserAdmin)
    {
        $totalBudgetMonthly = $this->calculateTotalBudget($month, $year, $isUserAdmin);
        $totalExpensesMonthly = $this->calculateTotalExpenses($month, $year, $isUserAdmin);
        $totalIncomeMonthly = $this->calculateTotalIncome($month, $year, $isUserAdmin);

        $percentageMonthlyExpense = $totalBudgetMonthly > 0 ? ($totalExpensesMonthly / $totalBudgetMonthly) * 100 : 0;
        $percentageMonthlyIncome = $totalBudgetMonthly > 0 ? ($totalIncomeMonthly / $totalBudgetMonthly) * 100 : 0;

        return [
            'month' => $month,
            'totalBudgetMonthly' => $totalBudgetMonthly,
            'totalExpensesMonthly' => $totalExpensesMonthly,
            'percentageMonthlyExpense' => $percentageMonthlyExpense,
            'totalIncomeMonthly' => $totalIncomeMonthly,
            'percentageMonthlyIncome' => $percentageMonthlyIncome,
        ];
    }

    protected function calculateTotalBudget($month, $year, $isUserAdmin)
    {
        $query = Budget::whereYear('created_at', $year);

        if (!$isUserAdmin) {
            $query->where('user_id', auth()->id());
        }

        return $query->when($month, function ($query, $month) {
                return $query->whereMonth('created_at', $month);
            })
            ->sum('budget_currency_total');
    }

    protected function calculateTotalExpenses($month, $year, $isUserAdmin)
{
    $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->join('budget_expenses', 'operations.id', '=', 'budget_expenses.operation_id') // Unirse a la tabla budget_expenses
        ->leftJoin('budgets', 'budgets.id', '=', 'budget_expenses.budget_id')
        ->where('main_categories.id', 2)
        ->whereYear('operations.created_at', $year)
        ->whereMonth('operations.created_at', $month);

    $this->applyUserFilters($query, $isUserAdmin);

    return $query->sum('operations.operation_currency_total');
}


   protected function calculateTotalIncome($month, $year, $isUserAdmin)
{
    $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->join('budget_incomes', 'operations.id', '=', 'budget_incomes.operation_id') // Unirse a la tabla budget_incomes
        ->leftJoin('budgets', 'budgets.id', '=', 'budget_incomes.budget_id')
        ->where('main_categories.id', 1)
        ->whereYear('operations.created_at', $year)
        ->whereMonth('operations.created_at', $month);

    $this->applyUserFilters($query, $isUserAdmin);

    return $query->sum('operations.operation_currency_total');
}


    protected function applyUserFilters($query, $isUserAdmin)
    {
        if (!$isUserAdmin) {
            $query->where('operations.user_id', auth()->id());
        }
    }
}
