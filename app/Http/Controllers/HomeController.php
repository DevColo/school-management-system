<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\StudentDetail;
use App\Models\College;
use App\Models\StudentEnrollment;
use App\Models\AssignCourse;
use App\Models\CourseEnrollment;
use App\Models\CourseCreditCost;
use App\Models\StudentPayment;
use App\Models\Course;

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

            // total lecturer
            $lecturer_count = DB::table('users')
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('roles.name','lecturer')
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

            // total colleges
            $colleges_count = DB::table('colleges')
                ->where('status',1)
                ->count();

            // total courses
            $courses_count = DB::table('courses')
                ->where('status',1)
                ->count();

            $total_admins = $admin_count + $super_admin_count;

            // user detail
            $user_detail = DB::table('user_detail')->where('user_id',Auth::user()->id)->get();

            return view('dashboard.admin-dashboard',compact('student_count','lecturer_count','total_admins','user_detail','male_student_count','female_student_count','colleges_count','courses_count'));
        }
        else if(Auth::user()->hasRole('student') && (Auth::user()->status == 1)) {
            $studentDetail = StudentDetail::where('user_id', Auth::user()->id)->first();
            $studentEnrollment = StudentEnrollment::where('student_detail_id', $studentDetail->id)->first(); 
            $courseEnrollmentCount = CourseEnrollment::where('user_id', Auth::user()->id)->count();
            $courseEnrollments = CourseEnrollment::where('user_id', Auth::user()->id)->get();

            $totalCost = 0;
            $studentId = Auth::user()->id;

            if (!empty($courseEnrollments)) {
                foreach ($courseEnrollments as $courseEnrollment) {
                    // Get the course's credit hour
                    $creditHour = Course::where('id', $courseEnrollment->course_id)->value('credit_hour');

                    if ($creditHour) {
                        // Find the cost for this credit hour
                        $creditCost = CourseCreditCost::where('credit_hour', $creditHour)->first();

                        if ($creditCost) {
                            $totalCost += $creditCost->cost;
                        }
                    }
                }
            }
            // Get total amount paid by the student
            $totalPaid = StudentPayment::where('student_detail_id', $studentDetail->id)->sum('amount');

            // Calculate amount owed
            $studentBalance = $totalCost - $totalPaid;

            if ($studentBalance <= 0) {
                $studentBalance = 0;
            }

            return view('dashboard.student-dashboard', compact('studentDetail','studentEnrollment', 'courseEnrollmentCount','studentBalance'));
        }
        else if(Auth::user()->hasRole('lecturer') && (Auth::user()->status == 1)) {
            // total assigned courses
            $total_assigned_courses = DB::table('assign_lecturer_courses')
                ->where('user_id', Auth::user()->id)
                ->count();

            // tactive assigned courses
            $active_assigned_courses = DB::table('assign_lecturer_courses')
                ->where('user_id', Auth::user()->id)
                ->where('status', 1)
                ->count();

            // completed assigned courses
            $completed_assigned_courses = DB::table('assign_lecturer_courses')
                ->where('user_id', Auth::user()->id)
                ->where('status', 0)
                ->count();

            return view('dashboard.lecturer-dashboard',compact('total_assigned_courses','active_assigned_courses','completed_assigned_courses'));
        }
        else{
            abort(403, 'Unauthorized access.');
        }
    }
}
