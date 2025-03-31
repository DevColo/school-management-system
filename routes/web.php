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
    //return view('welcome');
    return view('auth.login');
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


Route::middleware(['auth'])->group(function () {
    // Add Class
    Route::get('add-class', 'ClassesController@addClassForm')->name('add-class');
    Route::post('add-class', 'ClassesController@addClass')->name('add-class');

    //View Class
    Route::get('class-list', 'ClassesController@viewClassList')->name('class-list');
    Route::post('activate-class', 'ClassesController@activateClass')->name('activate-class');
    Route::post('deactivate-class', 'ClassesController@deactivateClass')->name('deactivate-class');

    // edit class
    Route::get('edit-class/{classId}', 'ClassesController@editClassForm')->name('edit-class');
    Route::post('edit-class/{classId}', 'ClassesController@editClass')->name('edit-class');

    // Add Academic Year
    Route::get('add-year', 'AcademicYearController@addYearForm')->name('add-year');
    Route::post('add-year', 'AcademicYearController@addYear')->name('add-year');

    //View Academic Year
    Route::get('year-list', 'AcademicYearController@viewYearList')->name('year-list');
    Route::post('activate-year', 'AcademicYearController@activateYear')->name('activate-year');
    Route::post('deactivate-year', 'AcademicYearController@deactivateYear')->name('deactivate-year');

    // Edit Academic Year
    Route::get('edit-year/{yearId}', 'AcademicYearController@editYearForm')->name('edit-year');
    Route::post('edit-year/{yearId}', 'AcademicYearController@editYear')->name('edit-year');

    // Add Semester
    Route::get('add-semester', 'SemesterController@addSemesterForm')->name('add-semester');
    Route::post('add-semester', 'SemesterController@addSemester')->name('add-semester');

    //View Semester
    Route::get('semester-list', 'SemesterController@viewSemesterList')->name('semester-list');
    Route::post('activate-semester', 'SemesterController@activateSemester')->name('activate-semester');
    Route::post('deactivate-semester', 'SemesterController@deactivateSemester')->name('deactivate-semester');

    // Edit Semester
    Route::get('edit-semester/{semesterId}', 'SemesterController@editSemesterForm')->name('edit-semester');
    Route::post('edit-semester/{semesterId}', 'SemesterController@editSemester')->name('edit-semester');

    // Add Period
    Route::get('add-period', 'PeriodController@addPeriodForm')->name('add-period');
    Route::post('add-period', 'PeriodController@addPeriod')->name('add-period');

    //View Period
    Route::get('period-list', 'PeriodController@viewPeriodList')->name('period-list');
    Route::post('activate-period', 'PeriodController@activatePeriod')->name('activate-period');
    Route::post('deactivate-period', 'PeriodController@deactivatePeriod')->name('deactivate-period');

    // Edit Period
    Route::get('edit-period/{periodId}', 'PeriodController@editPeriodForm')->name('edit-period');
    Route::post('edit-period/{periodId}', 'PeriodController@editPeriod')->name('edit-period');

    // Add Subject
    Route::get('add-subject', 'SubjectController@addSubjectForm')->name('add-subject');
    Route::post('add-subject', 'SubjectController@addSubject')->name('add-subject');

    //View Subject
    Route::get('subject-list', 'SubjectController@viewSubjectList')->name('subject-list');
    Route::post('activate-subject', 'SubjectController@activateSubject')->name('activate-subject');
    Route::post('deactivate-subject', 'SubjectController@deactivateSubject')->name('deactivate-subject');

    // Edit Subject
    Route::get('edit-subject/{subjectId}', 'SubjectController@editSubjectForm')->name('edit-subject');
    Route::post('edit-subject/{subjectId}', 'SubjectController@editSubject')->name('edit-subject');

    // Add Student Detail
    Route::get('add-student', 'StudentDetailController@addStudentForm')->name('add-student');
    Route::post('add-student', 'StudentDetailController@addStudent')->name('add-student');

    //View Student Detail
    Route::get('student-list', 'StudentDetailController@viewStudentList')->name('student-list');
    Route::get('student-profile/{studentId}', 'StudentDetailController@studentProfile')->name('student-profile');
    Route::post('activate-student', 'StudentDetailController@activateStudent')->name('activate-student');
    Route::post('deactivate-student', 'StudentDetailController@deactivateStudent')->name('deactivate-student');

    // Edit Student Detail
    Route::get('edit-student/{studentId}', 'StudentDetailController@editStudentForm')->name('edit-student');
    Route::post('edit-student/{studentId}', 'StudentDetailController@editStudent')->name('edit-student');
});


// AI Research
Route::get('chat', 'ResearchController@chat')->name('chat');
//Route::post('updateAdminImg','AccountController@updateAdminImg')->name('updateAdminImg')->middleware('auth');
//Route::post('updateAdminAccount','AccountController@updateAdminAccount')->name('updateAdminAccount')->middleware('auth');
