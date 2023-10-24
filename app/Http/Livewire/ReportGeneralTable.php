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
    public $selectedYear2;
    public $selectedYear3;
    
    public $selectedUser;
    public $selectedUser2;
    public $selectedUser3;
    public $selectedUser4;
    public $selectedCategoryId;
    public $selectedMonth;
    public $showData = false;
    public $showData2 = false;
    public $showData3 = false;
    public $showData4 = false;
    public $incomeData = [];
    public $expenseData = [];
    public $incomeData3 = [];
    public $expenseData3 = [];
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
    public $userNameSelected2;
    public $userNameSelected3;
    public $userNameSelected4;
    public $totalIncome;
    public $totalExpense;

    public $totalIncome3;
    public $totalExpense3;

    public $totalCategoriesRender;
    public $operationsFetchMonths;
    public $totalMonthAmount;
    public $selectedMonthName;
    public $totalMonthAmountCurrency;

   public function months()
{
    $months = [];
    for ($i = 1; $i <= 12; $i++) {
        $monthName = Carbon::now()->month($i)->format('F');
        $months[] = [
            'number' => $i,
            'name' => $monthName,
        ];
    }
    return $months;
}



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
    
   $this->incomeData3 = [];
    $this->expenseData3 = [];

    for ($i = 1; $i <= 12; $i++) {
        // Consulta de ingresos
        $incomeData3[] = $this->fetchBetweenData(1, $i);

        // Consulta de gastos
        $expenseData3[] = $this->fetchBetweenData(2, $i);
    }

    $this->incomeData3 = $incomeData3;
    $this->expenseData3 = $expenseData3;

    $this->totalIncome3 = array_sum($incomeData3);
    $this->totalExpense3 = array_sum($expenseData3);
    $this->userNameSelected3 = User::find($this->selectedUser3);

    $this->categoryName = MainCategories::where('id', 1)->value('title');
    $this->categoryName2 = MainCategories::where('id', 2)->value('title');
    $this->showData3 = true;
}

private function fetchBetweenData($mainCategoryId, $month)
{
   $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', $mainCategoryId);

    if ($this->selectedUser3) {
        $query->where('operations.user_id', $this->selectedUser3);
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




  // REPORT GENERAL CATEGORIES 

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

        
    $this->userNameSelected2 = User::find($this->selectedUser2);
    
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



  // REPORT GENERAL MONTH TABLE

public function updateMonthData()
{
    $this->updateMonthDataInternal();
}

private function updateMonthDataInternal()
{
    $this->userNameSelected4 = User::find($this->selectedUser4);
    $this->operationsFetchMonths = $this->fetchMonthData();
    $this->totalMonthAmount = $this->fetchTotalMonthAmount(); 
    $this->totalMonthAmountCurrency = $this->fetchTotalMonthAmountCurrency(); 
    
    $this->showData4 = true;
    if ($this->selectedMonth) {
    $selectedDate = Carbon::create()->month($this->selectedMonth);
    $this->selectedMonthName = $selectedDate->format('F');
}

}


private function fetchTotalMonthAmountCurrency()
{
    return $this->operationsFetchMonths->sum('operation_currency_total');
}

private function fetchTotalMonthAmount()
{
    return $this->operationsFetchMonths->sum('operation_amount');
}


 private function fetchMonthData()
    {
        $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
            ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->join('statu_options', 'operations.operation_status', '=', 'statu_options.id'); 
        if ($this->selectedUser4) {
            $query->where('operations.user_id', $this->selectedUser4);
        }

        if ($this->selectedMonth) {
            $query->whereMonth('operations.operation_date', $this->selectedMonth);
        }

        if ($this->selectedYear3) {
            $query->whereYear('operations.operation_date', $this->selectedYear3);
        }

        return $query->select(
        'operations.operation_amount',
        'operations.operation_currency',
         'operations.operation_currency_total',
        'categories.category_name as category_title',
        'statu_options.status_description as status_description',
         'operations.operation_status as operation_status',
        'operations.operation_description',
        'main_categories.title as main_category_title'
    )->orderBy('operations.id', 'desc')->get();

    }



// FUNCIONT TO EXPORT EXCEL
 public function exportToExcel()
{
    // Lógica para exportar la tabla a Excel

    $this->emit('exportTableToExcel');
}


// FUNCIONT TO EXPORT EXCEL 2
 public function exportToExcel2()
{
    // Lógica para exportar la tabla a Excel

    $this->emit('exportTableToExcel2');
}

// FUNCIONT TO EXPORT EXCEL 3
 public function exportToExcel3()
{
    // Lógica para exportar la tabla a Excel

    $this->emit('exportTableToExcel3');
}


// FUNCIONT TO EXPORT EXCEL 4
 public function exportToExcel4()
{
    // Lógica para exportar la tabla a Excel

    $this->emit('exportTableToExcel4');
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
    $this->selectedUser2 = null;
    $this->selectedYear2 = null;
    $this->selectedCategoryId = null;
    $this->showData2 = false;
}

public function resetFields3()
{
    $this->selectedUser3 = null;
    
    $this->date_start = null;
    $this->date_end = null;
    $this->showData3 = false;
}


public function resetFields4()
{
    $this->selectedUser4 = null;
    $this->selectedYear3 = null;
    $this->selectedMonth = null;
    
    $this->showData4 = false;
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

