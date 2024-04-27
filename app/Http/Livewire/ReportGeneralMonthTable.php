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
use Illuminate\Support\Facades\Mail;
use PDF;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class ReportGeneralMonthTable extends Component
{
     
    public $years = [];
    public $selectedYear3;
    public $selectedUser4;
    public $selectedCategoryId;
    public $selectedMonth;
    public $showData4 = false;
    public $users;
    public $isOpen4 = 0;
    public $emails_user4 = [];
    public $emails;
    public $userNameSelected4;
    public $operationsFetchMonths;
    public $totalMonthAmount;
    public $selectedMonthName;
    public $totalMonthAmountCurrency;

    public $main_category_id;
    public $date_start;
    public $date_end;
    
    public $SelectMainCurrencyTypeRender = 'USD';
    public $currencyType;
    public $mainCurrencyTypeRender;
    
    protected $listeners = ['userSelected4','MonthSelected','YearSelected3'];

    public function userSelected4($userId)
    {
        // Aquí puedes ejecutar la lógica que desees con el $userId
         $this->mainCurrencyTypeRender = Operation::where('user_id', $userId)
    ->where('operation_currency_type', '!=', 'USD')
    ->distinct()
    ->pluck('operation_currency_type');

        $this->selectedUser4 = $userId;
        $this->updateMonthData();
    }

     public function MonthSelected($selectedMonthId)
    {
       
        $this->selectedMonth = $selectedMonthId;
        $this->updateMonthData();
    }


    public function YearSelected3($selectedYear3Id)
    {
       
        $this->selectedYear3 = $selectedYear3Id;
        $this->updateMonthData();
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
        return view('livewire.report-general-month-table');
    }



  // REPORT GENERAL MONTH TABLE

public function updateMonthData()
{  $this->emit('initializeFlatpickr');
     $this->mainCategoriesRender = MainCategories::orderBy('id', 'asc')->get();
    $this->updateMonthDataInternal();
      $this->updateKey = now()->timestamp;
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
    
    $query = Operation::with(['category.mainCategories', 'status', 'operationSubcategories'])
        ->join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->join('statu_options', 'operations.operation_status', '=', 'statu_options.id')
        ->leftJoin('operation_subcategories', 'operation_subcategories.operation_id', '=', 'operations.id')
        ->leftJoin('subcategories', 'operation_subcategories.subcategory_id', '=', 'subcategories.id')
        ->when($this->selectedUser4, function ($query, $selectedUser4) {
            // Filtrar por usuario seleccionado
            return $query->where('operations.user_id', $selectedUser4);
        })
        ->when($this->selectedMonth, function ($query, $selectedMonth) {
            // Filtrar por mes seleccionado
            return $query->whereMonth('operations.operation_date', $selectedMonth);
        })
        ->when($this->selectedYear3, function ($query, $selectedYear3) {
            // Filtrar por año seleccionado
            return $query->whereYear('operations.operation_date', $selectedYear3);
        })
        ->when($this->main_category_id !== null && $this->main_category_id !== '', function ($query) {
         if ($this->main_category_id === 'No Category Income') {
        // Filtrar por nombre de la categoría si la categoría seleccionada es "No Category Income"
        return $query->where('categories.category_name', 'No Category Income');
         } elseif ($this->main_category_id === 'No Category Expense') {
        // Filtrar por nombre de la categoría si la categoría seleccionada es "No Category Expense"
        return $query->where('categories.category_name', 'No Category Expense');
         } else {
        // Filtrar por main_category_id si está presente y no es una cadena vacía
        return $query->where('main_categories.id', $this->main_category_id);
          }
        })->when($this->date_start && $this->date_end, function ($query) {
            // Filtrar por rango de fechas si se proporcionan ambas fechas
            return $query->whereBetween('operations.operation_date', [$this->date_start, $this->date_end]);
        })
        ->when($this->date_start && !$this->date_end, function ($query) {
            // Filtrar por fecha de inicio si se proporciona solo la fecha de inicio
            return $query->whereDate('operations.operation_date', '>=', $this->date_start);
        })
        ->when(!$this->date_start && $this->date_end, function ($query) {
            // Filtrar por fecha de fin si se proporciona solo la fecha de fin
            return $query->whereDate('operations.operation_date', '<=', $this->date_end);
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
            'subcategories.subcategory_name'
        )
        ->orderBy('operations.id', 'desc')
        ->get();

    return $query;
}





// FUNCIONT TO EXPORT EXCEL 4
 public function exportToExcel4()
{
    // Lógica para exportar la tabla a Excel
$this->updateMonthData();

    $userName = $this->userNameSelected4->name ?? ''; 
    $yearSelected = $this->selectedYear3 ?? ''; 
     $selectedMonthName = $this->selectedMonthName ?? ''; 


    // Emitir el evento y pasar las variables como parámetros
    $this->emit('exportTableToExcel4', [
        'userName' => $userName,
        'selectedYear3' => $yearSelected,
         'selectedMonthName' => $selectedMonthName,
    ]);

}

//FUNCTION RESET FIELDS REPORT GENERAL


public function resetFields4()
{
    $this->selectedUser4 = null;
    $this->selectedYear3 = null;
    $this->selectedMonth = null;
    $this->date_start = null;
    $this->date_end = null;
    $this->main_category_id = null;
    $this->showData4 = false;
}




// FUNCTION SEND REPORT TO USERS EMAILS

    public function openModal4()
    {
        $this->isOpen4 = true;
         $this->updateMonthData();
         
    }

    public function closeModal4()
    {
        $this->isOpen4 = false;
         $this->updateMonthData();
           
    }

    private function resetInputFields3(){
        $this->emails_user4 = null;
    }

    
public function emailStore4()
{
   
    $this->validate([
        'emails_user4' => 'required|array',
        'emails_user4.*' => 'email|max:50',
        'selectedMonth' => 'required',
        'selectedYear3' => 'required',
    ], [
        'selectedYear3.required' => 'Please select a year',
        'selectedMonth.required' => 'Please select a Month',
    ]);
    $this->updateMonthData();

    $user = User::find($this->selectedUser4);

    $data = [];

    if ($user) {
        $userName = $user->name;
    } else {
        $userName = 'User Not Selected';
    }

    $now = Carbon::now('America/Argentina/Buenos_Aires');
    $datenow = $now->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY, H:mm:ss');
    $dateStartFormatted = $this->date_start ? Carbon::parse($this->date_start)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') : null;
    $dateEndFormatted = $this->date_end ? Carbon::parse($this->date_end)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') : null;

    $data['user'] = $userName;
    $fileName = 'General-Month-PDF-Report' . '-' . $userName . '-' . $datenow . '.pdf';
    
   
    foreach ($this->emails_user4 as $email) {
        $data = [
            'operationsFetchMonths' => $this->operationsFetchMonths,
            'totalMonthAmount' => $this->totalMonthAmount,
            'totalMonthAmountCurrency' => $this->totalMonthAmountCurrency,
            'selectedYear3' => $this->selectedYear3,
            'user' => $userName,
            'email' => $email,
            'title' => "General Report Month",
            'date' => $datenow,
            'date_start' => $dateStartFormatted,
            'date_end' => $dateEndFormatted,
             'currencyType' => $this->SelectMainCurrencyTypeRender,
        ];

        $pdf = PDF::loadView('emails.pdf-generalmonthreport', $data);

        Mail::send('emails.pdf-generalmonthreport', $data, function ($message) use ($data, $pdf, $fileName) {
            $message->to($data["email"], $data["email"])
                    ->subject($data["title"])
                    ->attachData($pdf->output(), $fileName);
        });
    }

    session()->flash('message', __('messages.email_sent_successfully'));
    $this->closeModal4();
    $this->resetInputFields3();
      
    
}


}

