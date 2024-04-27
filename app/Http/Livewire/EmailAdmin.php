<?php

namespace App\Http\Livewire;
use App\Models\AdminEmail;
use Livewire\Component;
use Livewire\WithPagination;


class EmailAdmin extends Component
{
    use WithPagination;
    
 public  $name_support, $email, $data_id;
 public $search = '';
 
    public $isOpen = 0;
    protected $listeners = ['render','delete']; 

    

  public function authorize()
{
    return true;
}


   public function render()
{
   $user = auth()->user();
        if (!$user || !$user->hasRole('Admin')) {
            abort(403, 'This action is Forbidden.');
        }

    $data = AdminEmail::where('user_id', auth()->user()->id)
        ->where(function ($query) {
            $query->where('name_support', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
        })
        ->orderBy('id', 'desc')
        ->paginate(10);

    return view('livewire.email-admin', [
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
         $this->reset();
        $this->resetValidation(); 
    }

    private function resetInputFields(){
         $this->reset();
    }

public function store()
{
    $this->authorize('manage admin');

   $validationRules = [
    'name_support' => 'required|string|max:20|',
    'email' => 'required|email|string|max:40',
];


    $validatedData = $this->validate($validationRules);

    $existingEmail = AdminEmail::where('email', $this->email)->first();

    
if ($existingEmail && $existingEmail->id != $this->data_id) {
    session()->flash('info', 'The email already exists in the database.');
} else {
    // Asignar el ID del usuario a los datos validados
    $validatedData['user_id'] = auth()->user()->id;

    // Verificar si ya existe un correo electrónico registrado
    $existingCount = AdminEmail::count();

    if ($existingCount === 0 || $this->data_id) {
        if ($this->data_id) {
            // Si es una edición, permite cambiar el correo siempre que el nuevo correo no exista
            $existingOtherEmail = AdminEmail::where('email', $this->email)->where('id', '!=', $this->data_id)->first();
            if ($existingOtherEmail) {
                session()->flash('info', 'Email already exists for another record.');
            } else {
                // Actualiza tanto el email como el name_support
                AdminEmail::where('id', $this->data_id)->update([
                    'email' => $this->email,
                    'name_support' => $this->name_support
                ]);
                session()->flash('message', 'Data Updated Successfully.');
            }
        } else {
            // Si es nuevo y no hay registros, permite crear el correo
            AdminEmail::create($validatedData);
            session()->flash('message', 'Data Created Successfully.');
        }
    } else {
        session()->flash('info', 'Only one email can be registered.');
    }
}

    $this->closeModal();
    $this->resetInputFields();
}


public function edit($id)
    {
        $this->authorize('manage admin');
        $list = AdminEmail::findOrFail($id);
        $this->data_id = $id;
        $this->name_support = $list->name_support;
        $this->email = $list->email;
       
     
        $this->openModal();
    }
    
public function delete($id)
    {
         $this->authorize('manage admin');
        EmailManagement::find($id)->delete();
        session()->flash('message', 'Data Deleted Successfully.');
    }

}
