<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\College;
use App\Models\AssignCourse;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\AssignLecturerCourse;
use App\Models\CourseEnrollment;

class CourseController extends Controller
{
    /**
     * Method to display the Course Form.
     */
    public function addCourseForm(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            // get active colleges
            $colleges =  College::where('status',1)->get();
            return view('course.add-course', compact('colleges'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Method to add Course.
     */
    public function addCourse(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            //dd($request->all());die;
           $validatedFields = Validator::make($request->all(), [
                'college' => ['required','max:255'],
                'course_name' => ['required', 'string', 'max:255', 'unique:courses'],
                'credit_hour' => ['required', 'string', 'max:255'],
            ]);
            if ($validatedFields->fails()) {
                \Session::flash('msgErr','Course was not created, try again.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }

            // Course Code Generation
            $course_code = $request->input('course_code');
            if (empty($course_code)) {
                $code = (DB::table('courses')->count()) +1;
                $course_code = "CRS" . str_pad($code, 4, "0", STR_PAD_LEFT);
            }

            $course = new Course;
            $course->course_name = $request->input('course_name');
            $course->course_code = $course_code;
            $course->credit_hour = $request->input('credit_hour');
            $course->created_by = Auth::user()->id;
            $course->status = (empty($request->input('status'))) ? 0 : 1;
            $course->save();

            if (!empty($request->input('college'))) {
                foreach($request->input('college') as $college){
                    $assignCourse = new AssignCourse;
                    $assignCourse->course_id = $course->id;
                    $assignCourse->college_id = $college;
                    $assignCourse->assigned_by = Auth::user()->id;
                    $assignCourse->save();
                }
            }

            \Session::flash('msg','Course was created successfully.' );
            return redirect()->back();
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /*
     * Method to display Course list
    */
    public function viewCourseList(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
        if(request()->ajax()){
            return datatables()->of(Course::latest()->get())
                ->addColumn('username', function($data){  
                    $output = ''; 
                    if (!empty($data->created_by)) {
                        $result = User::where('id', $data->created_by)->first(); 
                        $output = (!empty($result->user_name))? $result->user_name :'';
                    }
                    return  $output;
                })
                ->addColumn('colleges', function($data){  
                    $output = ' ';
                    $colleges = AssignCourse::where('course_id', $data->id)->get();  
                    if (!empty($colleges)) {
                        foreach ($colleges as $college) {
                            $theCollege = College::where('id', $college->college_id)->first(); 
                            $output = $output.'<a href="'.url('college-list?search='.$theCollege->college_name).'" class="btn-warning text-dark text-md btn">'.$theCollege->college_name.'</a>&nbsp;'; 
                        }
                    }
                    return  $output;
                })
                ->addColumn('created', function($data){  
                    $strtotime =  strtotime($data->created_at);
                    $date = date('F j, Y H:i', $strtotime);
                    return  $date;
                })
                ->addColumn('status', function($data){             
                    if ($data->status == 1) {
                        $class = 'btn-success';
                        $status = 'Active'; 
                    }else{
                        $status='Inactive';
                        $class = 'btn-danger';
                    }
                    $status_btn = '<a href="#" class="'.$class.' white-text delete btn">'.$status.'</a>'; 
                    return  $status_btn;
                })
                ->addColumn('action', function($data){
                    $viewUrl = url('subject-grades-list/'.$data->id);
                    $editUrl = url('edit-subject/'.$data->id);
                    $deleteUrl = url('delete-subject/'.$data->id);
                    $button = '<a class="" title="View Subject Roster" href="'.$viewUrl.'">
                                <i class="fa fa-eye text-orange"></i>
                                </a>';
                    $button .= '&nbsp;';
                    $button .= '<a class="" title="Edit Subject" href="'.$editUrl.'">
                                    <i class="fas fa-edit text-dark-pastel-blue"></i>
                                </a>';
                    $button .= '&nbsp;';
                    if ($data->status == 1) {
                        $button .= '<a onclick="deactivateSubject('.$data->id.')" title="Deactivate Subject" href="javascript:void(0);">
                                <i class="fa fa-times-circle text-orange-red"></i>
                                </a>';
                        $button .= '&nbsp;';
                    }else{
                        $button .= '<a onclick="activateSubject('.$data->id.')" class="activate_class" href="javascript:void(0);" title="Activate Subject">
                                <i class="fa fa-check text-green"></i>
                                </a>';
                        $button .= '&nbsp;';
                    }
                    $button .= '<a class="" title="Delete Subject" href="'.$deleteUrl.'">
                                <i class="fa fa-trash text-orange-red" aria-hidden="true"></i>
                                </a>';
                    return $button;

                })
                ->rawColumns(['username','colleges','created','updated','action','status'])
                ->make(true);
            }            
            return view('course.course-list');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Course Assignment Form.
     */
    public function courseAssignmentForm() {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            // get active colleges
            $colleges =  College::where('status',1)->get();

            // get active years
            $years =  AcademicYear::where('status',1)->get();

            // get lecturers
            $lecturers = User::whereHas('roles', function ($query) {
                $query->where('name', 'lecturer');
            })->join('user_detail', 'user_detail.user_id', 'users.id')->select('users.id','users.email','user_detail.first_name','user_detail.other_name','user_detail.last_name')->get();

            return view('course.course-assignment', compact('colleges','years','lecturers'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Course Assignment
     */
    public function courseAssignment(Request $request) {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            $validatedFields = Validator::make($request->all(), [
                'college' => ['required','max:255'],
                'course' => ['required','max:255',],
                'year' => ['required','max:255'],
                'semester' => ['required','max:255'],
                'lecturer' => ['required','max:255'],
            ]);
            if ($validatedFields->fails()) {
                \Session::flash('msgErr','Course was not assigned to lecturer, try again.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }

            // Unique Course Assignment
            $uniqueCourseAssignment = AssignLecturerCourse::where('college_id', $request->input('college'))
            ->where('course_id',$request->input('course'))
            ->where('academic_year_id',$request->input('year'))
            ->where('semester_id',$request->input('semester'))
            ->where('user_id',$request->input('lecturer'))
            ->where('status', 1)
            ->count();
            if($uniqueCourseAssignment > 0){
                \Session::flash('msgErr','This Course has already been assigned to this lecturer for this semester, try another.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }

            // Unique Course Assignment For Others
            $uniqueCourseAssignment2 = AssignLecturerCourse::where('college_id', $request->input('college'))
            ->where('course_id',$request->input('course'))
            ->where('academic_year_id',$request->input('year'))
            ->where('semester_id',$request->input('semester'))
            ->where('status', 1)
            ->count();
            if($uniqueCourseAssignment2 > 0){
                \Session::flash('msgErr','This Course has already been assigned to another lecturer for this semester, try another.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }

            $courseAssignment = new AssignLecturerCourse;
            $courseAssignment->college_id = $request->input('college');
            $courseAssignment->course_id = $request->input('course');
            $courseAssignment->academic_year_id = $request->input('year');
            $courseAssignment->semester_id = $request->input('semester');
            $courseAssignment->user_id = $request->input('lecturer');
            $courseAssignment->assigned_by = Auth::user()->id;
            $courseAssignment->status = (empty($request->input('status'))) ? 0 : 1;
            $courseAssignment->save();

            \Session::flash('msg','Course was assigned to lecturer successfully.' );
            return redirect()->back();
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Course Assignment List
     */
    public function courseAssignmentList() {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            if(request()->ajax()){
            return datatables()->of(AssignLecturerCourse::latest()->get())
                ->addColumn('college', function($data){  
                    $college = College::where('id', $data->college_id)->first();  
                    $output = (!empty($college)) ? $college->college_name : '';
                    return  $output;
                })
                ->addColumn('course', function($data){  
                    $course = Course::where('id', $data->course_id)->first();  
                    $output = (!empty($course)) ? $course->course_name.' ('.$course->course_code.')' : '';
                    return  $output;
                })
                ->addColumn('year', function($data){  
                    $year = AcademicYear::where('id', $data->academic_year_id)->first();  
                    $output = (!empty($year)) ? $year->year : '';
                    return  $output;
                })
                ->addColumn('semester', function($data){  
                    $semester = Semester::where('id', $data->semester_id)->first();  
                    $output = (!empty($semester)) ? $semester->semester : '';
                    return  $output;
                })
                ->addColumn('enrolled_students', function($data){  
                    $semester = Semester::where('id', $data->semester_id)->first();  
                    $output = (!empty($semester)) ? $semester->semester : '';
                    return  $output;
                })
                ->addColumn('lecturer', function($data){  
                    $lecturer = User::where('id', $data->user_id)->first();
                    if (!empty($lecturer)) {
                        $user_detail = UserDetail::where('user_id', $lecturer->id)->first();
                        $output = (!empty($user_detail)) ? $user_detail->first_name.' '.$user_detail->other_name.' '.$user_detail->last_name.' ('.$lecturer->user_name.')' : '';
                      }  
                    
                    return  $output;
                })
                ->addColumn('created', function($data){  
                    $strtotime =  strtotime($data->created_at);
                    $date = date('F j, Y H:i', $strtotime);
                    return  $date;
                })
                ->addColumn('status', function($data){             
                    if ($data->status == 1) {
                        $class = 'btn-success';
                        $status = 'Active'; 
                    }else{
                        $status='Inactive';
                        $class = 'btn-danger';
                    }
                    $status_btn = '<a href="#" class="'.$class.' white-text delete btn">'.$status.'</a>'; 
                    return  $status_btn;
                })
                ->addColumn('action', function($data){
                    $viewUrl = url('subject-grades-list/'.$data->id);
                    $editUrl = url('edit-course-assignment/'.$data->id);
                    $deleteUrl = url('delete-subject/'.$data->id);
                    $button = '<a class="" title="View Student Roster" href="'.$viewUrl.'">
                                <i class="fa fa-eye text-orange"></i>
                                </a>';
                    $button .= '&nbsp;';
                    $button .= '<a class="" title="Edit Course Assignment" href="'.$editUrl.'">
                                    <i class="fas fa-edit text-dark-pastel-blue"></i>
                                </a>';
                    $button .= '&nbsp;';
                    if ($data->status == 1) {
                        $button .= '<a onclick="deactivateSubject('.$data->id.')" title="Grade Sheet" href="javascript:void(0);">
                                <i class="fa fa-tasks text-dark-pastel-blue"></i>
                                </a>';
                        $button .= '&nbsp;';
                    }
                    return $button;

                })
                ->rawColumns(['college','course','year','semester','enrolled_students','lecturer','created','updated','action','status'])
                ->make(true);
            }            
            return view('course.course-assignment-list');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Show the form for editing the course assignment.
     */
    public function editCourseAssignmentForm($courseAssignmentId) {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            $courseAssignment = AssignLecturerCourse::findOrFail($courseAssignmentId);
            if (!empty($courseAssignment)) {
                // get active colleges
                $colleges =  College::where('status',1)->get();

                // get active years
                $years =  AcademicYear::where('status',1)->get();

                // get lecturers
                $lecturers = User::whereHas('roles', function ($query) {
                    $query->where('name', 'lecturer');
                })->join('user_detail', 'user_detail.user_id', 'users.id')->select('users.id','users.email','user_detail.first_name','user_detail.other_name','user_detail.last_name')->get();

                return view('course.edit-course-assignment', compact('courseAssignment','colleges','years','lecturers'));
            }else{
                abort(404, 'Course Assignment Not Found.');
            }
            
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Update the course assignment.
     */
    public function editCourseAssignment(Request $request) {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            $courseAssignment = AssignLecturerCourse::findOrFail($request->input('course_assignment_id'));
            if (!empty($courseAssignment)) {
                $validatedFields = Validator::make($request->all(), [
                'college' => ['required','max:255'],
                'course' => ['required','max:255',],
                'year' => ['required','max:255'],
                'semester' => ['required','max:255'],
                'lecturer' => ['required','max:255'],
                ]);
                if ($validatedFields->fails()) {
                    \Session::flash('msgErr','Course Assignment was not updated, try again.' );
                    return redirect()->back()->withErrors($validatedFields->errors())->withInput();
                }

                // Unique Course Assignment
                $uniqueCourseAssignment = AssignLecturerCourse::where('college_id', $request->input('college'))
                ->where('course_id',$request->input('course'))
                ->where('academic_year_id',$request->input('year'))
                ->where('semester_id',$request->input('semester'))
                ->where('user_id',$request->input('lecturer'))
                ->where('id','!=',$request->input('course_assignment_id'))
                ->where('status', 1)
                ->count();
                if($uniqueCourseAssignment > 0){
                    \Session::flash('msgErr','This Course has already been assigned to this lecturer for this semester. try another.' );
                    return redirect()->back()->withErrors($validatedFields->errors())->withInput();
                }

                $courseAssignment->college_id = $request->input('college');
                $courseAssignment->course_id = $request->input('course');
                $courseAssignment->academic_year_id = $request->input('year');
                $courseAssignment->semester_id = $request->input('semester');
                $courseAssignment->user_id = $request->input('lecturer');
                $courseAssignment->updated_by = Auth::user()->id;
                $courseAssignment->status = (empty($request->input('status'))) ? 0 : 1;
                $courseAssignment->update();

            \Session::flash('msg','Course Assignment was updated successfully.' );
            return redirect()->back();
            }else{
                abort(404, 'Course Assignment Not Found.');
            }
            
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

}
