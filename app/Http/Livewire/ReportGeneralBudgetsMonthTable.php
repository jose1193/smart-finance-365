<?php

namespace App\Http\Livewire;
use App\Models\Operation;
use App\Models\MainCategories;
use App\Models\EmailManagement;
use App\Models\User;
use App\Models\Category;
use App\Models\Budget;
use App\Models\BudgetExpense;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PDF;
use Illuminate\Support\Facades\Auth;

class ReportGeneralBudgetsMonthTable extends Component
{
    public $years = [];
    public $selectedYear5;
    public $selectedUser6;
    public $selectedCategoryId;
    public $selectedMonthBudget;
    public $showData6 = false;
    public $users;
    public $isOpen4 = 0;
    public $emails_user6 = [];
    public $emails;
    public $userNameSelected4;
    public $operationsFetchMonths;
    public $totalMonthAmount;
    public $selectedMonthName;
    public $totalMonthAmountCurrency;

    public $SelectMainCurrencyTypeRender = 'USD';
    public $mainCurrencyTypeRender;
    
    protected $listeners = ['userSelected6','MonthSelectedBudget','YearSelected5'];

    public function userSelected6($userId)
    {
        // Aquí puedes ejecutar la lógica que desees con el $userId
    $this->mainCurrencyTypeRender = Operation::where('user_id', $userId)
    ->where('operation_currency_type', '!=', 'USD')
    ->distinct()
    ->pluck('operation_currency_type');

        $this->selectedUser6 = $userId;
        $this->updateBudgetMonthData();
    }

     public function MonthSelectedBudget($selectedMonthId)
    {
       
        $this->selectedMonthBudget = $selectedMonthId;
        $this->updateBudgetMonthData();
    }


    public function YearSelected5($selectedYear3Id)
    {
       
        $this->selectedYear5 = $selectedYear3Id;
        $this->updateBudgetMonthData();
    }

   public function months()
{
    $currentYear = Carbon::now()->year;
    $months = [];

    for ($i = 1; $i <= 12; $i++) {
        $dateInMonth = Carbon::create($currentYear, $i, 1);
        $monthName = $dateInMonth->format('F');

        $months[] = [
            'number' => $i,
            'name' => $monthName,
        ];
    }

    return $months;
}


    public function dataSelect()
    {
        $this->years = Operation::distinct()->pluck('operation_year');
        
        $this->users = User::orderBy('id', 'desc')->get();
       
    
        $this->emails = EmailManagement::where('user_id', auth()->id())->get();

    }


