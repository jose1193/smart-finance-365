<?php

namespace App\Http\Livewire;
use App\Models\Category;
use App\Models\Operation;
use App\Models\StatuOptions;
use Livewire\WithPagination;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class DashboardTable extends Component
{
     public  $operation_description, $operation_amount,$operation_date, $operation_status, $category_id, $data_id;
 public $search = '';
 public $categoriesRender;
  public $statusOptionsRender;
    public $isOpen = 0;
     protected $listeners = ['render','delete']; 

    public function authorize()
{
    return true;
}

    public function render()
    {
       $data = Operation::join('categories', 'operations.category_id', '=', 'categories.id')
    ->join('users', 'operations.user_id', '=', 'users.id')
    ->join('main_categories', 'main_categories.id', '=', 'categories.main_category_id')
    ->join('statu_options', 'operations.operation_status', '=', 'statu_options.id') 
    ->where(function ($query) {
        $query->where('operations.operation_description', 'like', '%' . $this->search . '%')
            ->orWhere('categories.category_name', 'like', '%' . $this->search . '%')
            ->orWhere('users.name', 'like', '%' . $this->search . '%');
    })
    ->select('operations.*', 'categories.category_name', 'statu_options.status_description', 'users.name')
    ->orderBy('operations.id', 'desc')
    ->paginate(10);



   $this->categoriesRender = Category::join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
    ->orderBy('categories.id', 'asc')
    ->select('categories.id', 'categories.category_name', 'main_categories.title as main_category_title')
    ->get();


    $this->statusOptionsRender = StatuOptions::orderBy('id', 'asc')->get();
       
        return view('livewire.dashboard-table', [
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
        'operation_description' => 'required|string|max:255',
        'operation_amount' => 'required|numeric',
        'operation_status' => 'required',
        'operation_date' => 'required|date',
        'category_id' => 'required|exists:categories,id',
    ];

    $validatedData = $this->validate($validationRules);

    // Agregar user_id al array validado
    $validatedData['user_id'] = auth()->user()->id;

    // Calcular el mes y el año a partir de expense_date usando Carbon
    $operationDate = \Carbon\Carbon::createFromFormat('Y-m-d', $validatedData['operation_date']);
    $validatedData['operation_month'] = $operationDate->format('m');
    $validatedData['operation_year'] = $operationDate->format('Y');

    Operation::updateOrCreate(['id' => $this->data_id], $validatedData);

    session()->flash('message', $this->data_id ? 'Data Updated Successfully.' : 'Data Created Successfully.');

    $this->closeModal();
    $this->resetInputFields();
}


public function edit($id)
    {
         $this->authorize('manage admin');
        $list = Operation::findOrFail($id);
        $this->data_id = $id;
        $this->operation_description = $list->operation_description;
        $this->operation_amount = $list->operation_amount;
          $this->operation_status = $list->operation_status;
         $this->category_id = $list->category_id;
     
        $this->openModal();
    }
public function delete($id)
    {
         $this->authorize('manage admin');
        Operation::find($id)->delete();
        session()->flash('message', 'Data Deleted Successfully.');
    }

}
