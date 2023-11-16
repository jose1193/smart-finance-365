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

class ReportGeneralBetweenTable extends Component
{

    public $years = [];
    public $categoryName,$categoryName2;
    public $selectedUser3;
    public $showData3 = false;
    public $incomeData3 = [];
    public $expenseData3 = [];
    public $incomeDataCurrency3 = [];
    public $expenseDataCurrency3= [];
    public $users;
    public $isOpen3 = 0;
    public $emails_user3 = [];
    public $emails;
    public $date_start;
    public $date_end;
    public $userNameSelected3;
    public $totalIncome3;
    public $totalExpense3;
    public $totalIncomeCurrency3;
    public $totalExpenseCurrency3;

    protected $listeners = ['userSelected3'];

    public function userSelected3($userId)
    {
        // Aquí puedes ejecutar la lógica que desees con el $userId
        $this->selectedUser3 = $userId;
        $this->updateBetweenData();
    }

    
    
public function dataSelect()
    {
       
        
        $this->users = User::orderBy('id', 'desc')->get();
       
    
        $this->emails = EmailManagement::where('user_id', auth()->id())->get();

       
    }

    public function render()
    {
        $this->dataSelect();
        return view('livewire.report-general-between-table');
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
    $this->incomeDataCurrency3 = [];
    $this->expenseDataCurrency3 = [];

    for ($i = 1; $i <= 12; $i++) {
        $data = $this->fetchBetweenData(1, $i);

        $this->incomeData3[] = $data->sum('operation_amount');
        $this->incomeDataCurrency3[] = $data->sum('operation_currency_total');

        $data = $this->fetchBetweenData(2, $i);

        $this->expenseData3[] = $data->sum('operation_amount');
        $this->expenseDataCurrency3[] = $data->sum('operation_currency_total');
    }

    $this->totalIncome3 = array_sum($this->incomeData3);
    $this->totalIncomeCurrency3 = array_sum($this->incomeDataCurrency3);
    $this->totalExpense3 = array_sum($this->expenseData3);
    $this->totalExpenseCurrency3 = array_sum($this->expenseDataCurrency3);

    $this->userNameSelected3 = User::find($this->selectedUser3);

    $this->categoryName = MainCategories::where('id', 1)->value('title');
    $this->categoryName2 = MainCategories::where('id', 2)->value('title');
    $this->showData3 = true;
}

private function fetchBetweenData($mainCategoryId, $month)
{
    // Verificar si las fechas están invertidas
    if ($this->date_start && $this->date_end && $this->date_start > $this->date_end) {
        session()->flash('error', 'Error: La fecha de inicio no puede ser posterior a la fecha de finalización.');
        return collect(); // Devolver una colección vacía en caso de error
    }

    $query = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', $mainCategoryId);

    if ($this->selectedUser3) {
        $query->where('operations.user_id', $this->selectedUser3);
    }

    if ($this->date_start && $this->date_end) {
        $query->whereBetween('operations.operation_date', [$this->date_start, $this->date_end]);
    } elseif ($this->date_start) {
        $query->whereDate('operations.operation_date', '>=', $this->date_start);
    } elseif ($this->date_end) {
        $query->whereDate('operations.operation_date', '<=', $this->date_end);
    }

    return $query
        ->whereMonth('operations.operation_date', $month)
        ->get()
        ->groupBy('category_id')
        ->map(function ($group) {
            return [
                'operation_amount' => $group->sum('operation_amount'),
                'operation_currency_total' => $group->sum('operation_currency_total')
            ];
        });
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

    private function resetInputFields3(){
        $this->emails_user3 = null;
    
    }


    public function emailStore3()
{
    $this->validate([
        'emails_user3' => 'required|array',
        'emails_user3.*' => 'email|max:50',
        'date_start' => 'required',
        'date_end' => 'required',
    ], [
        'emails_user3.required' => 'The :attribute field is required.',
        'emails_user3.array' => 'The :attribute must be an array.',
        'emails_user3.*.email' => 'The :attribute must be a valid email address.',
        'emails_user3.*.max' => 'The :attribute may not be greater than :max characters.',
        'date_start.required' => 'Please select a Date Start',
        'date_end.required' => 'Please select a Date End',
        // ... Otros mensajes personalizados aquí
    ]);

    $user = User::find($this->selectedUser3);
    $userName = $user ? $user->name : 'User Not Selected';

    $now = Carbon::now('America/Argentina/Buenos_Aires');
    $datenow = $now->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY, H:mm:ss');
    $dateStartFormatted = $this->date_start ? Carbon::parse($this->date_start)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') : null;
    $dateEndFormatted = $this->date_end ? Carbon::parse($this->date_end)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') : null;

    $data = [
        'incomeData3' => $this->incomeData3,
        'expenseData3' => $this->expenseData3,
        'incomeDataCurrency3' => $this->incomeDataCurrency3,
        'expenseDataCurrency3' => $this->expenseDataCurrency3,
        'totalIncome3' => $this->totalIncome3,
        'totalExpense3' => $this->totalExpense3,
        'totalIncomeCurrency3' => $this->totalIncomeCurrency3,
        'totalExpenseCurrency3' => $this->totalExpenseCurrency3,
        'date_start' => $dateStartFormatted,
        'date_end' => $dateEndFormatted,
        'categoryName' => $this->categoryName,
        'categoryName2' => $this->categoryName2,
        'user' => $userName,
        'title' => "Report General Between Dates",
        'date' => $datenow,
    ];

    foreach ($this->emails_user3 as $email) {
        $data['email'] = $email; // Agregar la dirección de correo electrónico al array $data

        $fileName = 'General-PDF-Report-Between-Dates-' . '-' . $userName . '-' . $datenow . '.pdf';
        
        $pdf = PDF::loadView('emails.pdf-generalbetweenreport', $data);
        
        Mail::send('emails.pdf-generalbetweenreport', $data, function ($message) use ($data, $pdf, $fileName) {
            $message->to($data['email'], $data['email'])
                ->subject($data['title'])
                ->attachData($pdf->output(), $fileName);
        });
    }

    session()->flash('message', 'Email Sent Successfully.');
    $this->closeModal3();
    $this->resetInputFields3();
    $this->dataSelect();
    $this->updateBetweenData();
}



}
