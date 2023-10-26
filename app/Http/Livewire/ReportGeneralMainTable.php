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



class ReportGeneralMainTable extends Component
{
     
    public $years = [];
    
    public $categoryName,$categoryName2;
    public $selectedYear;
    public $selectedUser;
    public $showData = false;
    public $incomeData = [];
    public $expenseData = [];
    
    
    public $users;
    public $isOpen = 0;
    public $emails_user;
    public $emails;

    
    public $userNameSelected;
   
   
    
    public $totalIncome;
    public $totalExpense;



    public function mount()
    {
        $this->years = Operation::distinct()->pluck('operation_year');
        
        $this->users = User::orderBy('id', 'desc')->get();
       
    
        $this->emails = EmailManagement::where('user_id', auth()->id())->get();

      
    }

    public function render()
    {
         
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
public function sendEmail()
    {
        
       
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->updateData();
    }

    public function closeModal()
    {
        $this->isOpen = false;
         $this->updateData();
    }

    private function resetInputFields(){
         $this->reset();
    }


    // FUNCTION EXCEL FILE EMAIL TO USER
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

