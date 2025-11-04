<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\MasterController;

use App\Http\Controllers\ProductManagementController;


Route::middleware(['guest'])->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('loginstore', [AuthController::class, 'Loginstore'])->name('loginstore');
});

Route::middleware(['auth'])->group(function () {
    # Auth Controller
    Route::post('sessions-change', [AuthController::class, 'sessionsChange'])->name('sessions.change');


    # Master Controller
    Route::get('/', [MasterController::class, 'index'])->name('dashboard');
    Route::get('roles', [MasterController::class, 'role'])->name('role.index');
    Route::get('role-add', [MasterController::class, 'role_add']);
    Route::post('/role/store', [MasterController::class, 'rolestore'])->name('role.store');
    Route::get('/role/edit/{id}', [MasterController::class, 'roleedit'])->name('role.edit');
    Route::post('/role/update/{id}', [MasterController::class, 'roleupdate'])->name('role.update');
    Route::get('/role/delete/{id}', [MasterController::class, 'roledestroy'])->name('role.delete');
    Route::get('users', [MasterController::class, 'userindex'])->name('users.index');
    Route::get('users/create', [MasterController::class, 'usercreate'])->name('users.create');
    Route::post('users/store', [MasterController::class, 'userstore'])->name('users.store');
    Route::get('users/{id}/edit', [MasterController::class, 'useredit'])->name('users.edit');
    Route::put('users/{id}', [MasterController::class, 'userupdate'])->name('users.update');
    Route::get('users/{id}', [MasterController::class, 'userdestroy'])->name('users.destroy');


Route::get('users/profile/{id}', [MasterController::class, 'viewProfile'])->name('users.profile');
// routes/web.php




    // AJAX endpoints for country/state/city
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');


    Route::match(['get', 'post'], 'settings', [MasterController::class, 'settings'])->name('settings');
    
    
    
//Teacher
// Route::match(['get', 'post'], 'teachers/index', [StaffController::class, 'index']);
// Route::match(['get', 'post'], 'teachers/add', [StaffController::class, 'add']);


Route::match(['get', 'post'], 'teachers/index', [StaffController::class, 'index']);
Route::match(['get', 'post'], 'teachers/add', [StaffController::class, 'add']);

});
