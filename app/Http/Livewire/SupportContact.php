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
    public  $name, $email, $subject,$message,$user_id,$data_id;
    public $search = '';
    public $isOpen = 0;
    protected $listeners = ['render','delete']; 
    public $emails;
    public $name_from;

    public function authorize()
{
    return true;
}

   

   

   public function render()
{
    $query = SupportContactForm::query();

    if (auth()->user()->hasRole('User')) {
        $query->where('user_id', auth()->user()->id);
    } elseif (auth()->user()->hasRole('Admin')) {
        $query->orderBy('id', 'desc');
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
    }

    private function resetInputFields(){
         $this->reset();
    }

    public function store()
{
    $validationRules = [
        'name' => 'required|string|max:40',
        'email' => 'required|email|string|max:40',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ];

    $validatedData = $this->validate($validationRules);

    // Asigna el user_id si el usuario está autenticado
    $validatedData['user_id'] = auth()->user()->id;
    $validatedData['name_from'] = $this->name_from;
    //SEND EMAIL FORM CONTACT
       
\Mail::send('emails.contactSupportMail', array(
    'name' => $this->name,
    'name2' => $this->name_from,
    'email' => $this->email,
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
    $this->authorize('manage admin');
    SupportContactForm::find($id)->delete();
    session()->flash('message', 'Data Deleted Successfully');
}


}
