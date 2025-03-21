<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;
use App\Models\Semester;
use App\Models\PeriodAdmin;
use App\Models\AcademicYear;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PeriodController extends Controller
{
    /**
     * Method to display the Period Form.
     */
    public function addPeriodForm(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            // get active semesters
            $semesters =  Semester::where('status',1)->get();
            return view('period.add-period', compact('semesters'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Method to add Period.
     */
    public function addPeriod(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
           $validatedFields = Validator::make($request->all(), [
                'semester' => ['required', 'string', 'max:255'],
                'period' => ['required', 'string', 'max:25']
            ]);
            if ($validatedFields->fails()) {
                \Session::flash('msgErr','Oops! Period was not created, try again.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }else{
                // Unique Period validation
                $checkName = Period::where('semester_id', $request->input('semester'))->where('period',$request->input('period'))->count();
                if($checkName > 0){
                    $semester = Semester::where('id',$request->input('semester'))->first();
                    $semesterName = (!empty($semester->semester))?$semester->semester:'';
                    \Session::flash('msgErr','Oops! Period '.$request->input('period')." for Semester $semesterName already exists, try another.");
                    return redirect()->back()->withInput();
                }
                $period = new Period;
                $period->period = $request->input('period');
                $period->semester_id = $request->input('semester');
                $period->status = (empty($request->input('status'))) ? 0 : 1;
                $period->save();

                $periodAdmin = new PeriodAdmin;
                $periodAdmin->period_id = $period->id;
                $periodAdmin->user_id = Auth::user()->id;
                $periodAdmin->save();

                \Session::flash('msg','Success! Period was created successfully.' );
                return redirect()->back();
            }
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to display Period list
    */
    public function viewPeriodList(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
        if(request()->ajax()){
            return datatables()->of(Period::latest()->get())
                ->addColumn('username', function($data){  
                    $output = '';
                    $periodAdmin = PeriodAdmin::where('period_id', $data->id)->first();  
                    if (!empty($periodAdmin)) {
                        $result = User::where('id', $periodAdmin->user_id)->first(); 
                        $output = (!empty($result->user_name))? $result->user_name :'';
                    }
                    return  $output;
                })
                ->addColumn('semester', function($data){  
                    $output = '';
                    $semester = Semester::where('id', $data->semester_id)->first();  
                    if (!empty($semester)) {
                        $output = (!empty($semester->semester))? $semester->semester :'';
                    }
                    return  $output;
                })
                ->addColumn('year', function($data){  
                    $output = '';
                    $semester = Semester::where('id', $data->semester_id)->first();  
                    if (!empty($semester)) {
                        $year = AcademicYear::where('id', $semester->academic_year_id)->first();  
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
                    $viewUrl = url('period-grades-list/'.$data->id);
                    $editUrl = url('edit-period/'.$data->id);
                    $deleteUrl = url('delete-period/'.$data->id);
                    $button = '<a class="" title="View Period Roster" href="'.$viewUrl.'">
                                <i class="fa fa-eye text-orange"></i>
                                </a>';
                    $button .= '&nbsp;';
                    $button .= '<a class="" title="Edit Period" href="'.$editUrl.'">
                                    <i class="fas fa-edit text-dark-pastel-blue"></i>
                                </a>';
                    $button .= '&nbsp;';
                    if ($data->status == 1) {
                        $button .= '<a onclick="deactivatePeriod('.$data->id.')" title="Deactivate Period" href="javascript:void(0);">
                                <i class="fa fa-times-circle text-orange-red"></i>
                                </a>';
                        $button .= '&nbsp;';
                    }else{
                        $button .= '<a onclick="activatePeriod('.$data->id.')" class="activate_class" href="javascript:void(0);" title="Activate Period">
                                <i class="fa fa-check text-green"></i>
                                </a>';
                        $button .= '&nbsp;';
                    }
                    $button .= '<a class="" title="Delete Period" href="'.$deleteUrl.'">
                                <i class="fa fa-trash text-orange-red" aria-hidden="true"></i>
                                </a>';
                    return $button;

                })
                ->rawColumns(['username','semester','year','created','updated','action','status'])
                ->make(true);
            }            
            return view('period.period-list');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to activate period
    */
    public function activatePeriod(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            $period = Period::findOrFail($request->input('period_id'));
            if (!empty($period)) {
                $period->status = 1;
                $period->updated_by = Auth::user()->id;
                $period->update();
                return json_encode(['msg' => 'success']);
            }else{
                return json_encode(['msg' => 'error']);
            }
            
        }else{
            return json_encode(['msg' => 'unauthorized']);
        }
    }

    /***
     * Method to deactivate period
    */
    public function deactivatePeriod(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            $period = Period::findOrFail($request->input('period_id'));
            if (!empty($period)) {
                $period->status = 0;
                $period->updated_by = Auth::user()->id;
                $period->update();
                return json_encode(['msg' => 'success']);
            }else{
                return json_encode(['msg' => 'error']);
            }
            
        }else{
            return json_encode(['msg' => 'unauthorized']);
        }
    }

    /***
     * Method to display edit period form
    */
    public function editPeriodForm($periodId){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            $period = Period::findOrFail($periodId);
            // get active semesters
            $semesters =  Semester::where('status',1)->get();
            if (!empty($period)) {
               return view('period.edit-period', compact('semesters','period'));
            }else{
                abort(404, 'Year Not Found.');
            }
            
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to edit period
    */
    public function editPeriod(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            $period = Period::findOrFail($request->input('period_id'));
            if (!empty($period)) {
                $validatedFields = Validator::make($request->all(), [
                    'semester' => ['required', 'string', 'max:255'],
                    'period' => ['required', 'string', 'max:25']
                ]);

                // Unique Period validation
                $checkName = Period::where('period', $request->input('period'))->where('semester_id', $request->input('semester'))->where('id','!=',$request->input('period_id'))->count();
                if($checkName > 0){
                    $semester = Semester::where('id',$request->input('semester'))->first();
                    $semesterName = (!empty($semester->semester))?$semester->semester:'';
                    \Session::flash('msgErr','Oops! Period '.$request->input('period')." for Semester $semesterName already exists, try another.");
                    return redirect()->back()->withInput();
                }

                if ($validatedFields->fails()) {
                    \Session::flash('msgErr','Oops! Period was not updated, try again.' );
                    return redirect()->back()->withErrors($validatedFields->errors())->withInput();
                }else{
                    $period->period = $request->input('period');
                    $period->semester_id = $request->input('semester');
                    $period->status = (empty($request->input('status'))) ? 0 : 1;
                    $period->updated_by = Auth::user()->id;
                    $period->update();

                    \Session::flash('msg','Success!  Period was updated successfully.' );
                    return redirect()->back();
                }
            }else{
                abort(404, 'Period Not Found.');
            }
            
        }else{
            abort(403, 'Unauthorized access.');
        }
    }
}
