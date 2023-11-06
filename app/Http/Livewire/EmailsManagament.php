<?php

namespace App\Http\Livewire;
use App\Models\EmailManagement;
use Livewire\Component;
use Livewire\WithPagination;

class EmailsManagament extends Component
{
    public  $name, $email, $data_id;
 public $search = '';
 
    public $isOpen = 0;
     protected $listeners = ['render','delete']; 
   
    public function render()
    {
    $data = EmailManagement::where('user_id', auth()->user()->id)
        ->where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
        })
        ->orderBy('id', 'desc')
        ->paginate(10);

    return view('livewire.emails-managament', [
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
        'name' => 'required|string|max:40',
        'email' => 'required|email|string|max:40',
    ];

    $validatedData = $this->validate($validationRules);

    // Valida si el correo electrónico ya existe en la base de datos
    $existingEmail = EmailManagement::where('email', $this->email)->first();
    
    if ($existingEmail) {
        
        
        session()->flash('error', 'The email already exists in the database.');
    } else {
        // El correo electrónico no existe, puedes proceder a crear el registro
        $validatedData['user_id'] = auth()->user()->id;

        EmailManagement::updateOrCreate(['id' => $this->data_id], $validatedData);

        session()->flash('message', $this->data_id ? 'Data Updated Successfully.' : 'Data Created Successfully.');
    }

    

    $this->closeModal();
    $this->resetInputFields();
}


public function edit($id)
    {
        
        $list = EmailManagement::findOrFail($id);
        $this->data_id = $id;
        $this->name = $list->name;
        $this->email = $list->email;
       
     
        $this->openModal();
    }
    
public function delete($id)
    {
         
        EmailManagement::find($id)->delete();
        session()->flash('message', 'Data Deleted Successfully.');
    }

}
