<?php

namespace App\Http\Livewire;
use App\Models\Operation;
use App\Models\MainCategories;
use Livewire\Component;
use Carbon\Carbon;

class ReportGeneralCharts extends Component
{
     
    public $years = [];
    public $categoryName,$categoryName2;
    public $selectedYear;
    public $showChart = false;
    public $incomeData = [];
    public $expenseData = [];

    public function mount()
    {
        $this->years = Operation::distinct()->pluck('operation_year');
    }

    public function render()
    {
         
        return view('livewire.report-general-charts');
    }

   

// Escucha el evento emitido y actualiza los datos de la gráfica
public function updatedSelectedYear($selectedYear)
{
    // Realiza tu consulta y actualiza los datos de la gráfica


    for ($i = 1; $i <= 12; $i++) {
        $incomeData[] = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
            ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
            ->where('main_categories.id', 1)
            ->where('operations.user_id', auth()->id())
            ->whereMonth('operations.created_at', $i)
            ->whereYear('operations.created_at', $selectedYear) // Usar $selectedYear en lugar de $this->selectedYear
            ->sum('operations.operation_amount');

        $expenseData[] = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
            ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
            ->where('main_categories.id', 2)
            ->where('operations.user_id', auth()->id())
            ->whereMonth('operations.created_at', $i)
            ->whereYear('operations.created_at', $selectedYear) // Usar $selectedYear en lugar de $this->selectedYear
            ->sum('operations.operation_amount');
    }
    $this->incomeData = $incomeData;
    $this->expenseData = $expenseData;
    $this->categoryName = MainCategories::where('id', 1)->value('title');
    $this->categoryName2 = MainCategories::where('id', 2)->value('title');

    //$this->emit('updateChartData', [
    //'incomeData' => $incomeData,
    //'expenseData' => $expenseData,]);


    // Muestra la gráfica después de seleccionar el año
    $this->showChart = true;
}



}
