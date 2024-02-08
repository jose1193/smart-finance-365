<?php

namespace App\Http\Livewire;
use Livewire\Component;
use App\Models\Operation;
use App\Models\MainCategories;
use App\Models\EmailManagement;
use App\Models\User;
use App\Models\Budget;
use App\Models\BudgetExpense;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use PDF;
use Illuminate\Support\Facades\Auth;

class ReportGeneralBudgetsTable extends Component
{
     public $years = [];
    
    public $categoryName,$categoryName2;
    public $selectedYear4;
    public $selectedUser5;
    public $showData = false;
    public $incomeData = [];
    public $expenseData = [];
    public $incomeDataCurrency = [];
    public $expenseDataCurrency = [];
    
    public $users;
    public $isOpen = 0;
    public $emails_user5 = [];
    public $emails;

    
    public $userNameSelected;
   
    public $totalIncome;
    public $totalExpense;
    public $totalIncomeCurrency;
    public $totalExpenseCurrency;
    
    public $budgetDataCurrency = [];
    public $totalBudgetCurrency = [];

    public $date_start;
    public $date_end;

    protected $listeners = ['userSelected5','YearSelected4'];

    public $SelectMainCurrencyTypeRender = 'USD';
       
    public function mount() {
        $this->dataSelectBudget();
    }

    public function render()
    {
        return view('livewire.report-general-budgets-table');
    }

    public function userSelected5($userId)
    {
        
         $this->mainCurrencyTypeRender = Operation::where('user_id', $userId)
    ->where('operation_currency_type', '!=', 'USD')
    ->distinct()
    ->pluck('operation_currency_type');

        $this->selectedUser5 = $userId;
        $this->updateDataBudget();
    }

    public function YearSelected4($selectedYearId)
    {
       
        $this->selectedYear4 = $selectedYearId;
        $this->updateDataBudget();
    }

    public function dataSelectBudget()
    {
        $this->years = Operation::distinct()->pluck('operation_year');
        
        $this->users = User::orderBy('id', 'desc')->get();
       
    
        $this->emails = EmailManagement::where('user_id', auth()->id())->get();

       
    }

    
   // REPORT GENERAL TABLE
public function updateDataBudget()
{
    $this->emit('initializeFlatpickr2');
    $this->updateDataBudgetInternal();
  

    
}

private function updateDataBudgetInternal()
{
    $this->incomeData = [];
    $this->expenseData = [];
    $this->incomeDataCurrency = [];
    $this->expenseDataCurrency = [];
    $this->budgetDataCurrency = [];

    for ($i = 1; $i <= 12; $i++) {
        $data = $this->fetchData(1, $i);

        $this->incomeData[] = $data['operation_total'];
        $this->incomeDataCurrency[] = $data['operation_total'];

        $data = $this->fetchData(2, $i);

        $this->expenseData[] = $data['operation_total'];
        $this->expenseDataCurrency[] = $data['operation_total'];

        $data = $this->fetchBudgetData($i);

        $this->budgetDataCurrency[] = $data['budget_currency_total'];
    }

    $this->totalIncome = array_sum($this->incomeData);
    $this->totalIncomeCurrency = array_sum($this->incomeDataCurrency);
    $this->totalExpense = array_sum($this->expenseData);
    $this->totalExpenseCurrency = array_sum($this->expenseDataCurrency);
    
    $this->totalBudgetCurrency = array_sum($this->budgetDataCurrency);

    $this->userNameSelected = User::find($this->selectedUser5);

    $this->categoryName = MainCategories::where('id', 1)->value('title');
    $this->categoryName2 = MainCategories::where('id', 2)->value('title');
    $this->showData = true;
}


private function fetchData($mainCategoryId, $month)
{
    $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', $mainCategoryId);

    if ($this->selectedUser5) {
        $query->where('operations.user_id', $this->selectedUser5);
    }

    if ($this->selectedYear4) {
        $query->whereYear('operations.operation_date', $this->selectedYear4);
    }
 
    if ($this->date_start && $this->date_end) {
        $query->whereBetween('operations.operation_date', [$this->date_start, $this->date_end]);
    } elseif ($this->date_start) {
        $query->whereDate('operations.operation_date', '>=', $this->date_start);
    } elseif ($this->date_end) {
        $query->whereDate('operations.operation_date', '<=', $this->date_end);
    }

   // Calcula la suma de 'operation_currency_total' sin aplicar el filtro
    $data['operation_total'] = $query
        ->whereMonth('operations.operation_date', $month)
        ->sum('operations.operation_currency_total');

    // Aplica el filtro 'SelectMainCurrencyTypeRender' si es diferente de 'USD'
    if ($this->SelectMainCurrencyTypeRender && $this->SelectMainCurrencyTypeRender !== 'USD') {
        $data['operation_total'] = $query
            ->whereMonth('operations.operation_date', $month)
            ->where('operations.operation_currency_type', $this->SelectMainCurrencyTypeRender)
            ->sum('operations.operation_amount');
    }

    return $data;
}

private function fetchBudgetData($month)
{
  $query = Budget::where('user_id', $this->selectedUser5);

    if ($this->selectedYear4) {
        $query->whereYear('budget_date', $this->selectedYear4);
    }

    $data = [
        'budget_currency_total' => $query
            ->whereMonth('budget_date', $month)
            ->sum('budget_currency_total')
    ];

    // Aplica el filtro 'SelectMainCurrencyTypeRender' si es diferente de 'USD'
    if ($this->SelectMainCurrencyTypeRender && $this->SelectMainCurrencyTypeRender !== 'USD') {
        $data = [
        'budget_currency_total' => $query
            ->whereMonth('budget_date', $month)
              ->where('budget_currency_type', $this->SelectMainCurrencyTypeRender)
            ->sum('budget_operation')
    ];

    }
    
    return $data;
}


// FUNCIONT TO EXPORT EXCEL
public function exportToExcel5()
{
    // Obtener los valores de $userNameSelected->name y $selectedYear4
    $userName = $this->userNameSelected->name ?? ''; // Asigna '' si $userNameSelected o su propiedad name están vacíos
    $yearSelected = $this->selectedYear4 ?? ''; // Asigna '' si $selectedYear4 está vacío


    // Emitir el evento y pasar las variables como parámetros
    $this->emit('exportTableToExcel5', [
        'userName' => $userName,
        'selectedYear4' => $yearSelected,
    ]);
}




//FUNCTION RESET FIELDS REPORT GENERAL
public function resetFields5()
{
    $this->selectedUser5 = null;
    $this->selectedYear4 = null;
    $this->date_start = null;
    $this->date_end = null;
    $this->showData = false;
}



// FUNCTION SEND REPORT TO USERS EMAILS

