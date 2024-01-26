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
   
    public $SelectMainCurrencyTypeRender = 'USD';

    protected $listeners = ['userSelectedChart2','YearSelectedChart2','categorySelected2'];

    public function userSelectedChart2($userId)
    {
        
    $this->mainCurrencyTypeRender = Operation::where('user_id', $userId)
    ->where('operation_currency_type', '!=', 'USD')
    ->distinct()
    ->pluck('operation_currency_type');

        $this->selectedUser2 = $userId;
        $this->updateCategoriesData();
    }

    public function YearSelectedChart2($selectedYearId)
    {
       
        $this->selectedYear2 = $selectedYearId;
        $this->updateCategoriesData();
    }


    public function categorySelected2($categoryId)
    {
        
        $this->selectedCategoryId = $categoryId;
        $this->updateCategoriesData();
    }

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
    $categoriesQuery = Category::join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->orderBy('categories.id', 'asc')
        ->select('categories.id', 'categories.category_name', 'main_categories.title as main_category_title');

    if (auth()->user()->hasRole('Admin')) {
       
        $categories = $categoriesQuery->get();
    } elseif (auth()->user()->hasRole('User')) {
    // Si es un usuario, obtener las categorías asignadas
    $categories = $categoriesQuery->leftJoin('categories_to_assigns', function ($join) {
            $join->on('categories.id', '=', 'categories_to_assigns.category_id')
                ->where('categories_to_assigns.user_id_assign', '=', auth()->user()->id);
        })
        ->select('categories.id', 'categories.category_name', 'main_categories.title as main_category_title')
        ->get();
}

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
     
    // Aplica el filtro 'SelectMainCurrencyTypeRender' si es diferente de 'USD'
    if ($this->SelectMainCurrencyTypeRender && $this->SelectMainCurrencyTypeRender !== 'USD') {
        return $query
        ->whereMonth('operations.operation_date', $month)
        ->sum('operations.operation_amount');

    }

    return $query
        ->whereMonth('operations.operation_date', $month)
        ->sum('operations.operation_currency_total');
}



public function resetFields2()
{
    $this->selectedUser2 = null;
    $this->selectedYear2 = null;
    $this->selectedCategoryId = null;
    $this->showChart2 = false;
}

}
