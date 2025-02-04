<?php
namespace App\Http\Livewire;
use App\Models\Operation;
use App\Models\MainCategories;
use App\Models\EmailManagement;
use App\Models\User;
use App\Models\Category;
use App\Models\CategoriesToAssign;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use PDF;
use Illuminate\Support\Facades\Auth;


class ReportGeneralCategoriesTable extends Component
{
     
    public $years = [];
    public $categoryName,$categoryName2;
    public $selectedYear2;
    public $selectedUser2;
    public $selectedCategoryId;
    public $showData2 = false;
    public $ArrayCategories;
    public $users;
    public $categoriesRender;
    public $isOpen2 = 0;
    public $emails_user2 = [];
    public $emails;

   
    public $categoryNameSelected;
    public $userNameSelected2;
    public $totalCategoriesRender;
    public $totalCategoriesRenderCurrency;
   
    public $SelectMainCurrencyTypeRender = 'USD';

    protected $listeners = ['userSelected2','categorySelected','YearSelected2'];

    public function userSelected2($userId)
    {
        
    $this->mainCurrencyTypeRender = Operation::where('user_id', $userId)
    ->where('operation_currency_type', '!=', 'USD')
    ->distinct()
    ->pluck('operation_currency_type');
        $this->selectedUser2 = $userId;
        $this->updateCategoriesData();
    }

    public function categorySelected($categoryId)
    {
        
        $this->selectedCategoryId = $categoryId;
        $this->updateCategoriesData();
    }
    
    public function YearSelected2($selectedYear2Id)
    {
       
        $this->selectedYear2 = $selectedYear2Id;
        $this->updateCategoriesData();
    }

   
    public function dataSelect()
    {
        $this->years = Operation::distinct()->pluck('operation_year');
        
        $this->users = User::orderBy('id', 'desc')->get();
       
        $this->categoriesRender = $this->getCategoryOptions();
        $this->emails = EmailManagement::where('user_id', auth()->id())->get();

       
    }

    
    // ALL CATEGORIES
public function getCategoryOptions()
{
    $categoriesQuery = Category::join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->orderBy('categories.id', 'asc')
        ->select('categories.id', 'categories.category_name', 'main_categories.title as main_category_title');

    if (auth()->user()->hasRole('Admin')) {
       
        $categories = $categoriesQuery->get();
    } elseif (auth()->user()->hasRole('User')) {
    // Si es un usuario, obtener las categorías asignadas
    $categories = $categoriesQuery->leftJoin('categories_to_assigns', function ($join) {
            $join->on('categories.id', '=', 'categories_to_assigns.category_id')
                ->where('categories_to_assigns.user_id_assign', '=', auth()->user()->id);
        })
        ->select('categories.id', 'categories.category_name', 'main_categories.title as main_category_title')
        ->get();
}

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
        
        $this->dataSelect();
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
    $this->totalCategoriesRenderCurrency = 0;

    for ($i = 1; $i <= 12; $i++) {
        // Consulta de ingresos por categoría
        $income = $this->fetchCategoriesData(1, $i);

        // Consulta de gastos por categoría
        $expense = $this->fetchCategoriesData(2, $i);

        // Calcular la suma general
        $total = $income->sum('operation_amount') + $expense->sum('operation_amount');
        $totalCurrency = $income->sum('operation_currency_total') + $expense->sum('operation_currency_total');
       
        $this->ArrayCategories[] = [
            'month' => $i,
            'total' => $total,
            'totalCurrency' => $totalCurrency
        ];

        $this->totalCategoriesRender += $total;
        $this->totalCategoriesRenderCurrency += $totalCurrency;
    }

    $this->userNameSelected2 = User::find($this->selectedUser2);
    $this->categoryName = MainCategories::where('id', 1)->value('title');
    $this->categoryName2 = MainCategories::where('id', 2)->value('title');
    $this->showData2 = true;
}

private function fetchCategoriesData($mainCategoryId, $month)
{
    // Build the initial query with joins and main category condition
    $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', $mainCategoryId);

    // Apply additional conditions based on optional parameters
    if ($this->selectedUser2) {
        $query->where('operations.user_id', $this->selectedUser2);
    }

    if ($this->selectedCategoryId) {
        $query->where('operations.category_id', $this->selectedCategoryId);
    }

    if ($this->selectedYear2) {
        $query->whereYear('operations.operation_date', $this->selectedYear2);
    }

    // Fetch the category name based on the selected category ID
    $this->categoryNameSelected = Category::find($this->selectedCategoryId);

    // Execute the query and get the results
    $operations = $query
        ->whereMonth('operations.operation_date', $month)
        ->get();

    // Group the results by category and calculate totals
    $categoryTotal = $operations->groupBy('category_id')
        ->map(function ($group) {
            return [
                'operation_amount' => $group->sum('operation_amount'),
                'operation_currency_total' => $group->sum('operation_currency_total')
            ];
        });

    // Apply an additional filter 'SelectMainCurrencyTypeRender' if it's not 'USD'
if ($this->SelectMainCurrencyTypeRender && $this->SelectMainCurrencyTypeRender !== 'USD') {
    // Note: It seems there's an issue here, 'operations.operation_date' is not directly accessible
    // You may need to adjust this part based on your data structure
    // Group the results by category and calculate totals
    $categoryTotal = $operations->groupBy('category_id')
        ->map(function ($group) {
            return [
                'operation_currency_type' => $this->SelectMainCurrencyTypeRender,
                'operation_amount' => $group->sum('operation_amount'),
                'operation_currency_total' => $group->sum(function ($item) {
                    // Consider only the items with the selected currency type
                    return $item->operation_currency_type === $this->SelectMainCurrencyTypeRender
                        ? $item->operation_amount
                        : 0;
                })
            ];
        });
    }


    // Return the category totals
    return $categoryTotal; 
}




// FUNCIONT TO EXPORT EXCEL 2

public function exportToExcel2()
{
    // Lógica para exportar la tabla a Excel
    $userName = $this->userNameSelected2->name ?? '';
    $yearSelected = $this->selectedYear2 ?? '';
    $categoryNameSelected = $this->categoryNameSelected->category_name ?? '';

    // Emitir el evento y pasar las variables como parámetros
    $this->emit('exportTableToExcel2', [
        'userName' => $userName,
        'selectedYear2' => $yearSelected,
        'categoryNameSelected' => $categoryNameSelected,
    ]);
}


public function resetFields2()
{
    $this->selectedUser2 = null;
    $this->selectedYear2 = null;
    $this->selectedCategoryId = null;
    $this->showData2 = false;
}


// FUNCTION SEND REPORT TO USERS EMAILS


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

