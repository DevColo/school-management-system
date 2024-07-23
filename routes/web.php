<?php

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Admin profile
Route::get('user-profile/{user_id}', 'UserController@userProfilePage')->middleware(['auth'])->name('user-profile');
Route::get('account-setting/{user_id}', 'UserController@userProfileEditPage')->middleware(['auth'])->name('account-setting');
Route::post('update-user-profile', 'UserController@updateUserProfile')->middleware(['auth'])->name('update-user-profile');

// User password
Route::get('change-password/{user_id}', 'UserController@changePasswordForm')->middleware(['auth'])->name('change-password');
Route::post('change-user-password', 'UserController@changePassword')->middleware(['auth'])->name('change-user-password');

// Add Admin
Route::get('add-admin-form', 'UserController@addAdminForm')->middleware(['auth'])->name('add-admin-form');
Route::post('add-admin', 'UserController@addAdmin')->middleware(['auth'])->name('add-admin');
Route::post('updateAdminImg','UserController@updateUserProfilePhoto')->name('updateAdminImg')->middleware('auth');

// View Admin
Route::get('admin-list', 'UserController@viewAdminList')->middleware(['auth'])->name('admin-list');
Route::post('activate-user', 'UserController@activateUser')->middleware(['auth'])->name('activate-user');
Route::post('deactivate-user', 'UserController@deactivateUser')->middleware(['auth'])->name('deactivate-user');

// Manage Roles
Route::get('manage-roles', 'RoleController@manageRoles')->middleware(['auth'])->name('manage-roles');
Route::resource('addRole', 'RoleController')->middleware(['auth']);
Route::get('edit-role/{role_id}', 'RoleController@editRoleForm')->middleware(['auth'])->name('edit-role');
Route::post('update-role', 'RoleController@updateRole')->middleware(['auth'])->name('update-role');

// AI Research
Route::get('chat', 'ResearchController@chat')->name('chat');
//Route::post('updateAdminImg','AccountController@updateAdminImg')->name('updateAdminImg')->middleware('auth');
//Route::post('updateAdminAccount','AccountController@updateAdminAccount')->name('updateAdminAccount')->middleware('auth');
