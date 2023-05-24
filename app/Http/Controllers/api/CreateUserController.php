<?php

namespace App\Http\Controllers\Api;

namespace App\Actions\Fortify;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Laravel\Jetstream\Jetstream;

class CreateUserController extends Controller
{
        use PasswordValidationRules;
    public function store(Request $request): Response
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ]);

        if ($validator->fails()) {
            return new Response(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        return new Response(['user' => $user], 201);
    }

    protected function passwordRules()
    {
        return Jetstream::passwordRules();
    }
}