    public function render()
    {
        $this->dataSelect();
        return view('livewire.report-general-budgets-month-table');
    }

    
  // REPORT GENERAL MONTH TABLE

public function updateBudgetMonthData()
{
    $this->updateBudgetMonthDataInternal();
      $this->updateKey = now()->timestamp;
}

private function updateBudgetMonthDataInternal()
{
    $this->userNameSelected4 = User::find($this->selectedUser6);
    $this->operationsFetchMonths = $this->fetchMonthData();
    $this->totalMonthAmount = $this->fetchTotalMonthAmount(); 
    $this->totalMonthAmountCurrency = $this->fetchTotalMonthAmountCurrency(); 
    
    $this->showData6 = true;

    if ($this->selectedMonthBudget) {
        $selectedDate = Carbon::create()->month($this->selectedMonthBudget);
        $this->selectedMonthName = $selectedDate->translatedFormat('F');

    } else {
        $this->selectedMonthName = ''; 
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
   $query = Operation::with(['category.mainCategories', 'status', 'operationSubcategories', 'budgetExpenses']) 
    ->join('categories', 'operations.category_id', '=', 'categories.id')
    ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
    ->join('statu_options', 'operations.operation_status', '=', 'statu_options.id')
    ->leftJoin('operation_subcategories', 'operation_subcategories.operation_id', '=', 'operations.id') 
    ->leftJoin('subcategories', 'operation_subcategories.subcategory_id', '=', 'subcategories.id') 
    ->leftJoin('budget_expenses', 'operations.id', '=', 'budget_expenses.operation_id')
    ->leftJoin('budgets', 'budgets.id', '=', 'budget_expenses.budget_id')
    ->leftJoin('categories as budget_category', 'budget_category.id', '=', 'budget_expenses.category_id')
    ->whereHas('category.mainCategories', function ($query) {
    $query->where('id', 2); // Utiliza la clave foránea correcta
    })

    ->when($this->selectedUser6, function ($query, $selectedUser6) {
        return $query->where('operations.user_id', $selectedUser6);
    })
    ->when($this->selectedMonthBudget, function ($query, $selectedMonthBudget) {
        return $query->whereMonth('operations.operation_date', $selectedMonthBudget);
    })
    ->when($this->selectedYear5, function ($query, $selectedYear5) {
        return $query->whereYear('operations.operation_date', $selectedYear5);
    })
    ->when($this->SelectMainCurrencyTypeRender && $this->SelectMainCurrencyTypeRender !== 'USD', function ($query) {
            // Apply currency type filter if SelectMainCurrencyTypeRender is set and not 'USD'
            return $query->where('operations.operation_currency_type', $this->SelectMainCurrencyTypeRender);
        })
    ->select(
        'operations.operation_amount',
        'operations.operation_currency',
        'operations.operation_currency_total',
        'categories.category_name as category_title',
        'statu_options.status_description as status_description',
        'operations.operation_status as operation_status',
        'operations.operation_description',
        'operations.operation_date',
        'operations.operation_currency_type',
        'main_categories.title as main_category_title',
        'subcategories.subcategory_name',
        'budgets.budget_currency_total as budget_operation',
         'budgets.budget_currency_type as budget_currency_type',
        'budgets.budget_date as date',
        'budget_expenses.budget_id',
        
    )
    ->orderBy('operations.id', 'desc')
    ->get();

    return $query;

}




// FUNCIONT TO EXPORT EXCEL 4
 public function exportToExcel6()
{
    // Lógica para exportar la tabla a Excel
 $this->updateBudgetMonthData();
    $userName = $this->userNameSelected4->name ?? ''; 
    $yearSelected = $this->selectedYear5 ?? ''; 
     $selectedMonthName = $this->selectedMonthName ?? ''; 


    // Emitir el evento y pasar las variables como parámetros
    $this->emit('exportTableToExcel6', [
        'userName' => $userName,
        'selectedYear5' => $yearSelected,
         'selectedMonthName' => $selectedMonthName,
    ]);
}

//FUNCTION RESET FIELDS REPORT GENERAL


public function resetFields6()
{
    $this->selectedUser6 = null;
    $this->selectedYear5 = null;
    $this->selectedMonthBudget = null;
    
    $this->showData6 = false;
}




// FUNCTION SEND REPORT TO USERS EMAILS

    public function openModal6()
    {
        $this->isOpen4 = true;
         $this->updateBudgetMonthData();
    }

    public function closeModal4()
    {
        $this->isOpen4 = false;
         $this->updateBudgetMonthData();
           
    }

    private function resetInputFields3(){
        $this->emails_user6 = null;
    }

    
public function emailStore6()
{
    
    $this->validate([
        'emails_user6' => 'required|array',
        'emails_user6.*' => 'email|max:50',
        'selectedMonthBudget' => 'required',
        'selectedYear5' => 'required',
    ], [
        'selectedYear5.required' => 'Please select a year',
        'selectedMonthBudget.required' => 'Please select a Month',
    ]);

    $this->updateBudgetMonthData();

    $user = User::find($this->selectedUser6);

    $data = [];

    if ($user) {
        $userName = $user->name;
    } else {
        $userName = 'User Not Selected';
    }

    $now = Carbon::now('America/Argentina/Buenos_Aires');
    $datenow = $now->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY, H:mm:ss');

    $data['user'] = $userName;
    $fileName = 'Budget-Expense-Monthly-Report' . '-' . $userName . '-' . $datenow . '.pdf';

    foreach ($this->emails_user6 as $email) {
        $data = [
            'operationsFetchMonths' => $this->operationsFetchMonths,
            'totalMonthAmount' => $this->totalMonthAmount,
            'totalMonthAmountCurrency' => $this->totalMonthAmountCurrency,
            'userNameSelected4' => $this->userNameSelected4,
            'selectedYear5' => $this->selectedYear5,
            'selectedMonthName' => $this->selectedMonthName,
            'user' => $userName,
            'email' => $email,
            'title' => "Budget Expense Monthly Report",
            'date' => $datenow,
             'currencyType' => $this->SelectMainCurrencyTypeRender,
        ];

        $pdf = PDF::loadView('emails.pdf-generalbudgetmonthreport', $data);

        Mail::send('emails.pdf-generalbudgetmonthreport', $data, function ($message) use ($data, $pdf, $fileName) {
            $message->to($data["email"], $data["email"])
                    ->subject($data["title"])
                    ->attachData($pdf->output(), $fileName);
        });
    }

    session()->flash('message', __('messages.email_sent_successfully'));
    $this->closeModal4();
    $this->resetInputFields3();
    $this->dataSelect();
    
}

}
