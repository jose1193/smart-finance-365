<?php

namespace App\Http\Livewire;
use App\Models\Teacher;
use Livewire\WithPagination;
use Livewire\Component;

class Teachers extends Component
{
     use WithPagination;
    public  $name, $phone,$email, $data_id;
     public $search = '';
    public $isOpen = 0;
  protected $listeners = ['render','delete']; 

    public function authorize()
{
    return true;
}


   public function render()
{
   $teachers  = Teacher::where('name', 'like', '%' . $this->search . '%')->paginate(10);
    return view('livewire.teachers', [
            'teachers' => $teachers, // Pasar los resultados paginados a la vista
        ]);
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
        $this->validate([
            'name' => 'required|string|max:50',
            'email' => 'string|max:30',
            'phone' => 'string|max:30',
            
        ]);
    
        Teacher::updateOrCreate(['id' => $this->data_id], [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'user_id' => auth()->user()->id,
        ]);
   
        session()->flash('message', 
            $this->data_id ? 'Data Updated Successfully.' : 'Data Created Successfully.');
   
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
         $this->authorize('manage admin');
        $teachers = Teacher::findOrFail($id);
        $this->data_id = $id;
        $this->name = $teachers->name;
        $this->email = $teachers->email;
         $this->phone = $teachers->phone;
     
        $this->openModal();
    }

    public function delete($id)
    {
         $this->authorize('manage admin');
        Teacher::find($id)->delete();
        session()->flash('message', 'Data Deleted Successfully.');
    }
}
