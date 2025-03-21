<?php

namespace App\Http\Controllers;

use App\Models\StudentDetail;
use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class StudentDetailController extends Controller
{
     /**
     * Method to display the Student Form.
     */
    public function addStudentForm(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            // get active classes
            $classes =  Classes::where('status',1)->get();
            return view('student.add-student', compact('classes'));
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
                \Session::flash('msgErr','Oops! Student was not registered, try again.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }else{
                // Unique Student validation
                $uniqueStudent = StudentDetail::where('first_name', $request->input('first_name'))->where('last_name',$request->input('last_name'))->where('dob',$request->input('dob'))->count();
                if($uniqueStudent > 0){
                    $year = AcademicYear::where('id',$request->input('year'))->first();
                    $yearName = (!empty($year->year))?$year->year:'';
                    \Session::flash('msgErr','Oops! Student '.$request->input('first_name').' '.$request->input('last_name')." already exists, try another.");
                    return redirect()->back()->withInput();
                }
                $semester = new Semester;
                $semester->semester = $request->input('semester');
                $semester->academic_year_id = $request->input('year');
                $semester->status = (empty($request->input('status'))) ? 0 : 1;
                $semester->save();

                $semesterAdmin = new SemesterAdmin;
                $semesterAdmin->semester_id = $semester->id;
                $semesterAdmin->user_id = Auth::user()->id;
                $semesterAdmin->save();

                \Session::flash('msg','Success!  Academic Semester was created successfully.' );
                return redirect()->back();
            }
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentDetail $studentDetail)
    {
        //
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
