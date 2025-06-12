<?php

namespace App\Http\Controllers;

use App\Models\Major;
use Illuminate\Http\Request;
use App\Models\College;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class MajorController extends Controller
{
    /**
     * Method to display the Major Form.
     */
    public function addMajorForm(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            // get active colleges
            $colleges =  College::where('status',1)->get();
            return view('major.add-major', compact('colleges'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Method to add Major.
     */
    public function addMajor(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
           $validatedFields = Validator::make($request->all(), [
                'major' => ['required', 'string', 'max:255', 'unique:majors'],
                'college' => ['required', 'string', 'max:255'],
            ]);
            if ($validatedFields->fails()) {
                \Session::flash('msgErr','Major was not created, try again.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }else{
                $major = new Major;
                $major->major = $request->input('major');
                $major->college_id = $request->input('college');
                $major->created_by = Auth::user()->id;
                $major->status = (empty($request->input('status'))) ? 0 : 1;
                $major->save();

                \Session::flash('msg','Major was created successfully.' );
                return redirect()->back();
            }
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to display Major list
    */
    public function viewMajorList(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
        if(request()->ajax()){
            return datatables()->of(Major::latest()->get())
                ->addColumn('username', function($data){  
                    $output = ''; 
                    if (!empty($data->created_by)) {
                        $result = User::where('id', $data->created_by)->first(); 
                        $output = (!empty($result->user_name))? $result->user_name :'';
                    }
                    return  $output;
                })
                ->addColumn('college', function($data){  
                    $output = ''; 
                    if (!empty($data->college_id)) {
                        $result = College::where('id', $data->college_id)->first(); 
                        $output = (!empty($result->college_name))? $result->college_name :'';
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
                    $editUrl = url('edit-major/'.$data->id);
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
                ->rawColumns(['college','username','created','updated','action','status'])
                ->make(true);
            }            
            return view('major.major-list');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Method to get Major by College
     */
    public function getMajors($collegeId) {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            $majors = Major::where('status', 1)->where('college_id', $collegeId)->get();
            $result = [];
            if (!empty($majors)) {
                foreach($majors as $major){
                    $result[] = [
                        'value' => $major->id,
                        'name' => $major->major
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Major $major)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Major $major)
    {
        //
    }
}
