<?php

namespace App\Http\Controllers;

use App\Models\CourseEnrollment;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\College;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentDetail;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\StudentEnrollment;
use App\Models\StudentEnrollmentAdmin;
use Illuminate\Support\Facades\Hash;

class CourseEnrollmentController extends Controller
{
    /*
    * Method to display the Course Enroll Form.
    */
    public function enrollStudentForm(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            // get active years
            $years =  AcademicYear::where('status',1)->get();
            // get active subjects
            $courses =  Course::where('status',1)->get();

            // Get Students
            $students =  StudentDetail::where('status',1)->get();

            return view('course.course-enrollment', compact('years','courses','students'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

     /**
     * Method to Enroll Student to course.
     */
    public function enrollStudent(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
           $validatedFields = Validator::make($request->all(), [
                'year' => ['required', 'max:30'],
                'semester' => ['required', 'max:30'],
                'course' => ['required', 'max:30'],
                'student' => ['required', 'max:30'],
            ]);
            if ($validatedFields->fails()) {
                 \Session::flash('msgErr','Student was not enrolled, try again.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }else{
                // Unique CourseEnrollment validation
                $uniqueCourseEnrollment = CourseEnrollment::where('user_id', $request->input('student'))
                ->where('academic_year_id',$request->input('year'))
                ->where('semester_id',$request->input('semester'))
                ->where('course_id',$request->input('course'))
                ->count();
                if($uniqueCourseEnrollment > 0){
                    \Session::flash('msgErr','This Student has already been enrolled in this course this semester. try another.' );
                    return redirect()->back()->withErrors($validatedFields->errors())->withInput();
                }

                $courseEnrollment = new CourseEnrollment;
                $courseEnrollment->user_id = $request->input('student');
                $courseEnrollment->academic_year_id = $request->input('year');
                $courseEnrollment->semester_id = $request->input('semester');
                $courseEnrollment->course_id = $request->input('course');
                $courseEnrollment->assigned_by = Auth::user()->id;
                $courseEnrollment->status = 1;//(empty($request->input('status'))) ? 0 : 1;
                $courseEnrollment->save();

                 \Session::flash('msg','Student enrolled successfully.' );
                return redirect()->back();
            }
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to display All Grades
    */
    public function enrollmentList(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            if(request()->ajax()){
                return datatables()->of(CourseEnrollment::latest()->get())
                    ->addColumn('student_id', function($data){  
                        $output = '';
                        $user = User::where('id', $data->user_id)->first();
                        $studentDetail = (!empty($user))? StudentDetail::where('user_id', $user->id)->first() : '';  
                        $output = (!empty($studentDetail))? $studentDetail->student_id :'';
                        return  $output;
                    })
                    ->addColumn('course', function($data){  
                        $output = '';
                        $course = Course::where('id', $data->course_id)->first();  
                        $output = (!empty($course))? $course->course_name.' ('.$course->course_code.')' :'';
                        return  $output;
                    })
                    ->addColumn('credit_hour', function($data){  
                        $output = '';
                        $course = Course::where('id', $data->course_id)->first();  
                        $output = (!empty($course))? $course->credit_hour :'';
                        return  $output;
                    })
                    ->addColumn('semester', function($data){  
                        $output = '';
                        $semester = Semester::where('id', $data->semester_id)->first();  
                        $output = (!empty($semester))? $semester->semester :'';
                        return  $output;
                    })
                    ->addColumn('year', function($data){  
                        $output = '';
                        $year = AcademicYear::where('id', $data->academic_year_id)->first();  
                        $output = (!empty($year))? $year->year :'';
                        return  $output;
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
                        $viewUrl = url('view-grade/'.$data->id);
                        $editUrl = url('edit-grade/'.$data->id);
                        $changePassword = url('change-password/'.$data->id);
                        $deleteUrl = url('delete-student/'.$data->id);
                        $button = '<a class="" title="View Profile" href="'.$viewUrl.'">
                                    <i class="fa fa-eye text-orange"></i>
                                    </a>';
                        $button .= '&nbsp;';
                        $button .= '<a class="" title="Account Setting" href="'.$editUrl.'">
                                        <i class="fas fa-edit text-dark-pastel-blue"></i>
                                    </a>';
                        $button .= '&nbsp;';
                        if ($data->status == 1) {
                            $button .= '<a onclick="deactivateUser('.$data->id.')" title="Deactivate User" href="javascript:void(0);">
                                    <i class="fa fa-times-circle text-orange-red"></i>
                                    </a>';
                            $button .= '&nbsp;';
                        }else{
                            $button .= '<a onclick="activateUser('.$data->id.')" class="activate_user" href="javascript:void(0);" title="Activate User">
                                    <i class="fa fa-check text-green"></i>
                                    </a>';
                            $button .= '&nbsp;';
                        }
                        $button .= '<a class="" title="Delete" href="'.$deleteUrl.'">
                                    <i class="fa fa-trash text-orange-red" aria-hidden="true"></i>
                                    </a>';
                        return $button;

                    })
                    ->rawColumns(['student_id','course','credit_hour','semester','year','action','status'])
                    ->make(true);
                }            
            return view('course.course-enrollment-list');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseEnrollment $courseEnrollment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseEnrollment $courseEnrollment)
    {
        //
    }
}