   private function resetInputFields2(){
        $this->emails_user2 = null;
    
    }

   public function emailStore2()
{
    $this->validate([
        'emails_user2' => 'required|array',
        'emails_user2.*' => 'email|max:50',
        'categoryNameSelected' => 'required', 
        'selectedYear2' => 'required', 
    ], [
        'emails_user2.required' => 'The :attribute field is required.',
        'emails_user2.array' => 'The :attribute must be an array.',
        'emails_user2.*.email' => 'The :attribute must be a valid email address.',
        'emails_user2.*.max' => 'The :attribute may not be greater than :max characters.',
        'categoryNameSelected.required' => 'The Category field is required',
        'selectedYear2.required' => 'Please select a year',
       
    ]);

    $user = User::find($this->selectedUser2);
    $userName = $user ? $user->name : 'User Not Selected';

    $datenow = Carbon::now('America/Argentina/Buenos_Aires')
        ->locale('es')
        ->isoFormat('dddd, D [de] MMMM [de] YYYY, H:mm:ss');

    $data = [
        'ArrayCategories' => $this->ArrayCategories,
        'totalCategoriesRender' => $this->totalCategoriesRender,
        'totalCategoriesRenderCurrency' => $this->totalCategoriesRenderCurrency,
        'selectedYear2' => $this->selectedYear2,
        'categoryNameSelected' => $this->categoryNameSelected,
        'user' => $userName,
        'title' => "Report Categories",
        'date' => $datenow,
        'currencyType' => $this->SelectMainCurrencyTypeRender,
    ];

    foreach ($this->emails_user2 as $email) {
        $data['email'] = $email; // Agregar la dirección de correo electrónico al array $data

        $fileName = 'General-PDF-' . $this->categoryNameSelected->category_name . '-Report' . '-' . $userName . '-' . $datenow . '.pdf';
        
        $pdf = PDF::loadView('emails.pdf-generalcategoriesreport', $data);
        
        Mail::send('emails.pdf-generalcategoriesreport', $data, function ($message) use ($data, $pdf, $fileName) {
            $message->to($data['email'], $data['email']) // Usar $data['email'] en lugar de $data["email"]
                ->subject($data['title'])
                ->attachData($pdf->output(), $fileName);
        });
    }

    session()->flash('message', __('messages.email_sent_successfully'));
    $this->closeModal2();
    $this->resetInputFields2();
    $this->dataSelect();
    $this->updateCategoriesData();
}

}
