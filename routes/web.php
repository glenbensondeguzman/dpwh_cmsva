<?php

use App\Http\Controllers\CentralController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\MaterialRegionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegionController;
use Illuminate\Support\Facades\Route;



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

Route::get('/', [DashboardController::class, 'showMap']);

Route::any('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/archived', [DashboardController::class, 'restoreSourceView'])->name('archived');

Route :: put('/restoreArchived/{id}', [DashboardController::class,'restoreSource']); //restore data

Route :: delete('/delete/{id}', [DashboardController::class,'destroy'])->name('delete'); //delete data
Route::put('/materials/{id}', [DashboardController::class, 'updateValidation']);

Route::get('/edit/{id}', [DashboardController::class, 'edit'])->name('edit.form');
Route::put('/edit/{id}', [DashboardController::class, 'update'])->name('edit');


Route::get('/add', [DashboardController::class, 'create'])->name('add.form');
Route::post('/add', [DashboardController::class, 'store'])->name('add');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route ::middleware('auth')->prefix('users')->name('users.')->middleware(['auth'])->controller(UserController :: class)->group(function () {
    Route :: get('/', 'user_page')->name('view_users_page'); //show users form slash only when getting values to main when this is main
    Route :: get('/', 'index')->name('users'); //show users data always use slash only when getting values to main
    Route :: get( '/add', 'create')->name('add.form'); //show add form
    Route :: post( '/add', 'store')->name('add'); //add data
    Route :: get('/edit/{id}', 'edit' )->name('edit.form'); //show edit form
    Route :: put('/edit/{id}', 'update')->name('edit'); //edit data
    Route :: delete('/delete/{id}', 'destroy')->name('delete'); //delete data


});

Route::fallback(function()
{
return "Page not found!";
});

require __DIR__.'/auth.php';
