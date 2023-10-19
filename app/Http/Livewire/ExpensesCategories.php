<?php

namespace App\Http\Livewire;
use App\Models\Category;
use App\Models\MainCategories;
use Livewire\WithPagination;
use Livewire\Component;

class ExpensesCategories extends Component
{
     public  $category_name, $category_description, $main_category_id, $data_id;
 public $search = '';
 public $mainCategoriesRender;
    public $isOpen = 0;
     protected $listeners = ['render','delete']; 
      public function authorize()
{
    return true;
}
    public function render()
    {
       $data = Category::join('main_categories', 'categories.main_category_id', '=', 'main_categories.id')
    ->where('main_categories.id', 2) 
    ->where('category_name', 'like', '%' . $this->search . '%')
    ->select('categories.*', 'main_categories.title as main_category_name')
    ->orderBy('categories.id', 'desc') 
    ->paginate(10);

     $this->mainCategoriesRender = MainCategories::orderBy('id', 'desc')->get();


       
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
            
            
        ]);
    
        Category::updateOrCreate(['id' => $this->data_id], [
            'category_name' => $this->category_name,
            'category_description' => $this->category_description,
            'main_category_id' => $this->main_category_id,
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
        $list = Category::findOrFail($id);
        $this->data_id = $id;
        $this->category_name = $list->category_name;
        $this->category_description = $list->category_description;
         $this->main_category_id = $list->main_category_id;
     
        $this->openModal();
    }
public function delete($id)
    {
         $this->authorize('manage admin');
        Category::find($id)->delete();
        session()->flash('message', 'Data Deleted Successfully.');
    }
}