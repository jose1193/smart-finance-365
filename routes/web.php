<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;// LARAVEL SOCIALITE
use App\Http\Livewire\PrimaryCategories;
use App\Http\Livewire\Teachers;
use App\Http\Livewire\Categories;
use App\Http\Livewire\IncomeCategories;
use App\Http\Livewire\ExpensesCategories;
use App\Http\Livewire\UsersCrud;
use App\Http\Livewire\Incomes;
use App\Http\Livewire\Expenses;
use App\Http\Livewire\EmailsManagament;
use App\Http\Livewire\GeneralChartForm;
use App\Http\Livewire\DashboardTable;
use App\Http\Livewire\DashboardCharts;
use App\Http\Livewire\DashboardCards;
use App\Http\Livewire\Operations;
use App\Http\Livewire\IncomesOperations;
use App\Http\Livewire\CurrencyCalculator;
use App\Http\Livewire\ReportGeneralCharts;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


///------------- ROUTE GOOGLE AUTH ---------///
Route::get('/google-auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});
 


Route::get('/google-auth/callback', function () {
    $googleUser = Socialite::driver('google')->user();
    // Generar un número aleatorio de 3 dígitos
    $randomNumber = rand(100, 999);
    // Elimina los espacios en blanco del nombre
    $nameWithoutSpaces = str_replace(' ', '', $googleUser->name);
    $user = User::updateOrCreate([
        'google_id' => $googleUser->id,
    ], [
        'name' => $googleUser->name,
        'username' => $nameWithoutSpaces . $randomNumber,
        'email' => $googleUser->email,
        'email_verified_at' => now(),
       
       
    ]);
 
   

 // Accede al token del usuario autenticado
    $token = $googleUser->token;


   

    Auth::login($user);
 
     //SEND EMAIL FORM CONTACT
    if (isset($user) && $user->wasRecentlyCreated) {
    // Verificar si el usuario ya tiene el rol por defecto asignado
    $defaultRole = Role::find(2); // Reemplaza 2 con el ID del rol por defecto

    if (!$user->hasRole($defaultRole)) {
        // El usuario no tiene el rol por defecto, asígnalo
        $user->assignRole($defaultRole);
    }

    \Mail::send('emails.NewMailUserGoogle', array(
        'name' => $user->name,
        'email' => $user->email,
    ), function($message) use ($user) {
        $message->from('smartfinance793@gmail.com', 'Smart Finance');
        $message->to($user->email)->subject('Welcome to Smart Finance');
    });
}

 //END SEND EMAIL FORM CONTACT
 
    return redirect('/dashboard');
  


});


///------------- END ROUTE GOOGLE AUTH ---------///


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('main-categories', PrimaryCategories::class)->name('main-categories');
    Route::get('teachers', Teachers::class)->name('teachers');
    Route::get('categories', Categories::class)->name('categories');
    Route::get('income-categories', IncomeCategories::class)->name('income-categories');
    Route::get('expenses-categories', ExpensesCategories::class)->name('expenses-categories');
    Route::get('users', UsersCrud::class)->name('users');
       //Route::get('income', Incomes::class)->name('incomes');
       //Route::get('expense', Expenses::class)->name('expense');
    Route::get('emails', EmailsManagament::class)->name('emails');
    Route::get('general-charts', GeneralChartForm::class)->name('general-charts');
    Route::get('dashboard-table', DashboardTable::class)->name('dashboard-table');
    Route::get('dashboard-charts', DashboardCharts::class)->name('dashboard-charts');
    Route::get('dashboard-cards', DashboardCards::class)->name('dashboard-cards');

    Route::get('expenses', Operations::class)->name('expense');
    Route::get('incomes', IncomesOperations::class)->name('incomes');
    Route::get('currency', CurrencyCalculator::class)->name('currency');
    Route::get('calculator', [CurrencyCalculator::class, 'Calculator'])->name('calculator');
    Route::get('general-charts', ReportGeneralCharts::class)->name('general-charts');
});
