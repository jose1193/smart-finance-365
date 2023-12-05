<?php

namespace App\Http\Livewire;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\SubcategoryToAssign;
use App\Models\MainCategories;
use App\Models\User;
use App\Models\CategoriesToAssign;
use Livewire\WithPagination;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class IncomeCategories extends Component
{
    public $category_name, $category_description, $main_category_id, $data_id;
    public $search = '';
    public $mainCategoriesRender;
    public $isOpen = 0;
    protected $listeners = ['render','delete'];
    public $users;
    public $user_id_assign = [];


    public $selectedItemId;
    public $showModal = false;
    public $categoryNameSelected; 
    public $subcategory_name = [];
    public $user_id_assignSubcategory = [];
    public $userAssignments = [];
  
    use WithPagination;

    public function authorize()
{
    return true;
}

    public function render()
    {
         if (auth()->user()->hasRole('Admin')) {
             
 $searchTerm = $this->search;
 //RELATION MODEL
$data = Category::with(['assignedUsers', 'Subcategory', 'Subcategory.assignedUsersSubcategory'])
    ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
    ->leftJoin('categories_to_assigns', 'categories.id', '=', 'categories_to_assigns.category_id')
    ->leftJoin('users', 'categories_to_assigns.user_id_assign', '=', 'users.id')
    ->leftJoin('subcategories', 'categories.id', '=', 'subcategories.category_id')
    ->where('main_categories.id', 1)
    ->where(function ($query) use ($searchTerm) {
        $query->where('categories.category_name', 'like', '%' . $searchTerm . '%')
            ->orWhere('users.username', 'like', '%' . $searchTerm . '%')
            ->orWhere('users.name', 'like', '%' . $searchTerm . '%')
            ->orWhere('subcategories.subcategory_name', 'like', '%' . $searchTerm . '%');
    })
    ->select(
    'categories.id',
    'categories.category_name',
    'main_categories.title as main_category_name',
    'subcategories.subcategory_name',
    \DB::raw('CASE 
        WHEN COUNT(users.id) = 0 THEN "All Users"
        WHEN COUNT(users.id) > 0 AND ' . auth()->user()->hasRole('Admin') . ' THEN GROUP_CONCAT(users.username)
        WHEN COUNT(users.id) > 0 AND EXISTS (SELECT 1 FROM categories_to_assigns WHERE category_id = categories.id AND user_id_assign = ' . auth()->user()->id . ') THEN "' . auth()->user()->username . '"
        ELSE "Not Assigned" 
    END as assigned_text')
)
->groupBy('categories.id', 'categories.category_name', 'main_categories.title', 'subcategories.subcategory_name')

    ->orderBy('categories.id', 'desc')
    ->paginate(10);



         }

    elseif (auth()->user()->hasRole('User')) {
    $searchTerm = $this->search;
    $userId = auth()->id(); 

    $data = Category::with(['assignedUsers', 'Subcategory'])
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->leftJoin('categories_to_assigns', function ($join) use ($userId) {
            $join->on('categories.id', '=', 'categories_to_assigns.category_id')
                ->where('categories_to_assigns.user_id_assign', $userId);
        })
        ->leftJoin('subcategories', 'categories.id', '=', 'subcategories.category_id')
        ->leftJoin('users', 'categories_to_assigns.user_id_assign', '=', 'users.id') // Agregado este join
        ->where('main_categories.id', 1)
        ->where(function ($query) use ($searchTerm) {
            $query->where('categories.category_name', 'like', '%' . $searchTerm . '%');
        })
        ->select(
            'categories.id',
            'categories.category_name',
            'main_categories.title as main_category_name',
            'subcategories.subcategory_name',
            \DB::raw('CASE 
                WHEN COUNT(users.id) = 0 THEN "All Users"
                WHEN COUNT(users.id) > 0 AND ' . auth()->user()->hasRole('User') . ' THEN GROUP_CONCAT(users.username)
                WHEN COUNT(users.id) > 0 AND EXISTS (SELECT 1 FROM categories_to_assigns WHERE category_id = categories.id AND user_id_assign = ' . auth()->user()->id . ') THEN "' . auth()->user()->username . '"
                ELSE "Not Assigned" 
            END as assigned_text')
        )
        ->groupBy('categories.id', 'categories.category_name', 'main_categories.title', 'subcategories.subcategory_name')
        ->orderBy('categories.id', 'desc')
        ->paginate(10);
}


        $this->mainCategoriesRender = MainCategories::orderBy('id', 'asc')->get();
        $this->users = User::orderBy('id', 'desc')->get();

       
        return view('livewire.income-categories', [
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
         $this->resetInputFields();
        
    }

    private function resetInputFields(){
         $this->reset();
         $this->resetValidation(); 
    }

       public function store()
    {
         $this->authorize('manage admin');
       $this->validate([
        'category_name' => 'required|string|max:30|unique:categories,category_name,' . $this->data_id,
        'category_description' => 'required|string|max:50',
        'main_category_id' => 'required|exists:main_categories,id',
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
]);

    
        $category = Category::updateOrCreate(['id' => $this->data_id], [
            'category_name' => $this->category_name,
            'category_description' => $this->category_description,
            'main_category_id' => $this->main_category_id,
            'user_id' => auth()->user()->id,
        ]);

         if (empty($this->subcategory_name)) {
        Subcategory::where('category_id', $category->id)->delete();
    } else {
    // Obtener la lista de subcategorías seleccionadas en el formulario
    $selectedSubcategories = is_array($this->subcategory_name) ? $this->subcategory_name : [];

    // Obtener todas las subcategorías actuales asociadas a esta categoría
    $currentSubcategories = Subcategory::where('category_id', $category->id)
        ->pluck('subcategory_name')
        ->toArray();

    // Eliminar subcategorías deseleccionadas
    Subcategory::where('category_id', $category->id)
        ->whereNotIn('subcategory_name', $selectedSubcategories)
        ->delete();

    // Actualizar o crear subcategorías
    foreach ($selectedSubcategories as $subcatName) {
        Subcategory::updateOrCreate(
            ['category_id' => $category->id, 'subcategory_name' => $subcatName],
            ['user_id' => auth()->user()->id]
        );
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
    
    $category = Category::with('Subcategory')->findOrFail($id);
    $this->data_id = $id;
    $this->category_name = $category->category_name;
    $this->category_description = $category->category_description;
    $this->main_category_id = $category->main_category_id;

    // Retrieve current subcategories associated with the category
    $currentSubcategories = $category->Subcategory ? $category->Subcategory->pluck('subcategory_name')->toArray() : [];

    // Initialize the subcategory_name property as an array
    $this->subcategory_name = $currentSubcategories;


        $this->openModal();
    }


  
public function categoryAssignment()
{
    $this->authorize('manage admin');

    $this->validate([
        'user_id_assign' => 'required|array',
        'user_id_assign.*' => 'required|max:100',
        'categoryNameSelected' => 'required|string|max:50',
    ], [
        'user_id_assign.required' => 'El ID de usuario asignado en Categoria es obligatorio.',
        'user_id_assign.array' => 'El ID de usuario asignado debe ser un array.',
        'user_id_assign.*.required' => 'El ID de usuario asignado es obligatorio.',
        'user_id_assign.*.max' => 'El ID de usuario asignado no debe superar los 100 caracteres.',
    ]);

    $storeCategory = Category::firstOrNew(['category_name' => $this->categoryNameSelected]);
     $storeCategory->save();

    if ($storeCategory) {
        if (in_array('all', $this->user_id_assign)) {
            CategoriesToAssign::where('category_id', $storeCategory->id)->delete();
        } else {
        // Eliminar usuarios deseleccionados
        CategoriesToAssign::where('category_id', $storeCategory->id)
            ->whereNotIn('user_id_assign', $this->user_id_assign)
            ->delete();

        // Actualizar o crear asignaciones de usuarios
        foreach ($this->user_id_assign as $userId) {
            CategoriesToAssign::updateOrCreate(
                ['category_id' => $storeCategory->id, 'user_id_assign' => $userId],
                ['user_id_admin' => auth()->user()->id]
            );
        }
    }
        
    } else {
        session()->flash('error', 'Category not found.');
    }

    // Llamada a la función para asignar usuarios a subcategorías
       $this->SubcategoryAssignment($storeCategory);

        $this->closeModalUserAssignment();
        $this->resetInputFields();
}




public function SubcategoryAssignment(Category $storeCategory)
{
    if (!$storeCategory || $storeCategory->Subcategory->isEmpty()) {
        session()->flash('error', 'Invalid category or no subcategories found.');
        return;
    }

    $storeCategory = Category::with('assignedUsers')->find($storeCategory->id);
    $assignedUsers = $storeCategory->assignedUsers;

     if (empty($this->user_id_assign) || in_array('all', $this->user_id_assign)) {
        $this->deleteSubcategoryAssignments($storeCategory->Subcategory);
        return;
    }

    foreach ($storeCategory->Subcategory as $index => $subcategory) {
        $selectedUsers = $this->user_id_assignSubcategory[$index] ?? [];
   
        // Check if 'All Users' is selected or array is empty
        if (in_array('all', $selectedUsers) || empty($selectedUsers)) {
            $this->deleteSubcategoryAssignments((object) $subcategory);
        } else {
            $this->processSubcategoryAssignments((object) $subcategory, $selectedUsers, $storeCategory->id, $assignedUsers);
        }
    }

    $this->resetInputFields();
}


private function deleteSubcategoryAssignments($subcategories)
{
    // Ensure $subcategories is an array
    if (!is_array($subcategories)) {
        $subcategories = [$subcategories];
    }

    // Loop through each subcategory in the array
    foreach ($subcategories as $subcategory) {
        // Check if the subcategory object has the 'id' property
        if (isset($subcategory->id)) {
            // Delete entries in the SubcategoryToAssign table where subcategory_id matches the subcategory id
            SubcategoryToAssign::where('subcategory_id', $subcategory->id)->delete();
        }
    }

}



private function processSubcategoryAssignments($subcategory, $selectedUsers, $categoryId, $assignedUsers)
{
    $unassignedUsers = $this->getUnassignedUsers(
        $selectedUsers,
        $this->getUserAssignments($categoryId, $selectedUsers),
        $assignedUsers
    );

    if (!empty($unassignedUsers)) {
        $unassignedUsernamesString = implode(', ', $unassignedUsers);
        session()->flash('error', "Users not assigned to the category '$subcategory->subcategory_name' - Usernames: $unassignedUsernamesString");
    } else {
        SubcategoryToAssign::where('subcategory_id', $subcategory->id)
            ->whereNotIn('user_id_subcategory', $selectedUsers)
            ->delete();

        $this->updateOrCreateUserAssignments($subcategory->id, $selectedUsers);
        session()->flash('message', 'User Assignments Updated Successfully.');
    }
}

private function updateOrCreateUserAssignments($subcategoryId, $selectedUsers)
{
    foreach ($selectedUsers as $userId) {
        SubcategoryToAssign::updateOrCreate(
            ['subcategory_id' => $subcategoryId, 'user_id_subcategory' => $userId],
            ['user_id_admin' => auth()->user()->id]
        );
    }
}


private function getUserAssignments($categoryId, $selectedUsers)
{
    return CategoriesToAssign::where('category_id', $categoryId)
        ->whereIn('user_id_assign', $selectedUsers)
        ->pluck('user_id_assign')
        ->toArray();
}



// Función para obtener usuarios no asignados
private function getUnassignedUsers($selectedUsers, $categoryAssignments, $assignedUsers) {
    $unassignedUsers = array_diff($selectedUsers, $categoryAssignments);

    // Verificar si el usuario autenticado está asignado
    if ($assignedUsers->contains('id', auth()->user()->id)) {
        $unassignedUsers = array_diff($unassignedUsers, [auth()->user()->username]);
    }

    return User::whereIn('id', $unassignedUsers)->pluck('username')->toArray();
}



public function OpenModalUserAssignment($itemId)
{
    $this->selectedItemId = $itemId;  
    $category = Category::with('Subcategory')->find($this->selectedItemId);
    $this->categoryNameSelected = $category ? $category->category_name : null;
    $this->showModal = true;

    // Obtener asignaciones de usuarios para la categoría
    $categoriesToAssign = CategoriesToAssign::where('category_id', $itemId)->get();
    $this->user_id_assign = $categoriesToAssign->isNotEmpty() ? $categoriesToAssign->pluck('user_id_assign')->toArray() : [];

    // Obtener asignaciones de usuarios para cada subcategoría con sus correos electrónicos
    $userAssignments = [];

    foreach ($category->Subcategory as $subcategory) {
        $subcategoryAssignments = SubcategoryToAssign::where('subcategory_id', $subcategory->id)->get();
        $userIdsSubcategory = $subcategoryAssignments->pluck('user_id_subcategory')->toArray();

        // Obtener usuarios asignados a la subcategoría
        $usersInSubcategory = User::whereIn('id', $userIdsSubcategory)->get(['id', 'name']); // Selecciona solo los campos necesarios

        // Guardar información en el array $userAssignments
        $userAssignments[] = [
            'subcategory_name' => $subcategory->subcategory_name,
            'users' => $usersInSubcategory,
            'user_id_assignSubcategory' => $userIdsSubcategory,
        ];
    }

    // Inicializar la propiedad con el mismo nombre
    $this->userAssignments = $userAssignments;
    
   
}


    public function closeModalUserAssignment()
    {
        $this->showModal = false;
         $this->resetValidation(); 
    }

    public function addSubcategory()
    {
        $this->subcategory_name[] = ''; // Add an empty string for a new subcategory
        
        
    }

    public function removeSubcategory($index)
    {
        unset($this->subcategory_name[$index]); // Remove a subcategory by index
        $this->subcategory_name = array_values($this->subcategory_name); // Re-index the array
    }
    

    
public function delete($id)
    {
         $this->authorize('manage admin');
        Category::find($id)->delete();
       
        session()->flash('message', 'Data Deleted Successfully.');
    }
}