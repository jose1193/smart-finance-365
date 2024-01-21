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
    $incomeData = [];
    $expenseData = [];
    $monthlyResults = [];
    $currentYear = now()->year; // Obtén el año actual

    $isUserAdmin = auth()->user()->hasRole('Admin');

    for ($i = 1; $i <= 12; $i++) {
        $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
            ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
            ->whereMonth('operations.created_at', $i)
            ->whereYear('operations.created_at', $currentYear);

        // Clonar la instancia de la consulta para evitar la acumulación de condiciones
        $incomeQuery = clone $query;
        $expenseQuery = clone $query;
        $expenseQueryWithBudget = clone $query;
        

        $this->applyUserFilters($incomeQuery, $expenseQuery, $expenseQueryWithBudget, $isUserAdmin);

        $this->calculateTotalBudget($i, $currentYear, $isUserAdmin);

        $incomeData[] = $this->calculateTotal($incomeQuery, 1);

        $expenseData[] = $this->calculateTotal($expenseQuery, 2);

        $this->calculateTotalExpenses($expenseQueryWithBudget);

        $this->totalIncome += $incomeData[$i - 1];

         $this->calculateMonthlyResults($i, $currentYear, $expenseQueryWithBudget, $incomeQuery, $monthlyResults, $isUserAdmin);
    }

    $this->categoryName = MainCategories::where('id', 1)->value('title');
    $this->categoryName2 = MainCategories::where('id', 2)->value('title');

    return view('livewire.dashboard-charts', [
        'incomeData' => $incomeData,
        'expenseData' => $expenseData,
        'monthlyResults' => $monthlyResults
    ]);
}


protected function applyUserFilters($incomeQuery, $expenseQuery, $expenseQueryWithBudget, $isUserAdmin)
{
    if (!$isUserAdmin) {
        $userId = auth()->id();
        $incomeQuery->where('operations.user_id', $userId);
        $expenseQuery->where('operations.user_id', $userId);
        $expenseQueryWithBudget->where('operations.user_id', $userId);
    }
}


protected function calculateTotalBudget($month, $year, $isUserAdmin)
{
    if ($isUserAdmin) {
        $this->totalBudget = Budget::whereYear('created_at', $year)->sum('budget_currency_total');
    } else {
        $userId = auth()->id();
        $this->totalBudget += Budget::where('user_id', $userId)
            ->whereYear('created_at', $year)
            ->when($month, function ($query, $month) {
                return $query->whereMonth('created_at', $month);
            })
            ->sum('budget_currency_total');
    }
    
}


protected function calculateTotal($query, $categoryId)
{
    return $query->where('main_categories.id', $categoryId)->sum('operations.operation_currency_total');
}

protected function calculateTotalExpenses($expenseQueryWithBudget)
{
    $this->totalExpenses += $expenseQueryWithBudget
        ->leftJoin('budget_expenses', 'operations.id', '=', 'budget_expenses.operation_id')
        ->leftJoin('budgets', 'budgets.id', '=', 'budget_expenses.budget_id')
        ->where('main_categories.id', 2)
        ->sum('operations.operation_currency_total');
}


protected function calculateMonthlyResults($month, $year, $expenseQueryWithBudget, $incomeQuery, &$monthlyResults, $isUserAdmin)
{
    if ($isUserAdmin) {
        $totalBudgetMonthly = Budget::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('budget_currency_total');
    } else {
        $userId = auth()->id();
        $totalBudgetMonthly = Budget::where('user_id', $userId)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('budget_currency_total');
    }

    $totalExpensesMonthly = $expenseQueryWithBudget
        ->leftJoin('budget_expenses as be2', 'operations.id', '=', 'be2.operation_id')
        ->leftJoin('budgets as b2', 'b2.id', '=', 'be2.budget_id')
        ->where('main_categories.id', 2)
        ->whereYear('be2.created_at', $year)
        ->whereMonth('be2.created_at', $month)
        ->sum('operations.operation_currency_total');

    $totalIncomeMonthly = $incomeQuery
        ->where('main_categories.id', 1)
        ->whereYear('operations.created_at', $year)
        ->whereMonth('operations.created_at', $month)
        ->sum('operations.operation_currency_total');

    // Calculate percentage expense
    $this->percentageExpense = $this->totalBudget > 0 ? ($this->totalExpenses / $this->totalBudget) * 100 : 0;

    // Calculate percentage income
    $this->percentageIncome = $this->totalBudget > 0 ? ($this->totalIncome / $this->totalBudget) * 100 : 0;

    $percentageMonthlyExpense = $totalBudgetMonthly > 0 ? ($totalExpensesMonthly / $totalBudgetMonthly) * 100 : 0;
    $percentageMonthlyIncome = $totalBudgetMonthly > 0 ? ($totalIncomeMonthly / $totalBudgetMonthly) * 100 : 0;

    $monthlyResults[$month] = [
        'month' => $month,
        'totalBudgetMonthly' => $totalBudgetMonthly,
        'totalExpensesMonthly' => $totalExpensesMonthly,
        'percentageMonthlyExpense' => $percentageMonthlyExpense,
        'totalIncomeMonthly' => $totalIncomeMonthly,
        'percentageMonthlyIncome' => $percentageMonthlyIncome,
    ];
}


}
