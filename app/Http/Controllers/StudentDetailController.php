<?php

namespace App\Http\Controllers;

use App\Models\StudentDetail;
use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\College;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentDetailAdmin;
use App\Models\AcademicYear;
use App\Models\CourseEnrollment;
use App\Models\StudentEnrollment;
use App\Models\StudentEnrollmentAdmin;
use App\Models\AssignLecturerCourse;
use App\Models\UserDetail;
use App\Models\Grade;
use App\Models\DropCourseLimit;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class StudentDetailController extends Controller
{
     /**
     * Method to display the Student Form.
     */
    public function addStudentForm(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            // get active College
            $colleges =  College::where('status',1)->get();
            $years =  AcademicYear::where('status',1)->get();
            return view('student.add-student', compact('colleges','years'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Method to add Student.
     */
    public function addStudent(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
           $validatedFields = Validator::make($request->all(), [
                'first_name' => ['required', 'string', 'max:30'],
                'other_name' => ['nullable', 'string', 'max:30'],
                'last_name' => ['nullable', 'string', 'max:30'],
                'gender' => ['required', 'string', 'max:30'],
                'dob' => ['nullable', 'string', 'max:30'],
                'pob' => ['nullable', 'string', 'max:30'],
                'nationality' => ['nullable', 'string', 'max:30'],
                'address' => ['nullable', 'string', 'max:30'],
                'phone' => ['nullable', 'string', 'max:30'],
                'mother_name' => ['nullable', 'string', 'max:30'],
                'mother_phone' => ['nullable', 'string', 'max:30'],
                'mother_email' => ['nullable', 'string', 'max:30'],
                'father_name' => ['nullable', 'string', 'max:30'],
                'father_phone' => ['nullable', 'string', 'max:30'],
                'father_email' => ['nullable', 'string', 'max:30'],
                'emergency_contact_name' => ['nullable', 'string', 'max:30'],
                'emergency_contact_phone' => ['nullable', 'string', 'max:30'],
                'emergency_contact_address' => ['nullable', 'string', 'max:30'],
                'emergency_contact_email' => ['nullable', 'string', 'max:30'],
                'emergency_contact_relationship' => ['nullable', 'string', 'max:30'],
            ]);
            if ($validatedFields->fails()) {
                \Session::flash('msgErr','Student was not registered, try again.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }else{
                // Unique Student validation
                // $uniqueStudent = StudentDetail::where('first_name', $request->input('first_name'))->where('last_name',$request->input('last_name'))->where('dob',$request->input('dob'))->count();
                // if($uniqueStudent > 0){
                //     \Session::flash('msgErr','Student '.$request->input('first_name').' '.$request->input('last_name')." already exists, try another.");
                //     return redirect()->back()->withInput();
                // }

                $code = (DB::table('student_details')->count()) +1;
                $student_id = str_pad($code, 4, "0", STR_PAD_LEFT);

                // Unique Student ID
                $uniqueStudentID = User::where('user_name',$student_id)->count();
                if($uniqueStudentID > 0){
                    \Session::flash('msgErr','Student ID already exists, try another.');
                    return redirect()->back()->withInput();
                }

                // Student Image Processing
                $image = $request->file('student_photo');
                if (empty($image)){
                    $imageName='avatar.jpg';
                }else{
                    $imageName = time().'.'.$image->extension(); 
                    $image->move(public_path('student_img'), $imageName);
                }

                // Student User Account Creation
                $user = new User;
                $user->user_name = $student_id;
                $user->password = Hash::make('rkps');
                $user->status = (empty($request->input('status'))) ? 0 : 1;
                $user->profile_image = $imageName;
                $user->save();

                // Assign Student Role
                $user->assignRole('student');

                $student = new StudentDetail;
                $student->student_id = $student_id;
                $student->user_id = $user->id;
                $student->first_name = $request->input('first_name');
                $student->other_name = $request->input('other_name');
                $student->last_name = $request->input('last_name');
                $student->gender = $request->input('gender');
                $student->dob = $request->input('dob');
                $student->pob = $request->input('pob');
                $student->nationality = $request->input('nationality');
                $student->address = $request->input('address');
                $student->phone = $request->input('phone');
                $student->address = $request->input('address');
                $student->mother_name = $request->input('mother_name');
                $student->mother_phone = $request->input('mother_phone');
                $student->mother_email = $request->input('mother_email');
                $student->father_name = $request->input('father_name');
                $student->father_name = $request->input('father_name');
                $student->father_phone = $request->input('father_phone');
                $student->father_email = $request->input('father_email');
                $student->emergency_contact_name = $request->input('emergency_contact_name');
                $student->emergency_contact_phone = $request->input('emergency_contact_phone');
                $student->emergency_contact_address = $request->input('emergency_contact_address');
                $student->emergency_contact_email = $request->input('emergency_contact_email');
                $student->emergency_contact_relationship = $request->input('emergency_contact_relationship');
                $student->status = (empty($request->input('status'))) ? 0 : 1;
                $student->save();

                // Student Creation Log
                $studentAdmin = new StudentDetailAdmin;
                $studentAdmin->student_detail_id = $student->id;
                $studentAdmin->user_id = Auth::user()->id;
                $studentAdmin->save();

                // Student Enrollment
                if (!empty($request->input('college'))) {
                    $studentEnrollment = new StudentEnrollment;
                    $studentEnrollment->student_detail_id = $student->id;
                    $studentEnrollment->college_id = $request->input('college');
                    $studentEnrollment->major_id = $request->input('major');
                    $studentEnrollment->academic_year_id = $request->input('year');
                    $studentEnrollment->updated_by = Auth::user()->id;
                    $studentEnrollment->save();

                    if (!empty($studentEnrollment)) {
                        $studentEnrollmentAdmin = new StudentEnrollmentAdmin;
                        $studentEnrollmentAdmin->student_enrollment_id = $studentEnrollment->id;
                        $studentEnrollmentAdmin->user_id = Auth::user()->id;
                        $studentEnrollmentAdmin->save();
                    }
                }

                \Session::flash('msg','Student was created successfully.' );
                return redirect()->back();
            }
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to display student list
    */
    public function viewStudentList(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            if(request()->ajax()){
                return datatables()->of(StudentDetail::latest()->get())
                    ->addColumn('class', function($data){  
                        $output = '';
                        $studentEnrollment = StudentEnrollment::where('student_detail_id', $data->id)->first();  
                        if (!empty($studentEnrollment)) {
                            $class = (!empty($studentEnrollment->class_id))? Classes::where('id', $studentEnrollment->class_id)->first() :'';
                            $output = (!empty($class->class_name))? $class->class_name :'';
                        }
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
                        $viewUrl = url('student-profile/'.$data->id);
                        $editUrl = url('edit-student/'.$data->id);
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
                        $button .= '<a class="" title="Change Password" href="'.$changePassword.'">
                                        <i class="fas fa-key text-secondary"></i>
                                    </a>';
                        $button .= '&nbsp;';
                        if ($data->status == 1) {
                            $button .= '<a onclick="deactivateStudent('.$data->id.')" title="Deactivate Student" href="javascript:void(0);">
                                    <i class="fa fa-times-circle text-orange-red"></i>
                                    </a>';
                            $button .= '&nbsp;';
                        }else{
                            $button .= '<a onclick="activateStudent('.$data->id.')" class="activate_user" href="javascript:void(0);" title="Activate User">
                                    <i class="fa fa-check text-green"></i>
                                    </a>';
                            $button .= '&nbsp;';
                        }
                        $button .= '<a class="" title="Delete" href="'.$deleteUrl.'">
                                    <i class="fa fa-trash text-orange-red" aria-hidden="true"></i>
                                    </a>';
                        return $button;

                    })
                    ->rawColumns(['class','action','status'])
                    ->make(true);
                }            
            return view('student.student-list');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     *  Method to view a Student Profile
     */
    public function studentProfile($studentID) {
        if((Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('student')) && Auth::user()->status == 1) {  
            if (Auth::user()->hasRole('student')) {
                $studentDetail = StudentDetail::where('user_id', Auth::user()->id)->first();
                $studentID = (!empty($studentDetail->id))? $studentDetail->id :'';
            }
            $studentDetail = StudentDetail::where('id', $studentID)->first();
            if (empty($studentDetail->User)) {
                \Session::flash('msgErr','Student was not found.' );
                return redirect()->back();
            }

            $class = '';
            $studentEnrollment = StudentEnrollment::where('student_detail_id', $studentID)->first();  
            if (!empty($studentEnrollment)) {
                $class = (!empty($studentEnrollment->class_id))? Classes::where('id', $studentEnrollment->class_id)->first() :'';
                $class = (!empty($class->class_name))? $class->class_name :'';
            }
            return view('student.student-profile', compact('studentDetail','class'));
        }else{
            abort(403, 'Unauthorized access.');
        } 
    }

    /**
     * Student: View Courses
     */
    public function myStudentCourses() {
        if(Auth::user()->hasRole('student') && (Auth::user()->status == 1)) { 
            if(request()->ajax()){
            return datatables()->of(CourseEnrollment::where('user_id', Auth::user()->id)->latest()->get())
                ->addColumn('course', function($data){  
                    $output = (!empty($data->course_id)) ? $data->Course->course_name.' ('.$data->Course->course_code.')' : '';
                    return  $output;
                })
                ->addColumn('year', function($data){  
                    $year = AcademicYear::where('id', $data->academic_year_id)->first();  
                    $output = (!empty($year)) ? $year->year : '';
                    return  $output;
                })
                ->addColumn('semester', function($data){  
                    $output = (!empty($data->semester_id)) ? $data->Semester->semester : '';
                    return  $output;
                })
                ->addColumn('lecturer', function($data){  
                    $assignLecturerCourse = AssignLecturerCourse::where('course_id', $data->course_id)->first(); 
                    $userDetail = (!empty($assignLecturerCourse)) ?  UserDetail::where('user_id', $assignLecturerCourse->user_id)->first() : '';
                    $lecturer = (!empty($userDetail)) ? $userDetail->first_name.' '.$userDetail->other_name.' '.$userDetail->last_name : '';
                    return  $lecturer;
                })
                ->addColumn('created', function($data){  
                    $strtotime =  strtotime($data->created_at);
                    $date = date('F j, Y H:i', $strtotime);
                    return  $date;
                })
                ->addColumn('status', function($data){ 
                    $studentDetail = StudentDetail::where('user_id', Auth::user()->id)->first();
                    $grade = Grade::where('academic_year_id', $data->academic_year_id)->where('semester_id', $data->semester_id)->where('course_id', $data->course_id)->where('semester_id', $data->semester_id)->where('student_detail_id', $studentDetail->id)->count();            
                    if ($grade > 0) {
                        $class = 'btn-success';
                        $status = 'Completed'; 
                    }else{
                        $status='Ongoing';
                        $class = 'btn-warning';
                    }
                    $status_btn = '<a href="#" class="'.$class.' white-text delete btn">'.$status.'</a>'; 
                    return  $status_btn;
                })
                ->addColumn('action', function($data){
                    $studentDetail = StudentDetail::where('user_id', Auth::user()->id)->first();
                    $grade = Grade::where('academic_year_id', $data->academic_year_id)->where('semester_id', $data->semester_id)->where('course_id', $data->course_id)->where('semester_id', $data->semester_id)->where('student_detail_id', $studentDetail->id)->count();            
                    if ($grade == 0) {
                        $dropCourseUrl = url('enrolled-students-list/'.$data->id);
                        $button = '<a class="disabled" href="#" title="Drop Course" onclick="dropCourse('.$data->id.')">
                                    <i class="fa fa-trash text-red"></i>
                                    </a>';
                        $button .= '&nbsp;';
                        return $button;
                    }
                })
                ->rawColumns(['course','year','semester','lecturer','created','updated','action','status'])
                ->make(true);
            }            
            return view('student.my-student-courses');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentDetail $studentDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentDetail $studentDetail)
    {
        //
    }

    /***
     * Method to activate Student
    */
    public function activateStudent(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            $studentDetail = StudentDetail::findOrFail($request->input('studentDetailId'));
            $user = (!empty($studentDetail))? User::findOrFail($studentDetail->user_id) : '';
            if (!empty($studentDetail)) {
                $studentDetail->status = 1;
                $studentDetail->update();
            }

            if (!empty($user)) {
                $user->assignRole('student');
                $user->status = 1;
                $user->update();
                return json_encode(['msg' => 'success']);
            }else{
                return json_encode(['msg' => 'error']);
            }
            
        }else{
            return json_encode(['msg' => 'unauthorized']);
        }
    }

    /***
     * Method to deactivate Student
    */
    public function deactivateStudent(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            $studentDetail = StudentDetail::findOrFail($request->input('studentDetailId'));
            $user = (!empty($studentDetail))? User::findOrFail($studentDetail->user_id) : '';
            if (!empty($studentDetail)) {
                $studentDetail->status = 0;
                $studentDetail->update();
            }

            if (!empty($user)) {
                $user->roles()->detach();
                $user->status = 0;
                $user->update();
                return json_encode(['msg' => 'success']);
            }else{
                return json_encode(['msg' => 'error']);
            }
            
        }else{
            return json_encode(['msg' => 'unauthorized']);
        }
    }

    /***
     * Method to drop course
    */
    public function dropCourse(Request $request){
        if(Auth::user()->hasRole('student') && (Auth::user()->status == 1)) { 
            $courseEnrollment = CourseEnrollment::where('user_id', Auth::user()->id)
                ->where('id', $request->input('enrollment_id'))
                ->first();

            if (empty($courseEnrollment)) {
                return json_encode(['msg' => 'error']);
            }

            $enrollmentDate = $courseEnrollment->created_at;
            $dropCourseLimit = DropCourseLimit::first();
            $limit = $dropCourseLimit->limit;

            $daysSinceEnrollment = Carbon::now()->diffInDays($enrollmentDate);

            if ($daysSinceEnrollment > $limit) {
                return json_encode(['msg' => 'limit']);
            } else {
                $courseEnrollment->delete();
                return json_encode(['msg' => 'success']);
            }
            
        }else{
            return json_encode(['msg' => 'unauthorized']);
        }
    }
}
