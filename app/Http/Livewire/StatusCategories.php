<?php

namespace App\Http\Livewire;
use App\Models\StatuOptions;
use App\Models\MainCategories;
use Livewire\Component;
use Livewire\WithPagination;

class StatusCategories extends Component
{
    use WithPagination;
    
 public  $mainCategories, $main_category_id, $status_description, $data_id;
 public $search = '';
  public $isOpen = 0;
  protected $listeners = ['render','delete']; 
   


   

    public function render()
    {
    
        $user = auth()->user();
        if (!$user || !$user->hasRole('Admin')) {
            abort(403, 'This action is Forbidden.');
        }
$data = MainCategories::select('main_categories.*', 'statu_options.*', 'main_categories.title AS Title')
    ->join('statu_options', 'main_categories.id', '=', 'statu_options.main_category_id')
    ->where('statu_options.status_description', 'like', '%' . $this->search . '%')
    ->orderBy('main_categories.id', 'desc')
    ->paginate(10);

        return view('livewire.status-categories', [
            'data' => $data, 
        ]);
    }

    
    public function create()
    {
        
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->mainCategories = MainCategories::orderBy('id', 'desc')->get();
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
   

    $validationRules = [
        'main_category_id' => 'required|string|max:40',
        'status_description' => 'required|string|max:40',
    ];

    $validatedData = $this->validate($validationRules);

    // Valida si el correo electrónico ya existe en la base de datos
    $existingData = StatuOptions::where('status_description', $this->status_description)->first();
    
    if ($existingData) {
        
        
       session()->flash('error', __('messages.option_already_exists'));

    } else {
       
       
        StatuOptions::updateOrCreate(['id' => $this->data_id], $validatedData);

         session()->flash('message', 
    $this->data_id ? __('messages.data_updated_successfully') : __('messages.data_created_successfully'));
    }

    

    $this->closeModal();
    $this->resetInputFields();
}


public function edit($id)
    {
        
        $list = StatuOptions::findOrFail($id);
        $this->data_id = $id;
        $this->main_category_id = $list->main_category_id;
        $this->status_description = $list->status_description;
       
     
        $this->openModal();
    }
    
public function delete($id)
    {
         
        StatuOptions::find($id)->delete();
        session()->flash('message', __('messages.data_deleted_successfully'));

    }
}
