<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\StudentDetail;
use App\Models\Classes;
use App\Models\StudentEnrollment;
use App\Models\AssignSubject;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if((Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) && (Auth::user()->status == 1)) {
            // total student
            $student_count = DB::table('users')
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('roles.name','student')
                ->where('users.status',1)
                ->count();

            // total male student
            $male_student_count = DB::table('users')
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->join('student_details', 'student_details.user_id', '=', 'users.id')
                ->where('student_details.gender','Male')
                ->where('roles.name','student')
                ->where('users.status',1)
                ->count();

            // total female student
            $female_student_count = DB::table('users')
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->join('student_details', 'student_details.user_id', '=', 'users.id')
                ->where('student_details.gender','Female')
                ->where('roles.name','student')
                ->where('users.status',1)
                ->count();

            // total classes
            $classes_count = DB::table('classes')
                ->where('status',1)
                ->count();

            // total librarian
            $librarian_count = DB::table('users')
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('roles.name','librarian')
                ->where('users.status',1)
                ->count();

            // total admin
            $admin_count = DB::table('users')
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('roles.name','admin')
                ->where('users.status',1)
                ->count();

            // total super_admin  
            $super_admin_count = DB::table('users')
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('roles.name','superadmin')
                ->where('users.status',1)
                ->count();

            $total_admins = $admin_count + $super_admin_count;

            // user detail
            $user_detail = DB::table('user_detail')->where('user_id',Auth::user()->id)->get();

            return view('dashboard.admin-dashboard',compact('student_count','classes_count','librarian_count','total_admins','user_detail','male_student_count','female_student_count'));
        }
        else if(Auth::user()->hasRole('student') && (Auth::user()->status == 1)) {
            $studentDetail = StudentDetail::where('user_id', Auth::user()->id)->first();

            $class = '';
            $subject_count = 0;
            $studentEnrollment = StudentEnrollment::where('student_detail_id', $studentDetail->id)->first();  
            if (!empty($studentEnrollment)) {
                $class = (!empty($studentEnrollment->class_id))? Classes::where('id', $studentEnrollment->class_id)->first() :'';
                $class = (!empty($class->class_name))? $class->class_name :'';

                $output = ' ';
                $subject_count = (!empty($studentEnrollment->class_id))? AssignSubject::where('class_id', $studentEnrollment->class_id)->count() : 0;
            }



             return view('dashboard.student-dashboard', compact('studentDetail','class','subject_count'));
        }
        else{
            abort(403, 'Unauthorized access.');
        }
    }
}
