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

class ReportGeneralBetweenTable extends Component
{

    public $years = [];
    public $categoryName,$categoryName2;
    public $selectedUser3;
    public $showData3 = false;
    public $incomeData3 = [];
    public $expenseData3 = [];
    public $users;
    public $isOpen3 = 0;
    public $emails_user3;
    public $emails;
    public $date_start;
    public $date_end;
    public $userNameSelected3;
    public $totalIncome3;
    public $totalExpense3;

    


    public function render()
    {
        return view('livewire.report-general-between-table');
    }

    public function mount()
    {
       
        
        $this->users = User::orderBy('id', 'desc')->get();
       
      
    
        $this->emails = EmailManagement::where('user_id', auth()->id())->get();

      
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

// FUNCIONT TO EXPORT EXCEL 3
 public function exportToExcel3()
{
    // Lógica para exportar la tabla a Excel

    $this->emit('exportTableToExcel3');
}



   

public function resetFields3()
{
    $this->selectedUser3 = null;
    
    $this->date_start = null;
    $this->date_end = null;
    $this->showData3 = false;
}



// FUNCTION SEND REPORT TO USERS EMAILS
public function sendEmail3()
    {
        
       
        $this->openModal3();
         
    }

    public function openModal3()
    {
        $this->isOpen3 = true;
        $this->updateBetweenData();
    }

    public function closeModal3()
    {
        $this->isOpen3 = false;
        $this->updateBetweenData();
    }

   

    // FUNCTION EXCEL FILE EMAIL TO USER
    public function emailStore3()
    {
       $validationRules = [
        'emails_user3' => 'required|string|email|max:50',
        
    ];

    $validatedData = $this->validate($validationRules);
    
        Todo::updateOrCreate(['id' => $this->todo_id], [
            'emails_user3' => $this->emails_user3,
           
        ]);

   // Llamar al método emailSent4 para enviar el correo con el archivo Excel
        $this->emailSent3();
        session()->flash('message', 
            $this->todo_id ? 'Todo Updated Successfully.' : 'Todo Created Successfully.');

        

        $this->closeModal3();
       
    }


}
