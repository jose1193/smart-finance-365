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




class ReportGeneralCategoriesTable extends Component
{
     
    public $years = [];
    public $categoryName,$categoryName2;
    public $selectedYear2;
    public $selectedUser2;
    public $selectedCategoryId;
    public $showData2 = false;
    public $ArrayCategories = [];
    public $users;
    public $categoriesRender;
    public $isOpen2 = 0;
    public $emails_user2;
    public $emails;

   
    public $categoryNameSelected;
    public $userNameSelected2;
    public $totalCategoriesRender;
   
    

    public function mount()
    {
        $this->years = Operation::distinct()->pluck('operation_year');
        
        $this->users = User::orderBy('id', 'desc')->get();
       
        $this->categoriesRender = $this->getCategoryOptions();
    
        $this->emails = EmailManagement::where('user_id', auth()->id())->get();

      
    }

    
    // ALL CATEGORIES
public function getCategoryOptions()
{
    $categories = Category::join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->orderBy('categories.id', 'asc')
        ->select('categories.id', 'categories.category_name', 'main_categories.title as main_category_title')
        ->get();

    $formattedCategories = $categories->groupBy('main_category_title')->map(function ($categories, $mainCategoryTitle) {
        return [
            'mainCategoryTitle' => $mainCategoryTitle,
            'categories' => $categories,
        ];
    });

    return collect($formattedCategories);
}

    public function render()
    {
        return view('livewire.report-general-categories-table');
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


// FUNCIONT TO EXPORT EXCEL 2
 public function exportToExcel2()
{
    // Lógica para exportar la tabla a Excel

    $this->emit('exportTableToExcel2');
}


public function resetFields2()
{
    $this->selectedUser2 = null;
    $this->selectedYear2 = null;
    $this->selectedCategoryId = null;
    $this->showData2 = false;
}


// FUNCTION SEND REPORT TO USERS EMAILS
public function sendEmail2()
    {
        
       
        $this->openModal2();
         
    }

    public function openModal2()
    {
        $this->isOpen2 = true;
        $this->updateCategoriesData();
    }

    public function closeModal2()
    {
        $this->isOpen2 = false;
        $this->updateCategoriesData();
    }

   

    // FUNCTION EXCEL FILE EMAIL TO USER
    public function emailStore2()
    {
       $validationRules = [
        'emails_user2' => 'required|string|email|max:50',
        
    ];

    $validatedData = $this->validate($validationRules);
    
        Todo::updateOrCreate(['id' => $this->todo_id], [
            'emails_user2' => $this->emails_user2,
           
        ]);

   // Llamar al método emailSent4 para enviar el correo con el archivo Excel
        $this->emailSent2();
        session()->flash('message', 
            $this->todo_id ? 'Todo Updated Successfully.' : 'Todo Created Successfully.');

        

        $this->closeModal2();
       
    }

}
