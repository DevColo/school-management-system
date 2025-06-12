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
use App\Models\AssignLecturerCourse;
use App\Models\UserDetail;
use App\Models\Grade;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class LecturerController extends Controller
{
    /**
     * Lecturer: View Courses
     */
    public function myLecturerCourses() {
        if(Auth::user()->hasRole('lecturer') && (Auth::user()->status == 1)) { 
            if(request()->ajax()){
            return datatables()->of(AssignLecturerCourse::where('user_id', Auth::user()->id)->latest()->get())
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
                    $courseEnrollment = CourseEnrollment::where('course_id', $data->course_id)
                    ->where('academic_year_id', $data->academic_year_id)  
                    ->where('semester_id', $data->semester_id)->count();  
                    return  $courseEnrollment;
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
                    $viewUrl = url('enrolled-students-list/'.$data->id);
                    $gradesUrl = url('enrolled-student-grades/'.$data->id);
                    $deleteUrl = url('delete-subject/'.$data->id);
                    $button = '<a class="" title="View Student Roster" href="'.$viewUrl.'">
                                <i class="fa fa-eye text-orange"></i>
                                </a>';
                    $button .= '&nbsp;';
                    if ($data->status == 1) {
                        $button .= '<a href="'.$gradesUrl.'" title="Students Grades" href="javascript:void(0);">
                                <i class="fa fa-tasks text-dark-pastel-blue"></i>
                                </a>';
                        $button .= '&nbsp;';
                    }
                    return $button;

                })
                ->rawColumns(['college','course','year','semester','enrolled_students','created','updated','action','status'])
                ->make(true);
            }            
            return view('lecturer.my-lecturer-courses');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * View Enrolled Student List
     */
    public function enrolledStudentList($enrollmentId) {
        if(Auth::user()->hasRole('lecturer') && (Auth::user()->status == 1)) { 
            $assignLecturerCourse = AssignLecturerCourse::where('id', $enrollmentId)->where('user_id', Auth::user()->id)->first();
                
            $courseEnrollments = (!empty($assignLecturerCourse))? CourseEnrollment::where('course_id', $assignLecturerCourse->course_id)->where('academic_year_id', $assignLecturerCourse->academic_year_id)->where('semester_id', $assignLecturerCourse->semester_id)->latest()->get() : '' ;

            // Course
            $course = (!empty($assignLecturerCourse)) ? Course::where('id', $assignLecturerCourse->course_id)->first() : '';
            $course = (!empty($course)) ? $course->course_name.' ('.$course->course_code.')' : '';

            // Year
            $year = (!empty($assignLecturerCourse)) ? AcademicYear::where('id', $assignLecturerCourse->academic_year_id)->first() : '';
            $year = (!empty($year)) ? $year->year : '';

            // Course
            $semester = (!empty($assignLecturerCourse)) ? Semester::where('id', $assignLecturerCourse->semester_id)->first() : '';
            $semester = (!empty($semester)) ? $semester->semester : '';

            if(request()->ajax()){
            return datatables()->of($courseEnrollments)
                ->addColumn('college', function($data){  
                    $studentDetail = StudentDetail::where('user_id', $data->user_id)->first();  
                    $studentEnrollment = (!empty($studentDetail)) ? StudentEnrollment::where('student_detail_id', $studentDetail->id)->first() : '';  
                    $output = (!empty($studentEnrollment)) ? $studentEnrollment->College->college_name : '';
                    return  $output;
                })
                ->addColumn('student_id', function($data){   
                    $studentDetail = (!empty($data->user_id)) ? StudentDetail::where('user_id', $data->user_id)->first() : '';
                    $student_id = (!empty($studentDetail)) ? $studentDetail->student_id : '';
                    return  $student_id;
                })
                ->rawColumns(['college','student_id'])
                ->make(true);
            }            
            return view('lecturer.enrolled-students-list', compact('enrollmentId','course','year','semester'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Print Enrolled Students
     */
    public function printEnrolledStudents($enrollmentId) {
        if(Auth::user()->hasRole('lecturer') && (Auth::user()->status == 1)) { 
            $assignLecturerCourse = AssignLecturerCourse::where('id', $enrollmentId)->where('user_id', Auth::user()->id)->first();
                
            $courseEnrollments = (!empty($assignLecturerCourse))? CourseEnrollment::where('course_id', $assignLecturerCourse->course_id)->where('academic_year_id', $assignLecturerCourse->academic_year_id)->where('semester_id', $assignLecturerCourse->semester_id)->latest()->get() : '' ;

            // Course
            $course = (!empty($assignLecturerCourse)) ? Course::where('id', $assignLecturerCourse->course_id)->first() : '';
            $course = (!empty($course)) ? $course->course_name.' ('.$course->course_code.')' : '';

            // Year
            $year = (!empty($assignLecturerCourse)) ? AcademicYear::where('id', $assignLecturerCourse->academic_year_id)->first() : '';
            $year = (!empty($year)) ? $year->year : '';

            // Course
            $semester = (!empty($assignLecturerCourse)) ? Semester::where('id', $assignLecturerCourse->semester_id)->first() : '';
            $semester = (!empty($semester)) ? $semester->semester : '';

            // Lecturer
            $lecturer = UserDetail::where('id', Auth::user()->id)->first();  
            $lecturer = (!empty($lecturer)) ? $lecturer->first_name.' '.$lecturer->other_name.' '.$lecturer->last_name : '';

            $records = [];
            if (!empty($courseEnrollments)) {
                foreach($courseEnrollments as $courseEnrollment) {
                    //College
                    $studentDetail = StudentDetail::where('user_id', $courseEnrollment->user_id)->first();  
                    $studentEnrollment = (!empty($studentDetail)) ? StudentEnrollment::where('student_detail_id', $studentDetail->id)->first() : '';  
                    $college = (!empty($studentEnrollment)) ? $studentEnrollment->College->college_name : '';

                    //Student
                    $studentDetail = (!empty($courseEnrollment->user_id)) ? StudentDetail::where('user_id', $courseEnrollment->user_id)->first() : '';
                    $student_id = (!empty($studentDetail)) ? $studentDetail->student_id : '';

                    $records[] = [
                        'student_id' => $student_id,
                        'college' => $college,
                    ];
                }
            }

            $pdf = PDF::loadView('lecturer.enrolled-student-list-pdf', compact('records','course','year','semester','lecturer'));
            return $pdf->stream("enrolled-student-list.pdf");

            return view('lecturer.enrolled-students-list', compact('enrollmentId'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Enrolled Student Grades.
     */
    public function enrolledStudentGrades($enrollmentId) {
        if(Auth::user()->hasRole('lecturer') && (Auth::user()->status == 1)) { 
            $assignLecturerCourse = AssignLecturerCourse::where('id', $enrollmentId)->where('user_id', Auth::user()->id)->first();
                
            $courseEnrollments = (!empty($assignLecturerCourse))? CourseEnrollment::where('course_id', $assignLecturerCourse->course_id)->where('academic_year_id', $assignLecturerCourse->academic_year_id)->where('semester_id', $assignLecturerCourse->semester_id)->latest()->get() : '' ;

            // Course
            $course = (!empty($assignLecturerCourse)) ? Course::where('id', $assignLecturerCourse->course_id)->first() : '';
            $course = (!empty($course)) ? $course->course_name.' ('.$course->course_code.')' : '';

            // Year
            $year = (!empty($assignLecturerCourse)) ? AcademicYear::where('id', $assignLecturerCourse->academic_year_id)->first() : '';
            $year = (!empty($year)) ? $year->year : '';

            // Course
            $semester = (!empty($assignLecturerCourse)) ? Semester::where('id', $assignLecturerCourse->semester_id)->first() : '';
            $semester = (!empty($semester)) ? $semester->semester : '';

            if(request()->ajax()){
            return datatables()->of($courseEnrollments)
                ->addColumn('student_id', function($data){   
                    $studentDetail = (!empty($data->user_id)) ? StudentDetail::where('user_id', $data->user_id)->first() : '';
                    $student_id = (!empty($studentDetail)) ? $studentDetail->student_id : '';
                    return  $student_id;
                })
                ->addColumn('grade', function($data){   
                    $studentDetail = (!empty($data->user_id)) ? StudentDetail::where('user_id', $data->user_id)->first() : '';
                    $student_id = (!empty($studentDetail)) ? $studentDetail->student_id : '';
                    $grade = (!empty($studentDetail)) ? 
                    Grade::where('student_detail_id', $studentDetail->id)
                    ->where('academic_year_id',$data->academic_year_id)
                    ->where('semester_id',$data->semester_id)
                    ->where('course_id',$data->course_id)->value('grade') 
                    : '';
                    return  $grade;
                })
                ->addColumn('point', function($data){   
                    $studentDetail = (!empty($data->user_id)) ? StudentDetail::where('user_id', $data->user_id)->first() : '';
                    $student_id = (!empty($studentDetail)) ? $studentDetail->student_id : '';
                    $point = (!empty($studentDetail)) ? 
                    Grade::where('student_detail_id', $studentDetail->id)
                    ->where('academic_year_id',$data->academic_year_id)
                    ->where('semester_id',$data->semester_id)
                    ->where('course_id',$data->course_id)->value('point') 
                    : '';
                    return  $point;
                })
                ->addColumn('observation', function($data){   
                    $studentDetail = (!empty($data->user_id)) ? StudentDetail::where('user_id', $data->user_id)->first() : '';
                    $student_id = (!empty($studentDetail)) ? $studentDetail->student_id : '';
                    $observation = (!empty($studentDetail)) ? 
                    Grade::where('student_detail_id', $studentDetail->id)
                    ->where('academic_year_id',$data->academic_year_id)
                    ->where('semester_id',$data->semester_id)
                    ->where('course_id',$data->course_id)->value('observation') 
                    : '';

                    $class = '';
                    $status_text = '';
                    if ($observation == 'Pass') {
                        $class = 'btn-success';
                        $status_text = $observation; 
                    }else if ($observation != 'Pass' && $observation != '') {
                        $status_text= $observation;
                        $class = 'btn-danger';
                    }
                    $status_btn = '<a href="#" class="'.$class.' white-text btn btn-lg">'.$status_text.'</a>'; 
                    return  $status_btn;
                })
                ->addColumn('status', function($data){    
                    $studentDetail = (!empty($data->user_id)) ? StudentDetail::where('user_id', $data->user_id)->first() : '';
                    $student_id = (!empty($studentDetail)) ? $studentDetail->student_id : '';
                    $status = (!empty($studentDetail)) ? 
                    Grade::where('student_detail_id', $studentDetail->id)
                    ->where('academic_year_id',$data->academic_year_id)
                    ->where('semester_id',$data->semester_id)
                    ->where('course_id',$data->course_id)->value('status') 
                    : '';

                    $class = '';
                    $status_text = '';
                    if ($status == 1) {
                        $class = 'btn-primary';
                        $status_text = 'Approved'; 
                    }else if ($status == 2) {
                        $status_text='pending';
                        $class = 'btn-warning';
                    }
                    $status_btn = '<a href="#" class="'.$class.'  btn btn-lg">'.$status_text.'</a>'; 
                    return  $status_btn;
                })
                ->addColumn('action', function($data) use ($enrollmentId){
                    $studentDetail = (!empty($data->user_id)) ? StudentDetail::where('user_id', $data->user_id)->first() : '';
                    $grade = (!empty($studentDetail)) ? 
                    Grade::where('student_detail_id', $studentDetail->id)
                    ->where('academic_year_id',$data->academic_year_id)
                    ->where('semester_id',$data->semester_id)
                    ->where('course_id',$data->course_id)->first() 
                    : '';
                    $button = '&nbsp;';
                    if (!empty($grade) && $grade->status == 2) {
                        $editUrl = url('edit-grade/'.$grade->id.'/'.$enrollmentId);
                        $button .= '<a href="'.$editUrl.'" title="Edit Student Grade">
                                <i class="fa fa-edit text-dark-pastel-blue"></i>
                                </a>';
                        $button .= '&nbsp;';
                    }else if(empty($grade)){
                        $student_id = (!empty($studentDetail)) ? $studentDetail->id : '';
                        $addGradeUrl = url('add-grade/'.$student_id.'/'.$enrollmentId);
                        $button .= '<a href="'.$addGradeUrl.'" title="Add Student Grade" >
                                <i class="fa fa-pencil-alt text-dark-pastel-green"></i>
                                </a>';
                        $button .= '&nbsp;';
                    }
                    return $button;

                })
                ->rawColumns(['grade','point','observation','student_id','status','action'])
                ->make(true);
            }            
            return view('lecturer.enrolled-student-grades', compact('enrollmentId','course','year','semester'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function addStudentGrade($enrollmentId) {
        if((Auth::user()->hasRole('lecturer') && (Auth::user()->status == 1)) || (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin'))) { 
            if(Auth::user()->hasRole('lecturer') && (Auth::user()->status == 1)) {
                $assignLecturerCourse = AssignLecturerCourse::where('id', $enrollmentId)->where('user_id', Auth::user()->id)->first();
            }else{
               $assignLecturerCourse = AssignLecturerCourse::where('id', $enrollmentId)->first(); 
            }
            
                
            $courseEnrollments = (!empty($assignLecturerCourse))? CourseEnrollment::where('course_id', $assignLecturerCourse->course_id)->where('academic_year_id', $assignLecturerCourse->academic_year_id)->where('semester_id', $assignLecturerCourse->semester_id)->latest()->get() : '' ;

            // Course
            $course = (!empty($assignLecturerCourse)) ? Course::where('id', $assignLecturerCourse->course_id)->first() : '';

            // Year
            $year = (!empty($assignLecturerCourse)) ? AcademicYear::where('id', $assignLecturerCourse->academic_year_id)->first() : '';

            // Semester
            $semester = (!empty($assignLecturerCourse)) ? Semester::where('id', $assignLecturerCourse->semester_id)->first() : '';

            $records = [];
            if (!empty($courseEnrollments)) {
                foreach($courseEnrollments as $courseEnrollment) {
                    //Student
                    $studentDetail = (!empty($courseEnrollment->user_id)) ? StudentDetail::where('user_id', $courseEnrollment->user_id)->first() : '';

                    $records[] = [
                        'id' => $studentDetail->id ?? '',
                        'student_id' => $studentDetail->student_id ?? '',
                    ];
                }
            }
       
            return view('lecturer.add-student-grade', compact('enrollmentId','course','year','semester','records'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Submit Student Grade
     */
    public function addStudentGradeSubmit(Request $request, $enrollmentId) {
        if((Auth::user()->hasRole('lecturer') && (Auth::user()->status == 1)) || (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin'))) { 
            $validatedFields = Validator::make($request->all(), [
                'year' => ['required', 'max:30'],
                'semester' => ['required', 'max:30'],
                'course' => ['required', 'max:30'],
                'student' => ['required', 'max:30'],
                'point' => ['required', 'max:30'],
            ]);
            if ($validatedFields->fails()) {
                 \Session::flash('msgErr','Student grade was not submitted, try again.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }

            // Unique Grade validation
            $uniqueGrade = Grade::where('student_detail_id', $request->input('student'))
            ->where('academic_year_id',$request->input('year'))
            ->where('semester_id',$request->input('semester'))
            ->where('course_id',$request->input('course'))
            ->count();
            if($uniqueGrade > 0){
                \Session::flash('msgErr','Student grade has already been submitted, try another.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }

            $grading = '';
            $observation = '';
            if ($request->input('point') >= 90 ) {
                $grading = 'A';
                $observation = 'Pass';
            }else if ($request->input('point') >= 78 && $request->input('point') <= 89 ) {
                $grading = 'B';
                $observation = 'Pass';
            }else if ($request->input('point') >= 70 && $request->input('point') <= 77 ) {
                $grading = 'C';
                $observation = 'Pass';
            }else if ($request->input('point') <= 69.9 ) {
                $grading = 'F';
                $observation = 'Repeat';
            }

            $grade = new Grade;
            $grade->student_detail_id = $request->input('student');
            $grade->academic_year_id = $request->input('year');
            $grade->semester_id = $request->input('semester');
            $grade->course_id = $request->input('course');
            $grade->point = $request->input('point');
            $grade->grade = $grading;
            $grade->observation = $observation;
            $grade->user_id = Auth::user()->id;
            $grade->status = 2;
            $grade->save();

            \Session::flash('msg','Student grade submitted successfully.' );
       
            return redirect('enrolled-student-grades/'.$request->input('enrollmentId'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Display Edit Grade Form
     */
    public function editGradeForm($gradeId, $enrollmentId) {
        if((Auth::user()->hasRole('lecturer') && (Auth::user()->status == 1)) || (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin'))) { 
            

            // Edit Denied
            if(Auth::user()->hasRole('lecturer') && (Auth::user()->status == 1)) {
                // Access Denied
                $gradeAccess = Grade::where('user_id', '!=', Auth::user()->id)->where('id', $gradeId)->count();
                if ($gradeAccess > 0) {
                   \Session::flash('msgErr','Access Denied.' );
                   return redirect()->back();
                }
                $gradeEditDenied = Grade::where('user_id', Auth::user()->id)->where('id', $gradeId)->where('status', '!=', 2)->count();
                if ($gradeEditDenied > 0) {
                   \Session::flash('msgErr','You cannot edit this grade, contact the system admin.' );
                   return redirect()->back();
                }

                $grade = Grade::where('user_id', Auth::user()->id)->where('id', $gradeId)->first();
                if (empty($grade)) {
                   \Session::flash('msgWrn','No Grade Found.');
                   return redirect()->back();
                }
            }else {
                $grade = Grade::where('id', $gradeId)->first();
                if (empty($grade)) {
                   \Session::flash('msgWrn','No Grade Found.');
                   return redirect()->back();
                }
            }

            
       
            return view('lecturer.edit-student-grade', compact('grade','enrollmentId'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Update Student Grade
     */
    public function editGrade(Request $request, $gradeId) {
        if((Auth::user()->hasRole('lecturer') && (Auth::user()->status == 1)) || (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin'))) { 
            $grade_id = $request->input('gradeId');

            // Validate fields
            $validatedFields = Validator::make($request->all(), [
                'point' => ['required', 'max:30'],
            ]);
            if ($validatedFields->fails()) {
                 \Session::flash('msgErr','Student grade was not updated, try again.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }

            if(Auth::user()->hasRole('lecturer') && (Auth::user()->status == 1)) {
                // Access Denied
                $gradeAccess = Grade::where('user_id', '!=', Auth::user()->id)->where('id', $grade_id)->count();
                if ($gradeAccess > 0) {
                   \Session::flash('msgErr','Access Denied.' );
                   return redirect()->back();
                }

                // Edit Denied
                $gradeEditDenied = Grade::where('user_id', Auth::user()->id)->where('id', $grade_id)->where('status', '!=', 2)->count();
                if ($gradeEditDenied > 0) {
                   \Session::flash('msgErr','You cannot edit this grade, contact the system admin.' );
                   return redirect()->back();
                }

                $grade = Grade::where('user_id', Auth::user()->id)->where('id', $grade_id)->first();
                if (empty($grade)) {
                   \Session::flash('msgWrn','No Grade Found.');
                   return redirect()->back();
                }
            }else{
                $grade = Grade::where('id', $grade_id)->first();
                if (empty($grade)) {
                   \Session::flash('msgWrn','No Grade Found.');
                   return redirect()->back();
                }
            }

            $grading = '';
            $observation = '';
            if ($request->input('point') >= 90 ) {
                $grading = 'A';
                $observation = 'Pass';
            }else if ($request->input('point') >= 78 && $request->input('point') <= 89 ) {
                $grading = 'B';
                $observation = 'Pass';
            }else if ($request->input('point') >= 70 && $request->input('point') <= 77 ) {
                $grading = 'C';
                $observation = 'Pass';
            }else if ($request->input('point') <= 69.9 ) {
                $grading = 'F';
                $observation = 'Repeat';
            }

            $grade->point = $request->input('point');
            $grade->grade = $grading;
            $grade->observation = $observation;
            $grade->updated_by = Auth::user()->id;
            $grade->update();
       
            \Session::flash('msg','Student grade updated successfully.' );
            return redirect()->back();
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Display Add Grade Form
     */
    public function addGradeForm($studentDetailId, $enrollmentId) {
        if(Auth::user()->hasRole('lecturer') && (Auth::user()->status == 1)) {
            $assignLecturerCourse = AssignLecturerCourse::where('id', $enrollmentId)->where('user_id', Auth::user()->id)->first();

            $studentDetail = (!empty($studentDetailId)) ? StudentDetail::where('id', $studentDetailId)->first() : '';
            $student_id = (!empty($studentDetail)) ? $studentDetail->student_id : '';
                
            $courseEnrollment = (!empty($assignLecturerCourse))? CourseEnrollment::where('course_id', $assignLecturerCourse->course_id)->where('academic_year_id', $assignLecturerCourse->academic_year_id)->where('semester_id', $assignLecturerCourse->semester_id)->where('user_id', $studentDetail->user_id)->first() : '' ;

            // Check Student Enrollment
            if (empty($courseEnrollment)) {
                \Session::flash('msgErr','Unable to add grade for this student, contact the system admin.' );
                return redirect()->back();
            }
            
            // Course
            $course = (!empty($assignLecturerCourse)) ? Course::where('id', $assignLecturerCourse->course_id)->first() : '';

            // Year
            $year = (!empty($assignLecturerCourse)) ? AcademicYear::where('id', $assignLecturerCourse->academic_year_id)->first() : '';

            // Semester
            $semester = (!empty($assignLecturerCourse)) ? Semester::where('id', $assignLecturerCourse->semester_id)->first() : '';
       
            return view('lecturer.add-grade', compact('studentDetail','enrollmentId','course','year','semester'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Display Add Grade
     */
    public function addGrade(Request $request, $studentDetailId, $enrollmentId) {
        if(Auth::user()->hasRole('lecturer') && (Auth::user()->status == 1)) {
            $assignLecturerCourse = AssignLecturerCourse::where('id', $enrollmentId)->where('user_id', Auth::user()->id)->first();

            $studentDetail = (!empty($studentDetailId)) ? StudentDetail::where('id', $studentDetailId)->first() : '';
            $student_id = (!empty($studentDetail)) ? $studentDetail->student_id : '';
                
            $courseEnrollment = (!empty($assignLecturerCourse))? CourseEnrollment::where('course_id', $assignLecturerCourse->course_id)->where('academic_year_id', $assignLecturerCourse->academic_year_id)->where('semester_id', $assignLecturerCourse->semester_id)->where('user_id', $studentDetail->user_id)->first() : '' ;

            // Check Student Enrollment
            if (empty($courseEnrollment)) {
                \Session::flash('msgErr','Unable to add grade for this student, contact the system admin.' );
                return redirect()->back();
            }
            
            $validatedFields = Validator::make($request->all(), [
                'year' => ['required', 'max:30'],
                'semester' => ['required', 'max:30'],
                'course' => ['required', 'max:30'],
                'student' => ['required', 'max:30'],
                'point' => ['required', 'max:30'],
            ]);
            if ($validatedFields->fails()) {
                 \Session::flash('msgErr','Student grade was not submitted, try again.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }

            // Unique Grade validation
            $uniqueGrade = Grade::where('student_detail_id', $request->input('student'))
            ->where('academic_year_id',$request->input('year'))
            ->where('semester_id',$request->input('semester'))
            ->where('course_id',$request->input('course'))
            ->count();
            if($uniqueGrade > 0){
                \Session::flash('msgErr','Student grade has already been submitted, try another.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }

            $grading = '';
            $observation = '';
            if ($request->input('point') >= 90 ) {
                $grading = 'A';
                $observation = 'Pass';
            }else if ($request->input('point') >= 78 && $request->input('point') <= 89 ) {
                $grading = 'B';
                $observation = 'Pass';
            }else if ($request->input('point') >= 70 && $request->input('point') <= 77 ) {
                $grading = 'C';
                $observation = 'Pass';
            }else if ($request->input('point') <= 69.9 ) {
                $grading = 'F';
                $observation = 'Repeat';
            }

            $grade = new Grade;
            $grade->student_detail_id = $request->input('student');
            $grade->academic_year_id = $request->input('year');
            $grade->semester_id = $request->input('semester');
            $grade->course_id = $request->input('course');
            $grade->point = $request->input('point');
            $grade->grade = $grading;
            $grade->observation = $observation;
            $grade->user_id = Auth::user()->id;
            $grade->status = 2;
            $grade->save();

            \Session::flash('msg','Student grade submitted successfully.' );
       
            return redirect('enrolled-student-grades/'.$request->input('enrollmentId'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }
}
