<?php
namespace App\Http\Livewire;
use App\Models\Operation;
use App\Models\MainCategories;
use App\Models\EmailManagement;
use App\Models\User;
use App\Models\Category;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportGeneralTable extends Component
{
     
    public $years = [];
    public $categoryName,$categoryName2;
    public $selectedYear;
    public $selectedUser;
    public $selectedCategoryId;
    public $showData = false;
    public $showData2 = false;
    public $showData3 = false;
    public $incomeData = [];
    public $expenseData = [];
    public $ArrayCategories = [];
    public $users;
    public $categoriesRender;
    public $isOpen = 0;
    public $emails_user;
    public $emails;

    public $date_start;
    public $date_end;
    public $categoryNameSelected;
    public $userNameSelected;

    public $totalIncome;
    public $totalExpense;
    public $totalCategoriesRender;

    public function mount()
    {
        $this->years = Operation::distinct()->pluck('operation_year');
        $this->users = User::orderBy('id', 'desc')->get();
        $this->categoriesRender = Category::join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->orderBy('categories.id', 'asc')
        ->select('categories.id', 'categories.category_name', 'main_categories.title as main_category_title')
        ->get();
    
        $this->emails = EmailManagement::where('user_id', auth()->id())->get();

    }

    public function render()
    {
         
        return view('livewire.report-general-table');
    }

   // REPORT GENERAL TABLE
public function updateData()
{
    $this->updateDataInternal();
  

    
}


private function updateDataInternal()
{
    $this->incomeData = [];
    $this->expenseData = [];

    for ($i = 1; $i <= 12; $i++) {
        // Consulta de ingresos
        $incomeData[] = $this->fetchData(1, $i);

        // Consulta de gastos
        $expenseData[] = $this->fetchData(2, $i);
    }

    $this->incomeData = $incomeData;
    $this->expenseData = $expenseData;
    $this->totalIncome = array_sum($incomeData);
    $this->totalExpense = array_sum($expenseData);


    $this->userNameSelected = User::find($this->selectedUser);

    $this->categoryName = MainCategories::where('id', 1)->value('title');
    $this->categoryName2 = MainCategories::where('id', 2)->value('title');
    $this->showData = true;
   
}

private function fetchData($mainCategoryId, $month)
{
    $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', $mainCategoryId);

    if ($this->selectedUser) {
        $query->where('operations.user_id', $this->selectedUser);
    }

    if ($this->selectedYear) {
        $query->whereYear('operations.operation_date', $this->selectedYear);
    }

    return $query
        ->whereMonth('operations.operation_date', $month)
        ->sum('operations.operation_amount');
}



  // REPORT GENERAL BETWEEN DATES
public function updateBetweenData()
{
    $this->updateBetweenDataInternal();
  

    
}


private function updateBetweenDataInternal()
{
    
   $this->incomeData = [];
    $this->expenseData = [];

    for ($i = 1; $i <= 12; $i++) {
        // Consulta de ingresos
        $incomeData[] = $this->fetchBetweenData(1, $i);

        // Consulta de gastos
        $expenseData[] = $this->fetchBetweenData(2, $i);
    }

    $this->incomeData = $incomeData;
    $this->expenseData = $expenseData;

    $this->totalIncome = array_sum($incomeData);
    $this->totalExpense = array_sum($expenseData);
    $this->userNameSelected = User::find($this->selectedUser);

    $this->categoryName = MainCategories::where('id', 1)->value('title');
    $this->categoryName2 = MainCategories::where('id', 2)->value('title');
    $this->showData3 = true;
}

private function fetchBetweenData($mainCategoryId, $month)
{
   $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', $mainCategoryId);

    if ($this->selectedUser) {
        $query->where('operations.user_id', $this->selectedUser);
    }

    if ($this->date_start) {
        $query->whereDate('operations.operation_date', '>=', $this->date_start);
    }

    if ($this->date_end) {
        $query->whereDate('operations.operation_date', '<=', $this->date_end);
    }

    return $query
        ->whereMonth('operations.operation_date', $month)
        ->sum('operations.operation_amount');
}




  // REPORT GENERAL CATEGORIES DATES

public function updateCategoriesData()
{
    $this->updateCategoriesDataInternal();
}

private function updateCategoriesDataInternal()
{
    $this->ArrayCategories = [];
    $this->totalCategoriesRender = 0; 

    for ($i = 1; $i <= 12; $i++) {
        // Consulta de ingresos
        $income = $this->fetchCategoriesData(1, $i);

        // Consulta de gastos
        $expense = $this->fetchCategoriesData(2, $i);

        // Calcular la suma general
        $total = $income + $expense;

        
    $this->userNameSelected = User::find($this->selectedUser);
    
        $this->ArrayCategories[] = [
            'month' => $i,
            'total' => $total,
        ];


        $this->totalCategoriesRender += $total;
    }

    $this->categoryName = MainCategories::where('id', 1)->value('title');
    $this->categoryName2 = MainCategories::where('id', 2)->value('title');
    $this->showData2 = true;
}


private function fetchCategoriesData($mainCategoryId, $month)
{
    $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', $mainCategoryId);

    if ($this->selectedUser) {
        $query->where('operations.user_id', $this->selectedUser);
    }

    if ($this->selectedCategoryId) {
        $query->where('operations.category_id', $this->selectedCategoryId);
    }

    if ($this->selectedYear) {
        $query->whereYear('operations.operation_date', $this->selectedYear);
    }

    
    $this->categoryNameSelected = Category::find($this->selectedCategoryId);

    return $query
        ->whereMonth('operations.operation_date', $month)
        ->sum('operations.operation_amount');
}




// FUNCIONT TO EXPORT EXCEL
 public function exportToExcel()
{
    // Lógica para exportar la tabla a Excel

    $this->emit('exportTableToExcel');
}


//FUNCTION RESET FIELDS REPORT GENERAL
public function resetFields1()
{
    $this->selectedUser = null;
    $this->selectedYear = null;
   
    $this->showData = false;
}

public function resetFields2()
{
    $this->selectedUser = null;
    $this->selectedYear = null;
    $this->selectedCategoryId = null;
    $this->showData2 = false;
}

public function resetFields3()
{
    $this->selectedUser = null;
    $this->selectedYear = null;
    $this->date_start = null;
    $this->date_end = null;
    $this->showData3 = false;
}





// FUNCTION SEND REPORT TO USERS EMAILS
public function sendEmail()
    {
        
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields(){
         $this->reset();
    }

    public function emailStore()
    {
       $validationRules = [
        'emails_user' => 'required|string|email|max:50',
        
    ];

    $validatedData = $this->validate($validationRules);
    
        Todo::updateOrCreate(['id' => $this->todo_id], [
            'emails_user' => $this->emails_user,
           
        ]);

   // Llamar al método emailSent para enviar el correo con el archivo Excel
        $this->emailSent();
        session()->flash('message', 
            $this->todo_id ? 'Todo Updated Successfully.' : 'Todo Created Successfully.');

        

        $this->closeModal();
        $this->resetInputFields();
    }


   


}

