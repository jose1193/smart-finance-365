<?php

namespace App\Http\Livewire;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\SubcategoryToAssign;
use App\Models\MainCategories;
use App\Models\User;
use App\Models\CategoriesToAssign;
use App\Models\Operation;
use Livewire\WithPagination;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ExpensesCategories extends Component
{
    use WithPagination;
    
    public  $category_name, $category_description, $main_category_id, $data_id;
    public $search = '';
    public $mainCategoriesRender;
    public $isOpen = 0;
    protected $listeners = ['render','delete','deleteSubcategoryAssignments'];
    public $users;
    public $user_id_assign = [];
    public $selectedUsers = [];

    public $selectedItemId;
    public $showModal = false;
    public $categoryNameSelected; 
    public $subcategory_name = [];
    public $user_id_assignSubcategory = [];
    public $userAssignments = [];

    public $selectedUserId;
    public $selectedUserIdDelete;

    public function authorize()
{
    return true;
}

public function render()
{
    if (auth()->user()->hasRole('Admin')) {
        $data = $this->getDataForAdmin();
    } elseif (auth()->user()->hasRole('User')) {
        $data = $this->getDataForUser();
    }

     $this->mainCategoriesRender = MainCategories::orderBy('id', 'desc')->get();
    $this->users = User::orderBy('id', 'desc')->get();
 
    return view('livewire.expenses-categories', [
        'data' => $data,
    ]);
}


protected function getDataForAdmin()
{
            
          
 $searchTerm = $this->search;
 //RELATION MODEL
$data = Category::with(['assignedUsers', 'Subcategory', 'Subcategory.assignedUsersSubcategory'])
    ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
    ->leftJoin('categories_to_assigns', 'categories.id', '=', 'categories_to_assigns.category_id')
    ->leftJoin('users', 'categories_to_assigns.user_id_assign', '=', 'users.id')
    ->leftJoin('subcategories', 'categories.id', '=', 'subcategories.category_id')
    ->where('main_categories.id', 2)
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



 
    return $data;
}



protected function getDataForUser()
{
    $searchTerm = $this->search;
    $userId = auth()->id();

    $data = Category::with(['assignedUsers', 'Subcategory'])
        ->join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->leftJoin('categories_to_assigns', function ($join) use ($userId) {
            $join->on('categories.id', '=', 'categories_to_assigns.category_id')
                ->where('categories_to_assigns.user_id_assign', $userId);
        })
        ->leftJoin('subcategories', 'categories.id', '=', 'subcategories.category_id')
        ->leftJoin('users', 'categories_to_assigns.user_id_assign', '=', 'users.id')
        ->where('main_categories.id', 2)
        ->where(function ($query) use ($searchTerm) {
            $query->where('categories.category_name', 'like', '%' . $searchTerm . '%');
        })
        ->select(
            'categories.id',
            'categories.category_name',
            'main_categories.title as main_category_name',
            'subcategories.subcategory_name',
            \DB::raw('CASE 
                WHEN NOT EXISTS (SELECT 1 FROM categories_to_assigns WHERE category_id = categories.id) THEN "All Users"
                WHEN EXISTS (SELECT 1 FROM categories_to_assigns WHERE category_id = categories.id AND user_id_assign = ' . auth()->user()->id . ') THEN "' . auth()->user()->username . '"
                ELSE "NoAssigned"
            END as assigned_text')
        )
        ->groupBy('categories.id', 'categories.category_name', 'main_categories.title', 'subcategories.subcategory_name')
        ->orderBy('categories.id', 'desc')
        ->paginate(10);

    return $data;
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

   

 // USER ASSIGNMENT TO REGISTER CATEGORY AND SUBCATEGORY ITEM

public function categoryAssignment()
{
    $this->authorize('manage admin');

    $storeCategory = Category::firstOrNew(['category_name' => $this->categoryNameSelected]);

    if ($storeCategory) {
        $this->handleUserAssignment($storeCategory);
    } else {
        session()->flash('info', 'Category not found.');
    }
}

private function handleUserAssignment($storeCategory)
{
    if (in_array('all', $this->user_id_assign)) {
        $this->deleteAllAssignments($storeCategory);
    } else {
        $this->deleteUnselectedUsers($storeCategory);
        $this->updateOrCreateAssignments($storeCategory);
    }
}

private function deleteAllAssignments($storeCategory)
{
    $categoryAssignmentsExist = CategoriesToAssign::where('category_id', $storeCategory->id)->exists();

    if ($categoryAssignmentsExist) {
        CategoriesToAssign::where('category_id', $storeCategory->id)->delete();

        $subcategories = $storeCategory->subCategory;
        foreach ($subcategories as $subcategory) {
            SubcategoryToAssign::where('subcategory_id', $subcategory->id)->delete();
        }

        session()->flash('removed', 'Users have been removed!');
        $this->reset(['selectedUserId', 'user_id_assign','selectedUserIdDelete']);
        
        $this->user_id_assign = 'All Users';
        
        // Refresh the modal or any other necessary action
    $this->resfreschModalUserAssignment($storeCategory->id);
         $this->emit('sessionRemoved');
    } else {
        // Puedes agregar un mensaje de flash específico si no hay asignaciones en CategoriesToAssign
        session()->flash('noAssignments', 'No user assignments found for this category!');
    }
}


private function deleteUnselectedUsers($storeCategory)
{
    
    $deletedCategories = CategoriesToAssign::where('category_id', $storeCategory->id)
        ->whereNotIn('user_id_assign', $this->user_id_assign)
        ->delete();

    $deletedSubcategories = SubcategoryToAssign::whereIn('subcategory_id', $storeCategory->subCategory->pluck('id'))
        ->whereNotIn('user_id_subcategory', $this->user_id_assign)
        ->delete();

    if ($deletedCategories || $deletedSubcategories) {
        
        // Refresh the modal or any other necessary action
    $this->resfreschModalUserAssignment($storeCategory->id);
            session()->flash('removed', "User has been removed!");
       $this->emit('sessionRemoved'); 
    }
}




private function updateOrCreateAssignments($storeCategory)
{
     
    $isCreate = false;

    foreach ($this->user_id_assign as $userId) {
        
        $result = CategoriesToAssign::updateOrCreate(
            ['category_id' => $storeCategory->id, 'user_id_assign' => $userId],
            ['user_id_admin' => auth()->user()->id]
        );

        if ($result->wasRecentlyCreated) {
            $isCreate = true; // Indica que al menos una asignación fue creada
        }

        
    }

        if ($isCreate) {
           
        session()->flash('assigned', 'User assigned successfully!');
        $this->emit('sessionAssigned');
    }
}



public function AssignToSubCategoryUser($subcategoryName, $selectedUserId)
{
    // Validate the selectedUserId
    $this->validate([
        'selectedUserId' => 'required', // Add any additional validation rules as needed
    ]);

    // Now, you can proceed with finding the subcategory
    $subcategory = Subcategory::where('subcategory_name', $subcategoryName)->first();

    if ($subcategory) {
        // Check if the user is already assigned to the subcategory
        $existingAssignment = SubcategoryToAssign::where('subcategory_id', $subcategory->id)
            ->where('user_id_subcategory', $selectedUserId)
            ->exists();

        if ($existingAssignment) {
            // User is already assigned to the subcategory
            session()->flash('info', 'User is already assigned to this subcategory.');
        } else {
            
            if ($selectedUserId === 'AllUsers') {
              
               // Find the category by name
                $category = Category::where('category_name', $this->categoryNameSelected)->first();
                if ($category) {
             // Assign all users to the subcategory
                 $this->assignAllUsersToSubcategory($subcategory, $selectedUserId, $category->id);
                }
              
                
            } else {
                // Assign a single user to the subcategory
                $this->assignUserToSubcategory($subcategory, $selectedUserId);
            }

            $this->emit('sessionAssignedSubcategory');
            session()->flash('assignedSubcategory', 'Users assigned to subcategory successfully');
        }
    } else {
        // Subcategory not found
        session()->flash('info', 'Subcategory not found.');
    }

    // Refresh the modal or any other necessary action
    $this->resfreschModalUserAssignment($subcategory->category_id);
}



private function assignUserToSubcategory($subcategory, $selectedUserId)
{
    
    // Find the user by ID
    $user = User::find($selectedUserId);

    // Perform the assignment
    SubcategoryToAssign::updateOrCreate(
        ['subcategory_id' => $subcategory->id, 'user_id_subcategory' => $selectedUserId],
        ['user_id_admin' => auth()->user()->id]
    );

    session()->flash('assignedSubcategory', "User $user->username assigned to subcategory successfully");
}




private function assignAllUsersToSubcategory($subcategory, $selectedUserId, $categoryIdSelected)
{
    // Find the user IDs assigned to the category
    $categoryAssignments = CategoriesToAssign::where('category_id', $categoryIdSelected)->get();
    $userIdsCategory = $categoryAssignments->pluck('user_id_assign')->toArray();

    // Find the user IDs assigned to the subcategory
    $subcategoryAssignments = SubcategoryToAssign::where('subcategory_id', $subcategory->id)->get();
    $userIdsSubcategory = $subcategoryAssignments->pluck('user_id_subcategory')->toArray();

    // Find the user IDs that are not assigned to the subcategory but are assigned to the category
    $missingUserIds = array_diff($userIdsCategory, $userIdsSubcategory);
   
    foreach ($missingUserIds as $userId) {
        // Perform the assignment for missing user IDs
        SubcategoryToAssign::updateOrCreate(
            ['subcategory_id' => $subcategory->id, 'user_id_subcategory' => $userId],
            ['user_id_admin' => auth()->user()->id]
        );
    }

    session()->flash('assignedSubcategory', "Users have been assigned to the subcategory successfully");
}




public function resfreschModalUserAssignment($itemId)
{
    $this->selectedItemId = $itemId;  
    $category = Category::with('Subcategory')->find($this->selectedItemId);
    
    // Clear the userAssignments array before populating it again
    $this->userAssignments = [];

    foreach ($category->Subcategory as $subcategory) {
        $subcategoryAssignments = SubcategoryToAssign::where('subcategory_id', $subcategory->id)->get();
        $userIdsSubcategory = $subcategoryAssignments->pluck('user_id_subcategory')->toArray();

        // Obtener usuarios asignados a la subcategoría
        $usersInSubcategory = User::whereIn('id', $userIdsSubcategory)->get(['id', 'username']); // Selecciona solo los campos necesarios
        
        // Guardar información en el array $userAssignments
        $this->userAssignments[] = [
            'subcategory_name' => $subcategory->subcategory_name,
            'users' => $usersInSubcategory,
            'user_id_assignSubcategory' => $userIdsSubcategory,
        ];
    }
   
}


public function deleteSubcategoryAssignments($subcategoryName, $selectedUserIdDelete)
{
    // Find the subcategory by name
    $subcategory = Subcategory::where('subcategory_name', $subcategoryName)->first();

    // Check if the subcategory is found
    if ($subcategory) {
        // Find the user by ID
        $user = User::find($selectedUserIdDelete);

        if ($selectedUserIdDelete === 'removedAll') {
            // Eliminar todos los usuarios asignados a la subcategoría
            SubcategoryToAssign::where('subcategory_id', $subcategory->id)->delete();
            session()->flash('removedAllSubcategory', "All users have been removed from the Subcategory!");
        } else {
            // Eliminar el usuario específico de la subcategoría
            SubcategoryToAssign::where('subcategory_id', $subcategory->id)
                ->where('user_id_subcategory', $selectedUserIdDelete)
                ->delete();

            session()->flash('removedSubcategory', "User $user->username has been removed from the Subcategory!");
        }

        // Refresh the modal or any other necessary action
        $this->resfreschModalUserAssignment($subcategory->category_id);
        $this->emit('sessionRemovedSubcategory');
    } else {
        // Handle the case where the subcategory is not found
        session()->flash('info', 'Subcategory not found.');
    }
}




   // END USER ASSIGNMENT TO REGISTER CATEGORY AND SUBCATEGORY ITEM





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
      $usersInSubcategory = User::whereIn('id', $userIdsSubcategory)->get(['id', 'username']); // Selecciona solo los campos necesarios

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
        $this->resetInputFields(); 
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

    // Obtener la categoría que se va a eliminar
    $categoryToDelete = Category::find($id);

    // Obtener la categoría especial 'No Category Expense'
    $otrosCategory = Category::firstOrCreate(
        [
            'category_name' => 'No Category Expense',
            'main_category_id' => '2',
        ],
        [
            'category_description' => 'No Category Expense',
            'user_id' => auth()->user()->id,
        ]
    );

    // Verificar si la categoría a eliminar es diferente de la categoría especial
    if ($categoryToDelete->id !== $otrosCategory->id) {
        // Actualizar las operaciones que tienen la categoría a eliminar
        Operation::where('category_id', $id)->update(['category_id' => $otrosCategory->id]);

        // Eliminar la categoría
        $categoryToDelete->delete();

        session()->flash('message', 'Data Deleted Successfully.');
    } else {
        // Si estás intentando eliminar la categoría especial, puedes manejarlo como desees,
        // por ejemplo, mostrar un mensaje de advertencia o simplemente no hacer nada.
        session()->flash('info', 'Cannot delete special category.');
    }
}

}