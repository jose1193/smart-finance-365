<?php

namespace App\Http\Livewire;
use App\Models\Category;
use App\Models\MainCategories;
use App\Models\User;
use App\Models\CategoriesToAssign;
use Livewire\WithPagination;
use Livewire\Component;

class ExpensesCategories extends Component
{
    public  $category_name, $category_description, $main_category_id, $data_id;
    public $search = '';
    public $mainCategoriesRender;
    public $isOpen = 0;
    protected $listeners = ['render','delete'];
    public $users;
    public $user_id_assign = [];

    public function authorize()
{
    return true;
}

    public function render()
    {
     if (auth()->user()->hasRole('Admin')) {
    $searchTerm = $this->search;
    // Relational Model
    $data = Category::with('assignedUsers')
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->leftJoin('categories_to_assigns', 'categories.id', '=', 'categories_to_assigns.category_id')
        ->leftJoin('users', 'categories_to_assigns.user_id_assign', '=', 'users.id')
        ->where('main_categories.id', 2)
        ->where(function ($query) use ($searchTerm) {
            $query->where('categories.category_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('users.username', 'like', '%' . $searchTerm . '%')
                ->orWhere('users.name', 'like', '%' . $searchTerm . '%');
        })
        ->select('categories.id', 'categories.category_name', 'main_categories.title as main_category_name')
      ->groupBy('categories.id', 'categories.category_name', 'main_categories.title')
        ->paginate(10);
}
elseif (auth()->user()->hasRole('User')) {
    $searchTerm = $this->search;
    $userId = auth()->id(); // Obtener el ID del usuario actual

    $data = Category::join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
    ->leftJoin('categories_to_assigns', function($join) use ($userId) {
        $join->on('categories.id', '=', 'categories_to_assigns.category_id')
            ->where('categories_to_assigns.user_id_assign', $userId);
    })
    ->where('main_categories.id', 2)
    ->where(function($query) use ($searchTerm) {
        $query->where('categories.category_name', 'like', '%' . $searchTerm . '%');
    })
    ->select('categories.*', 'main_categories.title as main_category_name')
    ->orderBy('categories.id', 'desc')
    ->paginate(10);

}

        $this->mainCategoriesRender = MainCategories::orderBy('id', 'desc')->get();
        $this->users = User::orderBy('id', 'desc')->get();

       
        return view('livewire.expenses-categories', [
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
        'category_name' => 'required|string|max:30|unique:categories,category_name,' . $this->data_id,
        'category_description' => 'required|string|max:50',
        'main_category_id' => 'required|exists:main_categories,id',
         'user_id_assign' => 'required|array',
        'user_id_assign.*' => 'required|max:50',
        ],[
    'category_name.required' => 'El nombre de categoría es obligatorio.',
    'category_name.string' => 'El nombre de categoría debe ser una cadena de texto.',
    'category_name.max' => 'El nombre de categoría no debe superar los 30 caracteres.',
    'category_name.unique' => 'El nombre de categoría ya está en uso.',

    'category_description.required' => 'La descripción de categoría es obligatoria.',
    'category_description.string' => 'La descripción de categoría debe ser una cadena de texto.',
    'category_description.max' => 'La descripción de categoría no debe superar los 50 caracteres.',

    'main_category_id.required' => 'El ID de categoría principal es obligatorio.',
    'main_category_id.exists' => 'El ID de categoría principal no es válido.',

    'user_id_assign.required' => 'El ID de usuario asignado es obligatorio.',
    'user_id_assign.string' => 'El ID de usuario asignado debe ser una cadena de texto.',
    'user_id_assign.max' => 'El ID de usuario asignado no debe superar los 30 caracteres.',
            ]);
    
        $storeCategory = Category::updateOrCreate(
        ['id' => $this->data_id],
        [
        'category_name' => $this->category_name,
        'category_description' => $this->category_description,
        'main_category_id' => $this->main_category_id,
        'user_id' => auth()->user()->id,
        ]
        );


        if (in_array('all', $this->user_id_assign)) {
        CategoriesToAssign::where('category_id', $storeCategory->id)->delete();
        
    }
        else
     {
    
         // Obtener todos los usuarios asignados actualmente a esta categoría
    $currentAssignments = CategoriesToAssign::where('category_id', $storeCategory->id)->get()->pluck('user_id_assign')->toArray();

    // Obtener la lista de usuarios seleccionados en el formulario
    $selectedUsers = is_array($this->user_id_assign) ? $this->user_id_assign : [];

    // Usuarios a agregar
    $usersToAdd = array_diff($selectedUsers, $currentAssignments);

    // Usuarios a eliminar
    $usersToRemove = array_diff($currentAssignments, $selectedUsers);

    // Eliminar usuarios deseleccionados
    CategoriesToAssign::where('category_id', $storeCategory->id)
        ->whereIn('user_id_assign', $usersToRemove)
        ->delete();

        
        foreach ($this->user_id_assign as $userId) {
         $categoryAssignment = CategoriesToAssign::where('category_id', $storeCategory->id)
                                ->where('user_id_assign', $userId)
                                ->first();
        if ($categoryAssignment) {
       
        $categoryAssignment->update([
            'user_id_admin' => auth()->user()->id,
            'user_id_assign' => $userId,
        ]);
        } else {
   
        $categoryAssignment = CategoriesToAssign::create([
            'category_id' => $storeCategory->id,
            'user_id_assign' => $userId,
            'user_id_admin' => auth()->user()->id,
        ]);
    
        }
        }
 }
        session()->flash('message', 
            $this->data_id ? 'Data Updated Successfully.' : 'Data Created Successfully.');
   
        $this->closeModal();
        $this->resetInputFields();
    }

public function edit($id)
{
    $this->authorize('manage admin');
    $list = Category::findOrFail($id);
    $this->data_id = $id;
    $this->category_name = $list->category_name;
    $this->category_description = $list->category_description;
    $this->main_category_id = $list->main_category_id;

    
    $categoriesToAssign = CategoriesToAssign::where('category_id', $id)->get();

    // Inicializar un array para almacenar todos los user_id_assign
    $userIds = [];

    // Si hay asignaciones, obtener todos los user_id_assign
    if ($categoriesToAssign->isNotEmpty()) {
        $userIds = $categoriesToAssign->pluck('user_id_assign')->toArray();
    }

   
    $this->user_id_assign = $userIds;

    $this->openModal();
}

public function delete($id)
    {
         $this->authorize('manage admin');
        Category::find($id)->delete();
        session()->flash('message', 'Data Deleted Successfully.');
    }
}