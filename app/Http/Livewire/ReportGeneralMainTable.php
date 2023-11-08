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


class ReportGeneralMainTable extends Component
{
     
    public $years = [];
    
    public $categoryName,$categoryName2;
    public $selectedYear;
    public $selectedUser;
    public $showData = false;
    public $incomeData = [];
    public $expenseData = [];
    public $incomeDataCurrency = [];
    public $expenseDataCurrency = [];
    
    public $users;
    public $isOpen = 0;
    public $emails_user = [];
    public $emails;

    
    public $userNameSelected;
   
    public $totalIncome;
    public $totalExpense;
    public $totalIncomeCurrency;
    public $totalExpenseCurrency;
    
    protected $listeners = ['userSelected','YearSelected'];

    public function userSelected($userId)
    {
        
        
        $this->selectedUser = $userId;
        $this->updateData();
    }

    public function YearSelected($selectedYearId)
    {
       
        $this->selectedYear = $selectedYearId;
        $this->updateData();
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
        return view('livewire.report-general-main-table');
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
    $this->incomeDataCurrency = [];
    $this->expenseDataCurrency = [];

    for ($i = 1; $i <= 12; $i++) {
        $data = $this->fetchData(1, $i);

        $this->incomeData[] = $data['operation_amount'];
        $this->incomeDataCurrency[] = $data['operation_currency_total'];

        $data = $this->fetchData(2, $i);

        $this->expenseData[] = $data['operation_amount'];
        $this->expenseDataCurrency[] = $data['operation_currency_total'];
    }

    $this->totalIncome = array_sum($this->incomeData);
    $this->totalIncomeCurrency = array_sum($this->incomeDataCurrency);
    $this->totalExpense = array_sum($this->expenseData);
    $this->totalExpenseCurrency = array_sum($this->expenseDataCurrency);
    
    

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

   $data = [
        'operation_amount' => $query
            ->whereMonth('operations.operation_date', $month)
            ->sum('operations.operation_amount'),
        'operation_currency_total' => $query
            ->whereMonth('operations.operation_date', $month)
            ->sum('operations.operation_currency_total')
    ];

    return $data;
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



// FUNCTION SEND REPORT TO USERS EMAILS

    public function openModal()
    {
        $this->isOpen = true;
        $this->updateData();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        
    }

    private function resetInputFields(){
        $this->emails_user = null;
    
    }


    // FUNCTION EXCEL FILE EMAIL TO USER
    public function emailStore()
    {
        
      
    $validationRules = [
    'emails_user' => 'required|array',
    'emails_user.*' => 'email|max:50',
    'selectedYear' => 'required', 
];

    $customMessages = [
    'selectedYear.required' => 'Please select a year',
    ];

    $this->validate($validationRules, $customMessages);



    $user = User::find($this->selectedUser);

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
    foreach ($this->emails_user as $email) {
    $data = [
        'incomeData' => $this->incomeData,
        'expenseData' => $this->expenseData,
        'incomeDataCurrency' => $this->incomeDataCurrency,
        'expenseDataCurrency' => $this->expenseDataCurrency,
        'totalIncome' => $this->totalIncome,
        'totalExpense' => $this->totalExpense,
        'totalIncomeCurrency' => $this->totalIncomeCurrency,
        'totalExpenseCurrency' => $this->totalExpenseCurrency,
        'selectedYear' => $this->selectedYear,
        'categoryName' => $this->categoryName,
        'categoryName2' => $this->categoryName2,
        'user' => $userName,
        'email' => $email, //emails arrays
        'title' => "Report General",
        'date' => $datenow,
    ];
   
    

    $pdf = PDF::loadView('emails.pdf-generalmainreport', $data );

    Mail::send('emails.pdf-generalmainreport', $data, function ($message) use ($data, $pdf, $fileName) {
    $message->to($data["email"], $data["email"])
        ->subject($data["title"])
        ->attachData($pdf->output(), $fileName); 
    });
    }
       
        session()->flash('message',  'Email Sent Successfully.');
        $this->closeModal();
        $this->resetInputFields();
        $this->dataSelect();
        $this->updateData();
        
    }


   


}

