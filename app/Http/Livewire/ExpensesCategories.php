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
    public $users,$user_id_assign;

    public function authorize()
{
    return true;
}
    public function render()
    {
      if (auth()->user()->hasRole('Admin')) {
    // Si es un administrador, muestra todas las categorías
    $data = Category::join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
        ->where('main_categories.id', 2) // Filtrar por la categoría "Income"
        ->where('category_name', 'like', '%' . $this->search . '%')
        ->select('categories.*', 'main_categories.title as main_category_name')
        ->orderBy('categories.id', 'desc')
        ->paginate(10);
    } elseif (auth()->user()->hasRole('User')) {
    $data = Category::join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
    ->leftJoin('categories_to_assigns', function($join) {
        $join->on('categories.id', '=', 'categories_to_assigns.category_id')
            ->where('categories_to_assigns.user_id_assign', '=', auth()->user()->id);
    })
    ->where(function($query) {
        $query->where('main_categories.id', 2) // Filtrar por la categoría "Income"
            ->orWhere(function ($q) {
                $q->where('main_categories.id', 2) // Filtrar por la categoría principal "1"
                    ->where('categories_to_assigns.user_id_assign', '=', auth()->user()->id);
            });
    })
    ->where('category_name', 'like', '%' . $this->search . '%')
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
    }

    private function resetInputFields(){
         $this->reset();
    }

    
 public function store()
    {
         $this->authorize('manage admin');
        $this->validate([
        'category_name' => 'required|string|max:30',
        'category_description' => 'required|string|max:50',
        'main_category_id' => 'required|exists:main_categories,id',
        'user_id_assign' => 'required|max:30',
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


        if ($this->user_id_assign === 'all') {
        // Si se selecciona 'all', elimina todas las asignaciones de esa categoría
        CategoriesToAssign::where('category_id', $storeCategory->id)->delete();
        } else {
    
        $categoryAssignment = CategoriesToAssign::where('category_id', $storeCategory->id)
        ->first();
        if ($categoryAssignment) {
        // Si ya existe la asignación para ese usuario y categoría, actualiza sus datos
        $categoryAssignment->update([
            'user_id_admin' => auth()->user()->id,
            'user_id_assign' => $this->user_id_assign,
        ]);
        } else {
        // Si no existe, crea una nueva asignación para el usuario y la categoría
        $categoryAssignment = CategoriesToAssign::create([
            'category_id' => $storeCategory->id,
            'user_id_assign' => $this->user_id_assign,
            'user_id_admin' => auth()->user()->id,
        ]);
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
        
        $categoryToAssign = CategoriesToAssign::where('category_id', $id)->first();

        if ($categoryToAssign) {
    
        $this->user_id_assign = $categoryToAssign->user_id_assign;
        } else {
        $this->user_id_assign = null; 
        }

        $this->openModal();
    }
public function delete($id)
    {
         $this->authorize('manage admin');
        Category::find($id)->delete();
        session()->flash('message', 'Data Deleted Successfully.');
    }
}