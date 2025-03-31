<?php

namespace App\Http\Controllers;

use App\Models\StudentDetail;
use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentDetailAdmin;
use App\Models\AcademicYear;
use App\Models\StudentEnrollment;
use App\Models\StudentEnrollmentAdmin;
use Illuminate\Support\Facades\Hash;

class StudentDetailController extends Controller
{
     /**
     * Method to display the Student Form.
     */
    public function addStudentForm(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            // get active classes
            $classes =  Classes::where('status',1)->get();
            $years =  AcademicYear::where('status',1)->get();
            return view('student.add-student', compact('classes','years'));
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
                'other_name' => ['string', 'max:30'],
                'last_name' => ['required', 'string', 'max:30'],
                'gender' => ['required', 'string', 'max:30'],
                'dob' => ['required', 'string', 'max:30'],
                'pob' => ['string', 'max:30'],
                'nationality' => ['string', 'max:30'],
                'address' => ['string', 'max:30'],
                'phone' => ['string', 'max:30'],
                'mother_name' => ['string', 'max:30'],
                'mother_phone' => ['string', 'max:30'],
                'mother_email' => ['string', 'max:30'],
                'father_name' => ['string', 'max:30'],
                'father_phone' => ['string', 'max:30'],
                'father_email' => ['string', 'max:30'],
                'emergency_contact_name' => ['string', 'max:30'],
                'emergency_contact_phone' => ['string', 'max:30'],
                'emergency_contact_address' => ['string', 'max:30'],
                'emergency_contact_email' => ['string', 'max:30'],
                'emergency_contact_relationship' => ['string', 'max:30'],
            ]);
            if ($validatedFields->fails()) {
                \Session::flash('msgErr','Student was not registered, try again.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }else{
                // Unique Student validation
                $uniqueStudent = StudentDetail::where('first_name', $request->input('first_name'))->where('last_name',$request->input('last_name'))->where('dob',$request->input('dob'))->count();
                if($uniqueStudent > 0){
                    \Session::flash('msgErr','Student '.$request->input('first_name').' '.$request->input('last_name')." already exists, try another.");
                    return redirect()->back()->withInput();
                }

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
                $user->password = Hash::make($request->input('rkps'));
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
                if (!empty($request->input('class'))) {
                    $studentEnrollment = new StudentEnrollment;
                    $studentEnrollment->student_detail_id = $student->id;
                    $studentEnrollment->class_id = $request->input('class');
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentDetail $studentDetail)
    {
        //
    }
}
