<?php

namespace App\Http\Livewire;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\WithPagination;
use Livewire\Component;
use Illuminate\Validation\Rule;

class UsersCrud extends Component
{
 public  $name,$username, $email,$password,$role, $data_id;
 public $search = '';
 public $rolesRender;
 public $isOpen = 0;
 protected $listeners = ['render','delete']; 

 public function authorize()
{
    return true;
}
    public function render()
    {
       
 $data = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
    ->where(function($query) {
        $query->where('users.name', 'like', '%' . request('search') . '%')
              ->orWhere('users.username', 'like', '%' . request('search') . '%')
              ->orWhere('users.email', 'like', '%' . request('search') . '%');

        if (!empty(request('role'))) {
            $query->where('roles.name', '=', request('role'));
        }
    })
    ->select('users.*', 'roles.name as role_name')
    ->orderBy('users.id', 'desc')
    ->paginate(10);

$this->rolesRender = Role::orderBy('id', 'asc')->get();

return view('livewire.users-crud', [
    'data' => $data, // Pasar los resultados paginados a la vista
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
        
        $this->reset();
        $this->resetValidation(); 

    }

    private function resetInputFields(){
         $this->reset();
    }


   
 public function store()
    {
    $this->authorize('manage admin');
    
    $this->validate([
    'name' => 'required|string|max:20|regex:/^[A-Za-z\s]+$/',
    'username' => ['required', 'unique:users,username,' . $this->data_id, 'regex:/^\S*$/'],
    'email' => 'required|email|unique:users,email,' . $this->data_id,
     'role' => 'required',
], [
    'name' => [
    'required' => 'El campo nombre es obligatorio',
    'string' => 'El campo nombre debe ser una cadena de texto',
    'max' => 'El campo nombre no debe superar los 40 caracteres',
    'regex' => 'El campo nombre solo debe contener letras',
],
    'email.required' => 'El campo correo electrónico es obligatorio',
    'email.email' => 'Por favor, ingrese una dirección de correo electrónico válida',
    'email.unique' => 'Esta dirección de Email ya ha sido registrada',
    'email.max' => 'El campo Email no debe superar los 50 caracteres',
    'username.required' => 'El campo nombre de usuario es obligatorio',
    'username.unique' => 'Este nombre de usuario ya está en uso',
    'username.max' => 'El campo nombre de usuario no debe superar los 20 caracteres',
    'username.regex' => 'El campo Username no debe tener espacios',
]);

// Verificar si el usuario existe antes de realizar la actualización
$user = User::find($this->data_id);

if ($user) {
    // El usuario ya existe, actualiza la información, pero no la contraseña si no se está actualizando
    $userData = [
        'name' => $this->name,
        'username' => $this->username,
        'email' => $this->email,
        'email_verified_at' => now(),
    ];

    
    if ($this->password) {
        $userData['password'] = bcrypt($this->password);
    }

    $user->update($userData);

    // Actualiza el rol
    $role = Role::find($this->role);
    $user->syncRoles([$role->name]);
} else {
    // Generar una contraseña predeterminada si es un nuevo usuario
    $password = $this->username;

    // Crear el usuario con la contraseña predeterminada
    $user = User::updateOrCreate(['id' => $this->data_id], [
        'name' => $this->name,
        'username' => $this->username,
        'email' => $this->email,
        'email_verified_at' => now(),
        'password' => bcrypt($password),
    ]);

    // Asignar rol al nuevo usuario
    $role = Role::find($this->role);
    $user->assignRole($role->name);

    
    \Mail::send('emails.NewMailUserCrud', [
        'name' => $this->name,
        'username' => $this->username,
        'email' => $this->email,
        'password' => $password,
        'role' => $role->name,
    ], function ($message) use ($user) {
        $message->from('smartfinance794@gmail.com', 'Smart Finance 365');
        $message->to($user->email)->subject('Welcome to Smart Finance');
    });
}



session()->flash('message', $this->data_id ? 'Data Updated Successfully.' : 'Data Created Successfully.');
   
        $this->closeModal();
        $this->resetInputFields();
    }


    public function edit($id)
{
    $this->authorize('manage admin');
    $user = User::findOrFail($id);

    $this->data_id = $id;
    $this->name = $user->name;
    $this->username = $user->username;
    $this->email = $user->email;
    

    // Obtener el rol del usuario y establecerlo en una propiedad del componente
    $this->role = $user->roles->first()->id; // Asigna el ID del rol

    $this->openModal();
}

public function delete($id)
    {
         $this->authorize('manage admin');
        User::find($id)->delete();
        session()->flash('message', 'Data Deleted Successfully.');
    }

}
