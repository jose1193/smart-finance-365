<?php

namespace App\Http\Livewire;
use App\Models\Category;
use App\Models\Expense;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
class Expenses extends Component
{
     public  $expense_description, $expense_amount,$expense_date,  $category_id, $data_id;
 public $search = '';
 public $categoriesRender;
    public $isOpen = 0;
     protected $listeners = ['render','delete']; 

    public function authorize()
{
    return true;
}
    public function render()
    {
        
        $data = Expense::join('categories', 'expenses.category_id', '=', 'categories.id')
     ->join('users', 'expenses.user_id', '=', 'users.id')
     ->where('users.id', auth()->id()) // Corregido
     ->where('expenses.expense_description', 'like', '%' . $this->search . '%')
     ->select('expenses.*','categories.category_name')
     ->orderBy('expenses.id', 'desc')
     ->paginate(10);

     $this->categoriesRender = Category::where('main_category_id', 2)
                                  ->orderBy('id', 'asc')
                                  ->get();
       
        return view('livewire.expenses', [
            'data' => $data]);
    }

    
    public function create()
    {
         $this->authorize('manage admin');
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields(){
         $this->reset();
    }

    
public function store()
{
    $this->authorize('manage admin');

    $validationRules = [
        'expense_description' => 'required|string|max:255',
        'expense_amount' => 'required|numeric',
        'expense_date' => 'required|date',
        'category_id' => 'required|exists:categories,id',
    ];

    $validatedData = $this->validate($validationRules);

    // Agregar user_id al array validado
    $validatedData['user_id'] = auth()->user()->id;

    // Calcular el mes y el año a partir de expense_date usando Carbon
    $expenseDate = \Carbon\Carbon::createFromFormat('Y-m-d', $validatedData['expense_date']);
    $validatedData['expense_month'] = $expenseDate->format('m');
    $validatedData['expense_year'] = $expenseDate->format('Y');

    Expense::updateOrCreate(['id' => $this->data_id], $validatedData);

    session()->flash('message', $this->data_id ? 'Data Updated Successfully.' : 'Data Created Successfully.');

    $this->closeModal();
    $this->resetInputFields();
}

public function edit($id)
    {
         $this->authorize('manage admin');
        $list = Expense::findOrFail($id);
        $this->data_id = $id;
        $this->expense_description = $list->expense_description;
        $this->expense_amount = $list->expense_amount;
         $this->category_id = $list->category_id;
     
        $this->openModal();
    }
public function delete($id)
    {
         $this->authorize('manage admin');
        Expense::find($id)->delete();
        session()->flash('message', 'Data Deleted Successfully.');
    }
}
