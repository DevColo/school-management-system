<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\SemesterAdmin;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SemesterController extends Controller
{
    /**
     * Method to display the Semester Form.
     */
    public function addSemesterForm(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            // get active academic years
            $years =  AcademicYear::where('status',1)->get();
            return view('semester.add-semester', compact('years'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

   /**
     * Method to add Semester.
     */
    public function addSemester(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
           $validatedFields = Validator::make($request->all(), [
                'year' => ['required', 'string', 'max:255'],
                'semester' => ['required', 'string', 'max:25']
            ]);
            if ($validatedFields->fails()) {
                \Session::flash('msgErr','Oops! Semester was not created, try again.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }else{
                // Unique Semester validation
                $checkName = Semester::where('academic_year_id', $request->input('year'))->where('semester',$request->input('semester'))->count();
                if($checkName > 0){
                    $year = AcademicYear::where('id',$request->input('year'))->first();
                    $yearName = (!empty($year->year))?$year->year:'';
                    \Session::flash('msgErr','Oops! Semester '.$request->input('semester')." for Academic Year $yearName already exists, try another.");
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

    /***
     * Method to display Semester list
    */
    public function viewSemesterList(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
        if(request()->ajax()){
            return datatables()->of(Semester::latest()->get())
                ->addColumn('username', function($data){  
                    $output = '';
                    $semesterAdmin = SemesterAdmin::where('semester_id', $data->id)->first();  
                    if (!empty($semesterAdmin)) {
                        $result = User::where('id', $semesterAdmin->user_id)->first(); 
                        $output = (!empty($result->user_name))? $result->user_name :'';
                    }
                    return  $output;
                })
                ->addColumn('year', function($data){  
                    $output = '';
                    $year = AcademicYear::where('id', $data->academic_year_id)->first();  
                    if (!empty($year)) {
                        $output = (!empty($year->year))? $year->year :'';
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
                    $viewUrl = url('semester-periods-list/'.$data->id);
                    $editUrl = url('edit-semester/'.$data->id);
                    $deleteUrl = url('delete-semester/'.$data->id);
                    $button = '<a class="" title="View Semester Roster" href="'.$viewUrl.'">
                                <i class="fa fa-eye text-orange"></i>
                                </a>';
                    $button .= '&nbsp;';
                    $button .= '<a class="" title="Edit Semester" href="'.$editUrl.'">
                                    <i class="fas fa-edit text-dark-pastel-blue"></i>
                                </a>';
                    $button .= '&nbsp;';
                    if ($data->status == 1) {
                        $button .= '<a onclick="deactivateSemester('.$data->id.')" title="Deactivate Semester" href="javascript:void(0);">
                                <i class="fa fa-times-circle text-orange-red"></i>
                                </a>';
                        $button .= '&nbsp;';
                    }else{
                        $button .= '<a onclick="activateSemester('.$data->id.')" class="activate_class" href="javascript:void(0);" title="Activate Semester">
                                <i class="fa fa-check text-green"></i>
                                </a>';
                        $button .= '&nbsp;';
                    }
                    $button .= '<a class="" title="Delete Semester" href="'.$deleteUrl.'">
                                <i class="fa fa-trash text-orange-red" aria-hidden="true"></i>
                                </a>';
                    return $button;

                })
                ->rawColumns(['username','year','created','updated','action','status'])
                ->make(true);
            }            
            return view('semester.semester-list');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to activate semester
    */
    public function activateSemester(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            $semester = Semester::findOrFail($request->input('semester_id'));
            if (!empty($semester)) {
                $semester->status = 1;
                $semester->updated_by = Auth::user()->id;
                $semester->update();
                return json_encode(['msg' => 'success']);
            }else{
                return json_encode(['msg' => 'error']);
            }
            
        }else{
            return json_encode(['msg' => 'unauthorized']);
        }
    }

    /***
     * Method to deactivate semester
    */
    public function deactivateSemester(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            $semester = Semester::findOrFail($request->input('semester_id'));
            if (!empty($semester)) {
                $semester->status = 0;
                $semester->updated_by = Auth::user()->id;
                $semester->update();
                return json_encode(['msg' => 'success']);
            }else{
                return json_encode(['msg' => 'error']);
            }
            
        }else{
            return json_encode(['msg' => 'unauthorized']);
        }
    }

    /***
     * Method to display edit semester form
    */
    public function editSemesterForm($semesterId){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            $semester = Semester::findOrFail($semesterId);
            // get active academic years
            $years =  AcademicYear::where('status',1)->get();
            if (!empty($semester)) {
               return view('semester.edit-semester', compact('years','semester'));
            }else{
                abort(404, 'Year Not Found.');
            }
            
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to edit year
    */
    public function editSemester(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            $semester = Semester::findOrFail($request->input('semester_id'));
            if (!empty($semester)) {
                $validatedFields = Validator::make($request->all(), [
                    'year' => ['required', 'string', 'max:255'],
                    'semester' => ['required', 'string', 'max:25']
                ]);

                // Unique semester validation
                $checkName = Semester::where('semester', $request->input('semester'))->where('academic_year_id', $request->input('year'))->where('id','!=',$request->input('semester_id'))->count();
                if($checkName > 0){
                    \Session::flash('msgErr','Oops! Semester'.$request->input('semester').' for Academic Year '.$request->input('year').' already exists, try another.' );
                    return redirect()->back()->withInput();
                }

                if ($validatedFields->fails()) {
                    \Session::flash('msgErr','Oops! Semester was not updated, try again.' );
                    return redirect()->back()->withErrors($validatedFields->errors())->withInput();
                }else{
                    $semester->semester = $request->input('semester');
                    $semester->academic_year_id = $request->input('year');
                    $semester->status = (empty($request->input('status'))) ? 0 : 1;
                    $semester->updated_by = Auth::user()->id;
                    $semester->update();

                    \Session::flash('msg','Success!  Semester was updated successfully.' );
                    return redirect()->back();
                }
            }else{
                abort(404, 'Semester Not Found.');
            }
            
        }else{
            abort(403, 'Unauthorized access.');
        }
    }
}
