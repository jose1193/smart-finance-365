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





// FUNCIONT TO EXPORT EXCEL 4
 public function exportToExcel4()
{
    // Lógica para exportar la tabla a Excel
 $this->updateMonthData();
    $this->emit('exportTableToExcel4');
}

//FUNCTION RESET FIELDS REPORT GENERAL


public function resetFields4()
{
    $this->selectedUser4 = null;
    $this->selectedYear3 = null;
    $this->selectedMonth = null;
    
    $this->showData4 = false;
}




// FUNCTION SEND REPORT TO USERS EMAILS

    public function sendEmail4()
    {
        
       
        $this->openModal4();
         
    }

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
       $validationRules = [
    'emails_user4' => 'required|array', 
    'emails_user4.*' => 'email|max:50', 
        
    ];

    $validatedData = $this->validate($validationRules);
    
    $user = User::find($this->selectedUser4);

    $data = [];
    
    if ($user) {
    $userName = $user->name; // Obtener el nombre del usuario si existe
    } else {
   $userName = 'User Not Selected'; 
    }

    $now = Carbon::now('America/Argentina/Buenos_Aires');
    $datenow = $now->format('Y-m-d H:i:s');
   
    $data['user'] = $userName; 
    $fileName = 'General-PDF-Report' . '-'.$userName. '-'. $datenow . '.pdf';
    foreach ($this->emails_user4 as $email) {
    $data = [
        'operationsFetchMonths' => $this->operationsFetchMonths,
        'totalMonthAmount' => $this->totalMonthAmount,
        'totalMonthAmountCurrency' => $this->totalMonthAmountCurrency,
        'selectedYear3' => $this->selectedYear3,
        'user' => $userName,
        'email' => $email, //emails arrays
        'title' => "Report General Month",
        'date' => $datenow,
    ];
   

    $pdf = PDF::loadView('emails.pdf-generalmonthreport', $data );

    Mail::send('emails.pdf-generalmonthreport', $data, function ($message) use ($data, $pdf, $fileName) {
    $message->to($data["email"], $data["email"])
        ->subject($data["title"])
        ->attachData($pdf->output(), $fileName); 
    });
        }
        session()->flash('message',  'Email Sent Successfully.');
        $this->closeModal4();
        $this->resetInputFields3();
        $this->dataSelect();
        $this->updateMonthData();
    }
   


}

