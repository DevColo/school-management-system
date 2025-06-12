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
    // Add College
    Route::get('add-college', 'CollegeController@addCollegeForm')->name('add-college');
    Route::post('add-college', 'CollegeController@addCollege')->name('add-college');

    //View College
    Route::get('college-list', 'CollegeController@viewCollegeList')->name('college-list');
    Route::post('activate-college', 'CollegeController@activateCollege')->name('activate-college');
    Route::post('deactivate-college', 'CollegeController@deactivateCollege')->name('deactivate-college');

    // Edit College
    Route::get('edit-college/{collegeId}', 'CollegeController@editCollegeForm')->name('edit-college');
    Route::post('edit-college/{collegeId}', 'CollegeController@editCollege')->name('edit-college');

    // Add Major
    Route::get('add-major', 'MajorController@addMajorForm')->name('add-major');
    Route::post('add-major', 'MajorController@addMajor')->name('add-major');

    //View Major
    Route::get('major-list', 'MajorController@viewMajorList')->name('major-list');
    Route::post('activate-major', 'MajorController@activateMajor')->name('activate-major');
    Route::post('deactivate-major', 'MajorController@deactivateMajor')->name('deactivate-major');

    // Edit Major
    Route::get('edit-major/{majorId}', 'MajorController@editMajorForm')->name('edit-major');
    Route::post('edit-major/{majorId}', 'MajorController@editMajor')->name('edit-major');

    // Add Course
    Route::get('add-course', 'CourseController@addCourseForm')->name('add-course');
    Route::post('add-course', 'CourseController@addCourse')->name('add-course');

    //View Course
    Route::get('course-list', 'CourseController@viewCourseList')->name('course-list');
    Route::post('activate-course', 'CourseController@activateCourse')->name('activate-course');
    Route::post('deactivate-course', 'CourseController@deactivateCourse')->name('deactivate-course');

    // Course Assignment
    Route::get('course-assignment', 'CourseController@courseAssignmentForm')->name('course-assignment');
    Route::post('course-assignment', 'CourseController@courseAssignment')->name('course-assignment');

    // View Course Assignment
    Route::get('course-assignment-list', 'CourseController@courseAssignmentList')->name('course-assignment-list');

    // Edit Course Assignment
    Route::get('edit-course-assignment/{courseAssignmentId}', 'CourseController@editCourseAssignmentForm')->name('edit-course-assignment');
    Route::post('edit-course-assignment/{courseAssignmentId}', 'CourseController@editCourseAssignment')->name('edit-course-assignment');

    // Edit Course
    Route::get('edit-course/{courseId}', 'CourseController@editCourseForm')->name('edit-course');
    Route::post('edit-course/{courseId}', 'CourseController@editCourse')->name('edit-course');

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

    // Activate & Deactivate Student
    Route::post('activate-student', 'StudentDetailController@activateStudent')->name('activate-student');
    Route::post('deactivate-student', 'StudentDetailController@deactivateStudent')->name('deactivate-student');

    // Get College Majors
    Route::get('get-majors/{collegeId}', 'MajorController@getMajors')->name('get-majors');

    //View Student Detail
    Route::get('student-list', 'StudentDetailController@viewStudentList')->name('student-list');
    Route::get('student-profile/{studentId}', 'StudentDetailController@studentProfile')->name('student-profile');
    Route::post('activate-student', 'StudentDetailController@activateStudent')->name('activate-student');
    Route::post('deactivate-student', 'StudentDetailController@deactivateStudent')->name('deactivate-student');

    // Edit Student Detail
    Route::get('edit-student/{studentId}', 'StudentDetailController@editStudentForm')->name('edit-student');
    Route::post('edit-student/{studentId}', 'StudentDetailController@editStudent')->name('edit-student');

    // Grades
    Route::get('grade-form', 'GradeController@displyGradeForm')->name('grade-form');
    Route::post('add-grade', 'GradeController@addStudentGrade')->name('add-grade');
    Route::get('all-grades', 'GradeController@allGrades')->name('all-grades');
    Route::get('get-semesters/{yearId}', 'GradeController@getSemesters')->name('get-semesters');
    Route::get('get-periods/{semesterId}', 'GradeController@getPeriods')->name('get-periods');
    Route::get('get-classes/{subjectId}', 'GradeController@getClasses')->name('get-classes');
    Route::get('get-courses/{collegeId}', 'GradeController@getCourses')->name('get-courses');
    Route::get('get-students/{classId}/{yearId}', 'GradeController@getStudents')->name('get-students');

    // Grade Sheet
    Route::get('my-gradesheet', 'GradeController@myGradeSheet')->name('my-gradesheet');

    // Lecturer: View Courses
    Route::get('my-lecturer-courses', 'LecturerController@myLecturerCourses')->name('my-lecturer-courses');
    Route::get('enrolled-students-list/{enrollmentId}', 'LecturerController@enrolledStudentList')->name('enrolled-students-list');
    Route::get('print-enrolled-students/{enrollmentId}', 'LecturerController@printEnrolledStudents')->name('print-enrolled-students');

    // Lecturer: Add Student Grade
    Route::get('add-student-grade/{enrollmentId}', 'LecturerController@addStudentGrade')->name('add-student-grade');
    Route::post('add-student-grade/{enrollmentId}', 'LecturerController@addStudentGradeSubmit')->name('add-student-grade');

    // Lecturer: Student Grades
    Route::get('enrolled-student-grades/{enrollmentId}', 'LecturerController@enrolledStudentGrades')->name('enrolled-student-grades');

    // Lecturer: Add Grade
    Route::get('add-grade/{studentDetailId}/{enrollmentId}', 'LecturerController@addGradeForm')->name('add-grade');
    Route::post('add-grade/{studentDetailId}/{enrollmentId}', 'LecturerController@addGrade')->name('add-grade');

    // Lecturer: Edit Grade
    Route::get('edit-grade/{gradeId}/{enrollmentId}', 'LecturerController@editGradeForm')->name('edit-grade');
    Route::post('edit-grade/{gradeId}/{enrollmentId}', 'LecturerController@editGrade')->name('edit-grade');

    // Course Enrollment
    Route::get('enroll-student', 'CourseEnrollmentController@enrollStudentForm')->name('enroll-student');
    Route::post('enroll-student', 'CourseEnrollmentController@enrollStudent')->name('enroll-student');

    // View Enrollment
    Route::get('enrollment-list', 'CourseEnrollmentController@enrollmentList')->name('enrollment-list');

    // Admin: View Assigned Courses
    Route::get('grade-courses', 'GradeController@gradeCourses')->name('grade-courses');
    Route::get('enrolled-students/{enrollmentId}', 'GradeController@enrolledStudents')->name('enrolled-students');
    Route::get('admin-print-enrolled-students/{enrollmentId}', 'GradeController@printEnrolledStudents')->name('print-enrolled-students');

    // Admin: Student Grades
    Route::get('student-grades/{enrollmentId}', 'GradeController@studentGrades')->name('student-grades');

    // Admin: Approve Grade
    Route::post('approve-grade', 'GradeController@approveGrade')->name('approve-grade');

    // Student: View Courses
    Route::get('my-student-courses', 'StudentDetailController@myStudentCourses')->name('my-student-courses');

    // Student: Drop Course
    Route::post('drop-course', 'StudentDetailController@dropCourse')->name('drop-course');

    // Course Drop Limit
    Route::get('course-drop-limit', 'SettingsController@courseDropLimitForm')->name('course-drop-limit');
    Route::post('course-drop-limit', 'SettingsController@courseDropLimit')->name('course-drop-limit');

    // Course Credit Course
    Route::get('course-credit-cost', 'SettingsController@courseCreditCostForm')->name('course-credit-cost');
    Route::post('course-credit-cost', 'SettingsController@courseCreditCost')->name('course-credit-cost');
    Route::get('credit-cost-list', 'SettingsController@creditCostList')->name('credit-cost-list');
    Route::get('edit-credit-cost/{creditId}', 'SettingsController@creditCostEditForm')->name('edit-credit-cost');
    Route::post('edit-credit-cost/{creditId}', 'SettingsController@creditCostEdit')->name('edit-credit-cost');

});

// AI Research
//Route::get('chat', 'ResearchController@chat')->name('chat');
//Route::post('updateAdminImg','AccountController@updateAdminImg')->name('updateAdminImg')->middleware('auth');
//Route::post('updateAdminAccount','AccountController@updateAdminAccount')->name('updateAdminAccount')->middleware('auth');
