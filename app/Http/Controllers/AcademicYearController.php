<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\AcademicYearAdmin;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AcademicYearController extends Controller
{
    /**
     * Method to display the Academic Year Form.
     */
    public function addYearForm(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            return view('year.add-year');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Method to add Year.
     */
    public function addYear(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
           $validatedFields = Validator::make($request->all(), [
                'year' => ['required', 'string', 'max:20', 'unique:academic_years']
            ]);
            if ($validatedFields->fails()) {
                \Session::flash('msgErr','Oops! Academic Year was not created, try again.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }else{
                $year = new AcademicYear;
                $year->year = $request->input('year');
                $year->status = (empty($request->input('status'))) ? 0 : 1;
                $year->save();

                $yearAdmin = new AcademicYearAdmin;
                $yearAdmin->year_id = $year->id;
                $yearAdmin->user_id = Auth::user()->id;
                $yearAdmin->save();

                \Session::flash('msg','Success!  Academic Year was created successfully.' );
                return redirect()->back();
            }
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to display AcademicYear list
    */
    public function viewYearList(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
        if(request()->ajax()){
            return datatables()->of(AcademicYear::latest()->get())
                ->addColumn('username', function($data){  
                    $output = '';
                    $yearAdmin = AcademicYearAdmin::where('year_id', $data->id)->first();  
                    if (!empty($yearAdmin)) {
                        $result = User::where('id', $yearAdmin->user_id)->first(); 
                        $output = (!empty($result->user_name))? $result->user_name :'';
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
                    $viewUrl = url('semester-list/?search='.$data->year);
                    $editUrl = url('edit-year/'.$data->id);
                    $deleteUrl = url('delete-year/'.$data->id);
                    $button = '<a class="" title="View Academic Year Roster" href="'.$viewUrl.'">
                                <i class="fa fa-eye text-orange"></i>
                                </a>';
                    $button .= '&nbsp;';
                    $button .= '<a class="" title="Edit Academic Year" href="'.$editUrl.'">
                                    <i class="fas fa-edit text-dark-pastel-blue"></i>
                                </a>';
                    $button .= '&nbsp;';
                    if ($data->status == 1) {
                        $button .= '<a onclick="deactivateYear('.$data->id.')" title="Deactivate Academic Year" href="javascript:void(0);">
                                <i class="fa fa-times-circle text-orange-red"></i>
                                </a>';
                        $button .= '&nbsp;';
                    }else{
                        $button .= '<a onclick="activateYear('.$data->id.')" class="activate_class" href="javascript:void(0);" title="Activate Academic Year">
                                <i class="fa fa-check text-green"></i>
                                </a>';
                        $button .= '&nbsp;';
                    }
                    $button .= '<a class="" title="Delete" href="'.$deleteUrl.'">
                                <i class="fa fa-trash text-orange-red" aria-hidden="true"></i>
                                </a>';
                    return $button;

                })
                ->rawColumns(['username','created','updated','action','status'])
                ->make(true);
            }            
            return view('year.year-list');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to activate year
    */
    public function activateYear(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            $year = AcademicYear::findOrFail($request->input('year_id'));
            if (!empty($year)) {
                $year->status = 1;
                $year->updated_by = Auth::user()->id;
                $year->update();
                return json_encode(['msg' => 'success']);
            }else{
                return json_encode(['msg' => 'error']);
            }
            
        }else{
            return json_encode(['msg' => 'unauthorized']);
        }
    }

    /***
     * Method to deactivate year
    */
    public function deactivateYear(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            $year = AcademicYear::findOrFail($request->input('year_id'));
            if (!empty($year)) {
                $year->status = 0;
                $year->updated_by = Auth::user()->id;
                $year->update();
                return json_encode(['msg' => 'success']);
            }else{
                return json_encode(['msg' => 'error']);
            }
            
        }else{
            return json_encode(['msg' => 'unauthorized']);
        }
    }

    /***
     * Method to display edit year form
    */
    public function editYearForm($yearId){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            $year = AcademicYear::findOrFail($yearId);
            if (!empty($year)) {
               return view('year.edit-year', compact('year'));
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
    public function editYear(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            $year = AcademicYear::findOrFail($request->input('year_id'));
            if (!empty($year)) {
                $validatedFields = Validator::make($request->all(), [
                'year' => ['required', 'string', 'max:20']
                ]);

                // Unique name validation
                $checkName = AcademicYear::where('year', $request->input('year'))->where('id','!=',$request->input('year_id'))->count();
                if($checkName > 0){
                    \Session::flash('msgErr','Oops! Year '.$request->input('year').' already exists, try another.' );
                    return redirect()->back()->withInput();
                }

                if ($validatedFields->fails()) {
                    \Session::flash('msgErr','Oops! Year was not updated, try again.' );
                    return redirect()->back()->withErrors($validatedFields->errors())->withInput();
                }else{
                    $year->year = $request->input('year');
                    $year->status = (empty($request->input('status'))) ? 0 : 1;
                    $year->updated_by = Auth::user()->id;
                    $year->update();

                    \Session::flash('msg','Success!  Year was updated successfully.' );
                    return redirect()->back();
                }
            }else{
                abort(404, 'Year Not Found.');
            }
            
        }else{
            abort(403, 'Unauthorized access.');
        }
    }
}
