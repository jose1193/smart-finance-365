<?php

namespace App\Http\Livewire;

use Livewire\Component;

class LanguageSelector extends Component
{
    public $selectedLanguage;

    public function mount()
    {
        $this->selectedLanguage = app()->getLocale();
    }

    // Este método se ejecutará automáticamente cuando cambie $selectedLanguage
    public function updatedSelectedLanguage($value)
    {
        app()->setLocale($value);
        session()->put('locale', $value);
    }

    public function render()
    {
        return view('livewire.language-selector');
    }

    // Este método puede ser llamado desde fuera del componente para actualizar el idioma
    public function updateLanguage($language)
    {
        $this->selectedLanguage = $language;
    }
}