    public function openModal()
    {
        $this->isOpen = true;
        $this->updateDataBudget();
    }

    public function closeModal5()
    {
        $this->isOpen = false;
        
         
        
    }

    private function resetInputFields5(){
        $this->emails_user5 = null;
        
        
        
    
    }

     // FUNCTION FILE EMAIL TO USER
public function emailStore5()
{
    $this->validate([
        'emails_user5' => 'required|array',
        'emails_user5.*' => 'email|max:50',
        'selectedYear4' => 'required', 
    ], [
        'selectedYear4.required' => 'Please select a year',
    ]);

    $user = User::find($this->selectedUser5);
    $userName = $user ? $user->name : 'User Not Selected';

    $now = Carbon::now('America/Argentina/Buenos_Aires');
    $datenow = $now->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');
    $dateStartFormatted = $this->date_start ? Carbon::parse($this->date_start)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') : null;
    $dateEndFormatted = $this->date_end ? Carbon::parse($this->date_end)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') : null;

    $data = [
        'incomeData' => $this->incomeData,
        'expenseData' => $this->expenseData,
        'budgetDataCurrency' => $this->budgetDataCurrency,
        'incomeDataCurrency' => $this->incomeDataCurrency,
        'expenseDataCurrency' => $this->expenseDataCurrency,
        'totalIncome' => $this->totalIncome,
        'totalExpense' => $this->totalExpense,
        'totalIncomeCurrency' => $this->totalIncomeCurrency,
        'totalExpenseCurrency' => $this->totalExpenseCurrency,
        'totalBudgetCurrency' => $this->totalBudgetCurrency,
        'selectedYear4' => $this->selectedYear4,
        'categoryName' => $this->categoryName,
        'categoryName2' => $this->categoryName2,
        'user' => $userName,
        'title' => "General Report",
        'date' => $datenow,
        'date_start' => $dateStartFormatted,
        'date_end' => $dateEndFormatted,
         'currencyType' => $this->SelectMainCurrencyTypeRender,
    ];

    foreach ($this->emails_user5 as $email) {
        $data['email'] = $email; // Agregar la dirección de correo electrónico al array $data

        $fileName = 'General-PDF-Report-' . $userName . '-' . $datenow . '.pdf';

        $pdf = PDF::loadView('emails.pdf-generalbudgetreport', $data);

        Mail::send('emails.pdf-generalbudgetreport', $data, function ($message) use ($data, $pdf, $fileName) {
            $message->to($data['email'], $data['email'])
                ->subject($data['title'])
                ->attachData($pdf->output(), $fileName);
        });
    }

    session()->flash('message', 'Email Sent Successfully.');
    $this->closeModal5();
    $this->resetInputFields5();
    $this->dataSelectBudget();
    $this->updateDataBudget();
}



}
