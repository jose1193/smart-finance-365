<?php

use Illuminate\Support\Facades\Route;
// LARAVEL SOCIALITE
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
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
 
    $user = User::updateOrCreate([
        'google_id' => $googleUser->id,
    ], [
        'name' => $googleUser->name,
        'email' => $googleUser->email,
        'email_verified_at' => now(),
       
    ]);
 
 // Accede al token del usuario autenticado
    $token = $googleUser->token;


    Auth::login($user);
 
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
       Route::get('income', Incomes::class)->name('incomes');
       Route::get('expense', Expenses::class)->name('expense');
       Route::get('emails', EmailsManagament::class)->name('emails');
        Route::get('general-charts', GeneralChartForm::class)->name('general-charts');
});
