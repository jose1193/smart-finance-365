<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Laravel\Jetstream\Jetstream;

class CreateUser extends Component
{
    public $name;
    public $email;
    public $password;
    public $terms = false;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ];
    }

    protected function passwordRules()
    {
        return Jetstream::passwordRules();
    }

    public function create()
    {
        $validator = Validator::make($this->toArray(), $this->rules());

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()], 422);
        }

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $this->reset();

        return response(['message' => 'User created successfully'], 201);
    }

    public function render()
    {
        return view('livewire.create-user');
    }
}
