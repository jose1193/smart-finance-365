<?php

namespace App\Http\Livewire;
use App\Models\SupportContactForm;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\AdminEmail;
use Illuminate\Support\Facades\Mail;

class SupportContact extends Component
{
    use WithPagination;
    
    public  $name, $email,$emailFrom, $subject,$message,$user_id,$data_id;
    public $search = '';
    public $isOpen = 0;
    protected $listeners = ['render','delete','deleteMultiple']; 
    public $emails;
    public $name_from;

    public $selectAll = false;
    public $checkedSelected = [];

    public $selectedMessage,$subjectShow,$selectedMessageShow ;

    public function authorize()
{
    return true;
}

   
   public function render()
{
    $query = SupportContactForm::query();

    if (auth()->user()->hasRole('Admin')) {
        // Si es un administrador, muestra todos los mensajes
        $query->orderBy('id', 'desc');
    } else {
        
        $userEmail = auth()->user()->name;

        $query->where(function ($q) use ($userEmail) {
            $q->where('name_from', $userEmail)
                ->orWhere('name', $userEmail)
                ->orWhere('email', $userEmail);
        });
    }

    if ($this->search) {
        $query->where(function ($q) {
            $q->where('email', 'like', '%' . $this->search . '%')
                ->orWhere('name_from', 'like', '%' . $this->search . '%')
                ->orWhere('name', 'like', '%' . $this->search . '%')
                ->orWhere('subject', 'like', '%' . $this->search . '%')
                ->orWhere('message', 'like', '%' . $this->search . '%');
        });
    }

    $data = $query->paginate(10);

    return view('livewire.support-contact', ['data' => $data]);
}





    
    public function create()
    {
       
        $this->resetInputFields();
        $this->openModal();
        
       if (auth()->user()->hasRole('User')) {
    $firstAdminEmail = AdminEmail::orderBy('id', 'asc')->first();
    if ($firstAdminEmail) {
        $this->email = $firstAdminEmail->email;
        $this->name = $firstAdminEmail->name_support; 
        $this->name_from = auth()->user()->name; 
    }
    
} 
       
    if (auth()->user()->hasRole('Admin')) {
    $this->emails = User::where('email', '!=', auth()->user()->email)->orderBy('id', 'desc')->get();

    $lastAdminEmail = AdminEmail::orderBy('id', 'desc')->first();
    if ($lastAdminEmail) {
        $this->name_from = $lastAdminEmail->name_support; 
    }
}
     
    }

    public function updatedEmail($value)
    {
    $selectedUser = User::where('email', $value)->first();
    $this->name = $selectedUser->name;
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
    $validationRules = [
        'name' => 'required|string|max:20|regex:/^[A-Za-z\s]+$/',
        'email' => 'required|email|string|max:40',
        'subject' => 'required|string|max:40',
        'message' => 'required|string|max:255',
    ];

    $validatedData = $this->validate($validationRules, [
    'name.required' => 'El campo nombre es obligatorio.',
    'name.string' => 'El campo nombre debe ser una cadena de texto.',
    'name.max' => 'El campo nombre no debe superar los 20 caracteres.',
    'name.regex' => 'El campo nombre solo debe contener letras y espacios.',
    'email.required' => 'El campo email es obligatorio.',
]);

    // Asigna el user_id si el usuario está autenticado
    $validatedData['user_id'] = auth()->user()->id;
    $validatedData['name_from'] = $this->name_from;
    $validatedData['message'] = nl2br($this->message);
    //SEND EMAIL FORM CONTACT
       
   $emailFrom = '';

if (auth()->user()->hasRole('User')) {
    // Si el usuario actual es un usuario normal, obtén el correo electrónico del administrador
    $emailFrom =  auth()->user()->email;
} elseif (auth()->user()->hasRole('Admin')) {
    // Si el usuario actual es un administrador, usa el correo del usuario que está enviando el formulario
    $emailFrom =  AdminEmail::orderBy('email', 'desc')->limit(1)->pluck('email')->first(); // Había un error en esta línea
}


\Mail::send('emails.contactSupportMail', array(
    'name' => $this->name,
    'name2' => $this->name_from,
    'email' => $this->email,
    'emailFrom' => $emailFrom,
    'subject' => $this->subject,
    'message2' => $this->message,
), function($message) {
    $emailAdmin = AdminEmail::orderBy('email', 'desc')->limit(1)->pluck('email')->first();

    $message->from($emailAdmin, $this->name);
    $message->to($this->email)->subject($this->subject);
});
// END SEND EMAIL FORM CONTACT
    SupportContactForm::updateOrCreate(['id' => $this->data_id], $validatedData);
  

    session()->flash('message', $this->data_id ? 'Data Updated Successfully' : 'Data Created Successfully');

    $this->closeModal();
    $this->resetInputFields();
}

public function edit($id)
{
    
    $this->authorize('manage admin');
    $record = SupportContactForm::findOrFail($id);

    $this->data_id = $id;
    $this->name = $record->name;
    $this->email = $record->email;
    $this->subject = $record->subject;
    $this->message = $record->message;

    $this->openModal();
}

public function delete($id)
{
   
    SupportContactForm::find($id)->delete();
    session()->flash('message', 'Data Deleted Successfully');
}

 public function updatedSelectAll($value)
{
    if ($value) {
        $this->checkedSelected = $this->getItemsIds();
    } else {
        $this->checkedSelected = [];
    }
}

public function getItemsIds()
{
    // Retorna un array con los IDs de los elementos disponibles
    return SupportContactForm::pluck('id')->toArray();
}

public function confirmDelete()
{
    $this->emit('showConfirmation'); // Emite un evento para mostrar la confirmación
    
    
}

public function deleteMultiple()
{
    if (count($this->checkedSelected) > 0) {
        SupportContactForm::whereIn('id', $this->checkedSelected)->delete();
        $this->checkedSelected = [];
        session()->flash('message', 'Data Deleted Successfully');
        $this->selectAll = false;
    }
}


public function showMessage($messageId)
{
    $message = SupportContactForm::find($messageId);

    if ($message) {
      
        $this->selectedMessage = $message;
        $this->subjectShow = $message->subject;
        $this->selectedMessageShow = $message->message; 
        
    } 
}



}
