<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\MainCategories;
class PrimaryCategories extends Component
{
    public $categories, $title, $description, $categories_id;
    public $isOpen = 0;

    public function render()
    {
        $this->categories = MainCategories::all();
        return view('livewire.primary-categories');
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
        $this->validate([
            'title' => 'required|string|max:20|unique:main_categories',
            'description' => 'string|max:30',
        ]);
    
        MainCategories::updateOrCreate(['id' => $this->categories_id], [
            'title' => $this->title,
            'description' => $this->description
        ]);
   
        session()->flash('message', 
            $this->categories_id ? 'Data Updated Successfully.' : 'Data Created Successfully.');
   
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $categories = MainCategories::findOrFail($id);
        $this->categories_id = $id;
        $this->title = $categories->title;
        $this->description = $categories->description;
     
        $this->openModal();
    }

    public function delete($id)
    {
        MainCategories::find($id)->delete();
        session()->flash('message', 'Data Deleted Successfully.');
    }
}
