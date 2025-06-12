<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentDetailAdmin;
use App\Models\AcademicYear;
use App\Models\StudentEnrollment;
use App\Models\Semester;
use App\Models\Period;
use App\Models\Subject;
use App\Models\AssignSubject;
use App\Models\AssignCourse;
use App\Models\StudentDetail;
use App\Models\AssignLecturerCourse;
use App\Models\College;
use App\Models\CourseEnrollment;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class GradeController extends Controller
{
    /*
    * Method to display the Grade Form.
    */
    public function displyGradeForm(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            // get active years
            $years =  AcademicYear::where('status',1)->get();
            // get active subjects
            $subjects =  Subject::where('status',1)->get();
            // get active classes
            $classes =  Classes::where('status',1)->get();
            return view('grade.grade-form', compact('years','subjects'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Method to get Semesters by year
     */
    public function getSemesters($yearId) {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            $semesters = Semester::where('status', 1)->where('academic_year_id', $yearId)->get();
            $result = [];
            if (!empty($semesters)) {
                foreach($semesters as $semester){
                    $result[] = [
                        'value' => $semester->id,
                        'name' => $semester->semester
                    ];
                }
            }
            \Log::info($result);
            return response()->json(['data' => $result]);
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Method to get Periods by semester
     */
    public function getPeriods($semesterId) {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            $periods = Period::where('status', 1)->where('semester_id', $semesterId)->get();
            $result = [];
            if (!empty($periods)) {
                foreach($periods as $period){
                    $result[] = [
                        'value' => $period->id,
                        'name' => $period->period
                    ];
                }
            }
            \Log::info($result);
            return response()->json(['data' => $result]);
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Method to get Classes by subject
     */
    public function getClasses($subjectId) {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            $assignedSubjects = AssignSubject::where('subject_id', $subjectId)->get();
            $result = [];
            if (!empty($assignedSubjects)) {
                foreach($assignedSubjects as $assignedSubject){
                    $result[] = [
                        'value' => $assignedSubject->Classes->id,
                        'name' => $assignedSubject->Classes->class_name
                    ];
                }
            }
            \Log::info($result);
            return response()->json(['data' => $result]);
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Method to get Courses by college
     */
    public function getCourses($collegeId) {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            $assignedCourses = AssignCourse::where('college_id', $collegeId)->get();
            $result = [];
            if (!empty($assignedCourses)) {
                foreach($assignedCourses as $assignedCourse){
                    $result[] = [
                        'value' => $assignedCourse->Course->id,
                        'name' => $assignedCourse->Course->course_name
                    ];
                }
            }
            \Log::info($result);
            return response()->json(['data' => $result]);
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Method to get Students by class & year IDs
     */
    public function getStudents($classId, $yearId) {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            $studentEnrollments = StudentEnrollment::where('class_id', $classId)->where('academic_year_id', $yearId)->get();
            $result = [];
            if (!empty($studentEnrollments)) {
                foreach($studentEnrollments as $studentEnrollment){
                    $result[] = [
                        'value' => $studentEnrollment->StudentDetail->id,
                        'name' => $studentEnrollment->StudentDetail->first_name.' '.$studentEnrollment->StudentDetail->last_name.' ( '.$studentEnrollment->StudentDetail->student_id.' )'
                    ];
                }
            }
            \Log::info($result);
            return response()->json(['data' => $result]);
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Method to add Student Grade.
     */
    public function addStudentGrade(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
           $validatedFields = Validator::make($request->all(), [
                'year' => ['required', 'max:30'],
                'semester' => ['required', 'max:30'],
                'period' => ['required', 'max:30'],
                'subject' => ['required', 'max:30'],
                'class' => ['required', 'max:30'],
                'student' => ['required', 'max:30'],
                'grade' => ['required', 'max:30'],
            ]);
            if ($validatedFields->fails()) {
                return json_encode(['msg' => 'error', 'errMsg'=> 'Grade was not submitted. All fields are required.']);
            }else{
                // Unique Grade validation
                $uniqueGrade = Grade::where('student_detail_id', $request->input('student'))
                ->where('academic_year_id',$request->input('year'))
                ->where('semester_id',$request->input('semester'))
                ->where('period_id',$request->input('period'))
                ->where('subject_id',$request->input('subject'))
                ->where('class_id',$request->input('class'))
                ->where('grade',$request->input('grade'))
                ->count();
                if($uniqueGrade > 0){
                    return json_encode(['msg' => 'error', 'errMsg'=> 'This Grade has already been submitted. try another.']);
                }

                $grade = new Grade;
                $grade->student_detail_id = $request->input('student');
                $grade->academic_year_id = $request->input('year');
                $grade->semester_id = $request->input('semester');
                $grade->period_id = $request->input('period');
                $grade->subject_id = $request->input('subject');
                $grade->class_id = $request->input('class');
                $grade->grade = $request->input('grade');
                $grade->user_id = Auth::user()->id;
                $grade->status = (empty($request->input('status'))) ? 0 : 1;
                $grade->save();

                return json_encode(['msg' => 'success']);
            }
        }else{
            return json_encode(['msg' => 'unauthorized']);
        }
    }

    /***
     * Method to display All Grades
    */
    public function allGrades(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            if(request()->ajax()){
                return datatables()->of(Grade::latest()->get())
                    ->addColumn('student_id', function($data){  
                        $output = '';
                        $studentDetail = StudentDetail::where('id', $data->student_detail_id)->first();  
                        $output = (!empty($studentDetail))? $studentDetail->student_id :'';
                        return  $output;
                    })
                    ->addColumn('subject', function($data){  
                        $output = '';
                        $subject = Subject::where('id', $data->subject_id)->first();  
                        $output = (!empty($subject))? $subject->subject :'';
                        return  $output;
                    })
                    ->addColumn('period', function($data){  
                        $output = '';
                        $period = Period::where('id', $data->period_id)->first();  
                        $output = (!empty($period))? $period->period :'';
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
                    ->addColumn('class', function($data){  
                        $output = '';
                        $class = Classes::where('id', $data->class_id)->first();  
                        $output = (!empty($class))? $class->class_name :'';
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
                    ->rawColumns(['subject','period','semester','year','class','action','status'])
                    ->make(true);
                }            
            return view('grade.all-grades');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Student Grade Sheet
     */
    public function myGradeSheet() {
        if(Auth::user()->hasRole('student')) {
            // 1. Get student detail from user
            $studentDetail = StudentDetail::where('user_id', Auth::id())->first();

            if (!$studentDetail) {
                return response()->json(['message' => 'Student not found'], 404);
            }

            // 2. Get latest student enrollment for academic year
            $studentEnrollment = StudentEnrollment::where('student_detail_id', $studentDetail->id)
                ->latest()
                ->first();

            if (!$studentEnrollment) {
                return response()->json(['message' => 'Enrollment not found'], 404);
            }

            // 3. Get course enrollments for that user (by semester and academic year)
            $courseEnrollments = CourseEnrollment::where('user_id', $studentDetail->user_id)
                ->where('academic_year_id', $studentEnrollment->academic_year_id)
                ->get()
                ->groupBy('semester_id');

            // 4. Get course_ids enrolled by semester
            $courseIdsGroupedBySemester = $courseEnrollments->map(function ($items) {
                return $items->pluck('course_id')->unique();
            });

            // 5. Get grades for those courses by semester
            $gradesBySemester = collect();

            foreach ($courseIdsGroupedBySemester as $semesterId => $courseIds) {
                $grades = Grade::where('student_detail_id', $studentDetail->id)
                    ->where('academic_year_id', $studentEnrollment->academic_year_id)
                    ->where('semester_id', $semesterId)
                    ->whereIn('course_id', $courseIds)
                    ->where('status', 1)
                    ->get();

                $gradesBySemester->put($semesterId, $grades);
            }

            // Optional: dd to inspect
            //dd($gradesBySemester);


            return view('grade.my-gradesheet', compact('grades', 'studentDetail', 'studentEnrollment','gradesBySemester'));
        }else{
            abort(403, 'Unauthorized access.');
        } 
    }

    /**
     * Display Grade Courses
     */
    public function gradeCourses() {
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
                    $courseEnrollment = CourseEnrollment::where('course_id', $data->course_id)
                    ->where('academic_year_id', $data->academic_year_id)  
                    ->where('semester_id', $data->semester_id)->count();  
                    return  $courseEnrollment;
                })
                ->addColumn('lecturer', function($data){  
                    $user = User::where('id', $data->user_id)->first(); 
                    $userDetail = (!empty($user)) ? UserDetail::where('user_id', $user->id)->first() : '' ;
                    $lecturer =  (!empty($userDetail)) ? $userDetail->first_name.' '.$userDetail->other_name.' '.$userDetail->last_name : '' ;
                    return  $lecturer;
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
                    $viewUrl = url('enrolled-students/'.$data->id);
                    $gradesUrl = url('student-grades/'.$data->id);
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
            return view('grade.grade-courses');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * View Enrolled Students
     */
    public function enrolledStudents($enrollmentId) {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            $assignLecturerCourse = AssignLecturerCourse::where('id', $enrollmentId)->first();
                
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
                ->addColumn('student_first_name', function($data){   
                    $studentDetail = (!empty($data->user_id)) ? StudentDetail::where('user_id', $data->user_id)->first() : '';
                    $student_first_name = (!empty($studentDetail)) ? $studentDetail->first_name : '';
                    return  $student_first_name;
                })
                ->addColumn('student_other_name', function($data){   
                    $studentDetail = (!empty($data->user_id)) ? StudentDetail::where('user_id', $data->user_id)->first() : '';
                    $student_other_name = (!empty($studentDetail)) ? $studentDetail->other_name : '';
                    return  $student_other_name;
                })
                ->addColumn('student_last_name', function($data){   
                    $studentDetail = (!empty($data->user_id)) ? StudentDetail::where('user_id', $data->user_id)->first() : '';
                    $student_last_name = (!empty($studentDetail)) ? $studentDetail->last_name : '';
                    return  $student_last_name;
                })
                ->rawColumns(['college','student_id','student_first_name','student_other_name','student_last_name'])
                ->make(true);
            }            
            return view('grade.enrolled-students', compact('enrollmentId','course','year','semester'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Print Enrolled Students
     */
    public function printEnrolledStudents($enrollmentId) {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            $assignLecturerCourse = AssignLecturerCourse::where('id', $enrollmentId)->first();
                
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
            $lecturer = (!empty($assignLecturerCourse))? UserDetail::where('user_id', $assignLecturerCourse->user_id)->first() : '';  
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
    public function studentGrades($enrollmentId) {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            $assignLecturerCourse = AssignLecturerCourse::where('id', $enrollmentId)->first();
                
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
                    if (!empty($grade)) {
                        $editUrl = url('edit-grade/'.$grade->id.'/'.$enrollmentId);
                        $button .= '<a href="'.$editUrl.'" target="_blank" title="Edit Student Grade">
                                <i class="fa fa-edit text-dark-pastel-blue"></i>
                                </a>';
                        $button .= '&nbsp;';

                        if ($grade->status == 2) {
                            $button .= '<a onclick="approveGrade('.$grade->id.')" title="Approve Student Grade" href="javascript:void(0);">
                                <i class="fa fa-check text-dark-pastel-green"></i>
                                </a>';
                            $button .= '&nbsp;';
                        }
                        
                    }
                    // else if(empty($grade)){
                    //     $student_id = (!empty($studentDetail)) ? $studentDetail->id : '';
                    //     $addGradeUrl = url('add-grade/'.$student_id.'/'.$enrollmentId);
                    //     $button .= '<a href="'.$addGradeUrl.'" target="_blank" title="Add Student Grade" >
                    //             <i class="fa fa-pencil-alt text-dark-pastel-green"></i>
                    //             </a>';
                    //     $button .= '&nbsp;';
                    // }
                    return $button;

                })
                ->rawColumns(['grade','point','observation','student_id','status','action'])
                ->make(true);
            }            
            return view('grade.student-grades', compact('enrollmentId','course','year','semester'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to approve Grade
    */
    public function approveGrade(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            $grade = Grade::findOrFail($request->input('grade_id'));
            if (!empty($grade)) {
                $grade->status = 1;
                $grade->updated_by = Auth::user()->id;
                $grade->update();
                return json_encode(['msg' => 'success']);
            }else{
                return json_encode(['msg' => 'error']);
            }
            
        }else{
            return json_encode(['msg' => 'unauthorized']);
        }
    }

    /***
     * Method to deactivate Subject
    */
    public function deleteGrade(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            $grade = Grade::findOrFail($request->input('grade_id'));
            if (!empty($grade)) {
                $grade->status = 0;
                $grade->updated_by = Auth::user()->id;
                $grade->update();
                return json_encode(['msg' => 'success']);
            }else{
                return json_encode(['msg' => 'error']);
            }
            
        }else{
            return json_encode(['msg' => 'unauthorized']);
        }
    }
}
