<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
public function __construct()
{
    $this->middleware('permission:manage manager')->only(['getUsers']);
   
}
// USER LOGIN
public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        if ($request->filled('remember')) {
            $rememberToken = Str::random(60); // Generar un token de recordar aleatorio
            $user->forceFill([
                'remember_token' => hash('sha256', $rememberToken),
            ])->save();

            $userObject = $user->toArray();
            $userObject['remember_token'] = $rememberToken;
        } else {
            $userObject = $user->toArray();
        }

        $userObject['token'] = $user->createToken('API Token')->plainTextToken;

        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        $welcomeMessage = '¡Bienvenido/a ' . $user->name . '! Tu tipo de usuario es ' . implode(', ', $userRole) . '.';

        return response()->json(['user' => $userObject, 'roles' => $roles, 'userRole' => $userRole, 'message' => $welcomeMessage], 200);
    }

    return response()->json(['error' => 'Credenciales inválidas'], 401);
}


// USER LOGOUT
public function logout()
{
    if (auth()->user()) {
        auth()->user()->tokens()->delete();
        return Response::json(['message' => 'Sesión cerrada correctamente'],200);
    } else {
        return Response::json(['message' => 'No se encontró ninguna sesión activa'], 401);
    }
}


/// DETAILS CURRENT USER
 public function user()
{
    return response()->json(['User' => auth()->user()]);
}

// UPDATE USER PASSWORD
public function updatePassword(Request $request)
   {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'La contraseña actual no coincide'], 401);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Contraseña actualizada correctamente']);
    }

    // RESET PASSWORD LINK
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Link de restablecimiento de contraseña enviado al correo electrónico.']);
        } else {
            return response()->json(['error' => 'No se pudo enviar el link de restablecimiento de contraseña.'], 500);
        }
    }

    //RESET PASSWORD PAGE MAIL
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Contraseña restablecida correctamente.']);
        } else {
            return response()->json(['error' => 'No se pudo restablecer la contraseña.'], 500);
        }
    }



    // UPDATE PROFILE USER
    public function updateProfile(Request $request, UpdateUserProfileInformation $updater)
    {
        $user = $request->user();


        // --- VALIDAR SIZE DE LA PHOTO
       try {
    $request->validate([
        'photo' => 'nullable|file|max:1024', // Cambiar 1024 por 1000 para 1MB
    ], [
        'photo.max' => 'La foto no debe ser mayor a 1MB.', // Mensaje personalizado
    ]);
} catch (ValidationException $e) {
    return Response::json(['message' => $e->errors()], 422);
}


 // -- Verificar si se envió una nueva foto
    if ($request->hasFile('photo')) {
        $input['photo'] = $request->file('photo');
    }

    // --- INPUTS UPDATE
        $updater->update($user, $request->all());
//--- MESSAGE
        return Response::json(['message' => 'Perfil actualizado correctamente.']);
    }

        // GET ALL USERS
     public function getUsers()
    {

        
        $users = User::all();
        return response()->json(['users' => $users], 200);
    }

}
