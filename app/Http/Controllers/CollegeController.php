<?php

namespace App\Http\Controllers;

use App\Models\College;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CollegeController extends Controller
{
    /**
     * Method to display the College Form.
     */
    public function addCollegeForm(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            return view('college.add-college');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Method to add College.
     */
    public function addCollege(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
           $validatedFields = Validator::make($request->all(), [
                'college_name' => ['required', 'string', 'max:255', 'unique:colleges']
            ]);
            if ($validatedFields->fails()) {
                \Session::flash('msgErr','College was not created, try again.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }else{
                $college_code = $request->input('college_code');
                if (empty($college_code)) {
                    $code = (DB::table('colleges')->count()) +1;
                    $college_code = "CLLG" . str_pad($code, 4, "0", STR_PAD_LEFT);
                }

                $college = new College;
                $college->college_name = $request->input('college_name');
                $college->college_code = $college_code;
                $college->created_by = Auth::user()->id;
                $college->status = (empty($request->input('status'))) ? 0 : 1;
                $college->save();

                \Session::flash('msg','College was created successfully.' );
                return redirect()->back();
            }
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to display College list
    */
    public function viewCollegeList(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
        if(request()->ajax()){
            return datatables()->of(College::latest()->get())
                ->addColumn('username', function($data){  
                    $output = ''; 
                    if (!empty($data->created_by)) {
                        $result = User::where('id', $data->created_by)->first(); 
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
                    $viewUrl = url('class-students-list/'.$data->id);
                    $editUrl = url('edit-college/'.$data->id);
                    $deleteUrl = url('delete-college/'.$data->id);
                    $button = '<a class="" title="View Class Roster" href="'.$viewUrl.'">
                                <i class="fa fa-eye text-orange"></i>
                                </a>';
                    $button .= '&nbsp;';
                    $button .= '<a class="" title="Edit College" href="'.$editUrl.'">
                                    <i class="fas fa-edit text-dark-pastel-blue"></i>
                                </a>';
                    $button .= '&nbsp;';
                    if ($data->status == 1) {
                        $button .= '<a onclick="deactivateClass('.$data->id.')" title="Deactivate Class" href="javascript:void(0);">
                                <i class="fa fa-times-circle text-orange-red"></i>
                                </a>';
                        $button .= '&nbsp;';
                    }else{
                        $button .= '<a onclick="activateClass('.$data->id.')" class="activate_class" href="javascript:void(0);" title="Activate Class">
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
            return view('college.college-list');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to display edit College form
    */
    public function editCollegeForm($CollegeId){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            $college = College::findOrFail($CollegeId);
            if (!empty($college)) {
               return view('college.edit-college', compact('college'));
            }else{
                abort(404, 'College Not Found.');
            }
            
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to edit College
    */
    public function editCollege(Request $request){
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
                abort(404, 'College Not Found.');
            }
            
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, College $college)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(College $college)
    {
        //
    }
}
