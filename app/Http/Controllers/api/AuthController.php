<?php 
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Illuminate\Http\Request;
use Laravel\Jetstream\Http\Controllers\Inertia\LoginController;
use Laravel\Fortify\src\Contracts\CreatesLoginResponse;
use Laravel\Fortify\src\Contracts\LoginResponse;
use Laravel\Fortify\src\Contracts\LoginViewResponse;

class AuthenticatedSessionControllers extends Controller
{
   use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Mapear los datos del usuario como desees
        $mappedUser = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            // Agrega cualquier otro dato que necesites
        ];

        return $mappedUser;
    }

    return response(['error' => 'Credenciales inválidas'], 401);
}

}
