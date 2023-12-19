<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;// LARAVEL SOCIALITE
use App\Http\Livewire\Categories;
use App\Http\Livewire\IncomeCategories;
use App\Http\Livewire\ExpensesCategories;
use App\Http\Livewire\UsersCrud;
use App\Http\Livewire\EmailsManagament;
use App\Http\Livewire\GeneralChartForm;
use App\Http\Livewire\DashboardTable;
use App\Http\Livewire\DashboardCharts;
use App\Http\Livewire\DashboardCards;
use App\Http\Livewire\Operations;
use App\Http\Livewire\IncomesOperations;
use App\Http\Livewire\CurrencyCalculator;
use App\Http\Livewire\ReportGeneralCharts;
use App\Http\Livewire\ReportGeneralTable;
use App\Http\Livewire\SupportContact;
use App\Http\Livewire\EmailAdmin;
use App\Http\Livewire\StatusCategories;
use Illuminate\Support\Facades\Mail;
use App\Http\Livewire\Budgets;
use App\Http\Livewire\IncomesOperationsAdmin;
use App\Http\Livewire\OperationsAdmin;

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

    $randomNumber = rand(100, 999);
    $nameWithoutSpaces = str_replace(' ', '', $googleUser->name);

    // Check if user exists with the same email address
    $existingUser = User::where('email', $googleUser->email)->first();

    if ($existingUser) {
    if (!$existingUser->email_verified_at) {
        // User exists but email isn't verified, set verification with DateNow
        $existingUser->email_verified_at = now();
        $existingUser->save();
    }

    // Existing user with verified email, log them in
    Auth::login($existingUser);
    return redirect('/dashboard');
} else {
        $user = User::updateOrCreate([
    'google_id' => $googleUser->id,
    ], [
    'name' => $googleUser->name,
    'username' => $nameWithoutSpaces . $randomNumber,
    'email' => $googleUser->email,
    'email_verified_at' => now(), 
    ], function ($user) {
    if ($user->wasRecentlyCreated) {
        $user->email_verified_at = now();
    }
    });


        // Assign default role if not already assigned
        $defaultRole = Role::find(2); // Reemplaza 2 con el ID del rol por defecto
        if (!$user->hasRole($defaultRole)) {
            $user->assignRole($defaultRole);
        }

        // Log in the newly created user
        Auth::login($user);
        return redirect('/dashboard');
    }
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
    
    
    Route::get('categories', Categories::class)->name('categories');
    Route::get('income-categories', IncomeCategories::class)->name('income-categories');
    Route::get('expenses-categories', ExpensesCategories::class)->name('expenses-categories');
    Route::get('users', UsersCrud::class)->name('users');
      
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
    Route::get('general-report', ReportGeneralTable::class)->name('general-report');
    Route::get('support-contact', SupportContact::class)->name('support-contact');
    Route::get('admin-email-support', EmailAdmin::class)->name('admin-email-support');
    Route::get('options-categories', StatusCategories::class)->name('options-categories');
    Route::get('budgets', Budgets::class)->name('budgets');
    Route::get('incomes-admin', IncomesOperationsAdmin::class)->name('incomes-admin');
    Route::get('expenses-admin', OperationsAdmin::class)->name('expenses-admin');

    //Route::get('expense/{budget}', Operations::class)->name('expense');
});
