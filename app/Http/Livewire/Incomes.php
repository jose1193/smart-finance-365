<?php

namespace App\Http\Livewire;
use App\Models\Category;
use App\Models\Income;
use Livewire\WithPagination;
use Livewire\Component;
use Carbon\Carbon;

class Incomes extends Component
{
    public  $income_description, $income_amount,$income_date,  $category_id, $data_id;
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
       $data = Income::join('categories', 'incomes.category_id', '=', 'categories.id')
     ->join('users', 'incomes.user_id', '=', 'users.id')
     ->where('users.id', auth()->id()) // Corregido
     ->where('incomes.income_description', 'like', '%' . $this->search . '%')
     ->select('incomes.*','categories.category_name')
     ->orderBy('incomes.id', 'desc')
     ->paginate(10);

     $this->categoriesRender = Category::where('main_category_id', 1)
                                  ->orderBy('id', 'asc')
                                  ->get();
       
        return view('livewire.incomes', [
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
        'income_description' => 'required|string|max:255',
        'income_amount' => 'required|numeric',
        'income_date' => 'required|date',
        'category_id' => 'required|exists:categories,id',
    ];

    $validatedData = $this->validate($validationRules);

    // Agregar user_id al array validado
    $validatedData['user_id'] = auth()->user()->id;

    // Calcular el mes y el año a partir de income_date usando Carbon
    $incomeDate = \Carbon\Carbon::createFromFormat('Y-m-d', $validatedData['income_date']);
    $validatedData['income_month'] = $incomeDate->format('m');
    $validatedData['income_year'] = $incomeDate->format('Y');

    Income::updateOrCreate(['id' => $this->data_id], $validatedData);

    session()->flash('message', $this->data_id ? 'Data Updated Successfully.' : 'Data Created Successfully.');

    $this->closeModal();
    $this->resetInputFields();
}


    public function edit($id)
    {
         $this->authorize('manage admin');
        $list = Income::findOrFail($id);
        $this->data_id = $id;
        $this->income_description = $list->income_description;
        $this->income_amount = $list->income_amount;
         $this->category_id = $list->category_id;
     
        $this->openModal();
    }
public function delete($id)
    {
         $this->authorize('manage admin');
        INcome::find($id)->delete();
        session()->flash('message', 'Data Deleted Successfully.');
    }


}
