<?php

namespace App\Http\Controllers;

use App\Models\DropCourseLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\CourseCreditCost;
use App\Models\User;
use App\Models\Course;

class SettingsController extends Controller
{
    /**
     * Display Course Drop Limit Form.
     */
    public function courseDropLimitForm() {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            $courseDropLimit = DropCourseLimit::first();
            return view('settings.course-drop-limit', compact('courseDropLimit'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Submit Course Drop Limit.
     */
    public function courseDropLimit(Request $request) {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            $courseDropLimitId = $request->input('courseDropLimitId');
            $limit = $request->input('limit');

            if (!empty($courseDropLimitId)) {
                $courseDropLimit = DropCourseLimit::where('id', $courseDropLimitId)->first();
            }else{
                $courseDropLimit = new DropCourseLimit;
            }
            
            $courseDropLimit->limit = $limit ?? 0;
            $courseDropLimit->user_id = Auth::user()->id;

            if (!empty($courseDropLimitId)) {
                $courseDropLimit->update();
            }else{
                $courseDropLimit->save();
            }
            \Session::flash('msg','Course Drop limit saved successfully.' );
            return redirect()->back();
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Display Course Credit Cost Form.
     */
    public function courseCreditCostForm() {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            return view('settings.course-credit-cost');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Submit Course Credit Cost.
     */
    public function courseCreditCost(Request $request) {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            $validatedFields = Validator::make($request->all(), [
                'credit_hour' => ['required','unique:course_credit_costs'],
                'cost' => ['required'],
                'currency' => ['required'],
            ]);
            if ($validatedFields->fails()) {
                \Session::flash('msgErr','Credit/Hour Cost was not created, try again.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }

            $courseCreditCost = new CourseCreditCost;
            $courseCreditCost->credit_hour = $request->input('credit_hour');
            $courseCreditCost->cost = $request->input('cost');
            $courseCreditCost->currency = $request->input('currency');
            $courseCreditCost->user_id = Auth::user()->id;
            $courseCreditCost->save();

            \Session::flash('msg','Credit/Hour Cost was created successfully.' );
            return redirect()->back();
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DropCourseLimit $dropCourseLimit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DropCourseLimit $dropCourseLimit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function creditCostList() {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
        if(request()->ajax()){
            return datatables()->of(CourseCreditCost::latest()->get())
                ->addColumn('username', function($data){  
                    $output = '';
                    $result = User::where('id', $data->user_id)->first(); 
                    if (!empty($result)) {
                        $output = (!empty($result->user_name))? $result->user_name :'';
                    }
                    return  $output;
                })
                ->addColumn('course', function($data){  
                    $output = '';
                    $courses = Course::where('credit_hour', $data->credit_hour)->get();  
                    if (!empty($courses)) {
                        foreach ($courses as $course) {
                            $output = $output.'<a href="'.url('college-list?search='.$course->course_name).'" class="btn-primary text-white text-md btn">'.$course->course_name.' ('.$course->course_name.')</a>&nbsp;'; 
                        }
                    }
                    return  $output;
                })
                ->addColumn('created', function($data){  
                    $strtotime =  strtotime($data->created_at);
                    $date = date('F j, Y H:i', $strtotime);
                    return  $date;
                })
                ->addColumn('updated', function($data){  
                    $strtotime =  strtotime($data->updated_at);
                    $date = date('F j, Y H:i', $strtotime);
                    return  $date;
                })
                ->addColumn('action', function($data){
                    $editUrl = url('edit-credit-cost/'.$data->id);
                    $button = '<a class="" title="Edit Credit Hour Cost" href="'.$editUrl.'">
                                    <i class="fas fa-edit text-dark-pastel-blue"></i>
                                </a>';
                    $button .= '&nbsp;';
                    $button .= '<a nclick="activateSemester('.$data->id.')" title="Delete Credit Hour Cost" href="#">
                                <i class="fa fa-trash text-orange-red" aria-hidden="true"></i>
                                </a>';
                    return $button;

                })
                ->rawColumns(['username','course','created','updated','action'])
                ->make(true);
            }            
            return view('settings.credit-cost-list');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Display Course Credit Cost Edit Form.
     */
    public function creditCostEditForm($creditId) {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            $credit = CourseCreditCost::where('id', $creditId)->first();
            if (empty($credit)) {
                \Session::flash('msgErr','Credit/Hour Cost not found.' );
                return redirect()->back();
            }
            return view('settings.edit-credit-cost', compact('credit'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Submit Course Credit Cost Edit Form.
     */
    public function creditCostEdit(Request $request) {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            $college = College::findOrFail($request->input('college_id'));
            if (!empty($college)) {
                $validatedFields = Validator::make($request->all(), [
                'college_name' => ['required', 'string', 'max:255'],
                'college_code' => ['required', 'string', 'max:30']
                ]);

                // Unique College name validation
                $checkName = College::where('college_name', $request->input('college_name'))->where('id','!=',$request->input('college_id'))->count();
                if($checkName > 0){
                    \Session::flash('msgErr','College '.$request->input('college_name').' already exists, try another.' );
                    return redirect()->back()->withInput();
                }

                // Unique College code validation
                $checkCode = College::where('college_code', $request->input('college_code'))->where('id','!=',$request->input('college_id'))->count();
                if($checkCode > 0){
                    \Session::flash('msgErr','College '.$request->input('college_code').' already exists, try another.' );
                    return redirect()->back()->withInput();
                }

                if ($validatedFields->fails()) {
                    \Session::flash('msgErr','College was not updated, try again.' );
                    return redirect()->back()->withErrors($validatedFields->errors())->withInput();
                }else{
                    $college->college_name = $request->input('college_name');
                    $college->college_code = $request->input('college_code');
                    $college->updated_by = Auth::user()->id;
                    $college->status = (empty($request->input('status'))) ? 0 : 1;
                    $college->update();

                    \Session::flash('msg','College was updated successfully.' );
                    return redirect()->back();
                }
            }else{
                abort(404, 'Class Not Found.');
            }
        }else{
            abort(403, 'Unauthorized access.');
        }
    }
}
