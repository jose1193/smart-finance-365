<?php

namespace App\Http\Livewire;
use App\Models\Operation;
use App\Models\MainCategories;
use App\Models\User;
use App\Models\Category;

use Livewire\Component;
use Carbon\Carbon;


class ReportGeneralCategoriesCharts extends Component
{
    public $years = [];
    public $categoryName,$categoryName2;
    
    public $selectedYear2;
 
   
    public $selectedUser2;
    
    public $selectedCategoryId;
    
    
    public $showChart2 = false;
   
  
    public $ArrayCategories = [];
   
    public $users;
    public $categoriesRender;
    
    
   
  
    public $totalGeneral = 0;
    public $categoryNameSelected;
    
    public $userNameSelected2;
   



    public function mount()
    {
        $this->years = Operation::distinct()->pluck('operation_year');
         $this->users = User::orderBy('id', 'desc')->get();
           $this->categoriesRender = $this->getCategoryOptions();
    }

    public function render()
    {
        return view('livewire.report-general-categories-charts');
    }


    
    // ALL CATEGORIES
public function getCategoryOptions()
{
    $categories = Category::join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->orderBy('categories.id', 'asc')
        ->select('categories.id', 'categories.category_name', 'main_categories.title as main_category_title')
        ->get();

    $formattedCategories = $categories->groupBy('main_category_title')->map(function ($categories, $mainCategoryTitle) {
        return [
            'mainCategoryTitle' => $mainCategoryTitle,
            'categories' => $categories,
        ];
    });

    return collect($formattedCategories);
}

  // REPORT GENERAL CATEGORIES DATES

public function updateCategoriesData()
{
    $this->updateCategoriesDataInternal();
   
}


private function updateCategoriesDataInternal()
{
    $this->ArrayCategories = [];
    $totalGeneral = 0;

    for ($i = 1; $i <= 12; $i++) {
        // Consulta de ingresos
        $income = $this->fetchCategoriesData(1, $i);

        // Consulta de gastos
        $expense = $this->fetchCategoriesData(2, $i);

        // Calcular la suma general
        $total = $income + $expense;
        $totalGeneral += $total;

        $this->ArrayCategories[] = [
            'month' => $i,
            'total' => $total,
        ];
    }

    $this->totalGeneral = $totalGeneral;
    $this->userNameSelected2 = User::find($this->selectedUser2);
    $this->categoryName = MainCategories::where('id', 1)->value('title');
    $this->categoryName2 = MainCategories::where('id', 2)->value('title');
    $this->showChart2 = true;
}



private function fetchCategoriesData($mainCategoryId, $month)
{
    $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', $mainCategoryId);

    if ($this->selectedUser2) {
        $query->where('operations.user_id', $this->selectedUser2);
    }

    if ($this->selectedCategoryId) {
        $query->where('operations.category_id', $this->selectedCategoryId);
    }

    if ($this->selectedYear2) {
        $query->whereYear('operations.operation_date', $this->selectedYear2);
    }

     $this->categoryNameSelected = Category::find($this->selectedCategoryId);
     

    return $query
        ->whereMonth('operations.operation_date', $month)
        ->sum('operations.operation_amount');
}



public function resetFields2()
{
    $this->selectedUser = null;
    $this->selectedYear = null;
    $this->selectedCategoryId = null;
    $this->showChart2 = false;
}

}
