<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassAdmin;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ClassesController extends Controller
{
    /**
     * Method to display the Class Form.
     */
    public function addClassForm(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            return view('class.add-class');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Method to add Class.
     */
    public function addClass(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
           $validatedFields = Validator::make($request->all(), [
                'class_name' => ['required', 'string', 'max:20', 'unique:classes']
            ]);
            if ($validatedFields->fails()) {
                \Session::flash('msgErr','Oops! Class was not created, try again.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }else{
                $class = new Classes;
                $class->class_name = $request->input('class_name');
                $class->status = (empty($request->input('status'))) ? 0 : 1;
                $class->save();

                $classAdmin = new ClassAdmin;
                $classAdmin->class_id = $class->id;
                $classAdmin->user_id = Auth::user()->id;
                $classAdmin->save();

                \Session::flash('msg','Success!  Class was created successfully.' );
                return redirect()->back();
            }
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to display classes list
    */
    public function viewClassList(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
        if(request()->ajax()){
            return datatables()->of(Classes::latest()->get())
                ->addColumn('username', function($data){  
                    $output = '';
                    $classAdmin = ClassAdmin::where('class_id', $data->id)->first();  
                    if (!empty($classAdmin)) {
                        $result = User::where('id', $classAdmin->user_id)->first(); 
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
                    $editUrl = url('edit-class/'.$data->id);
                    $deleteUrl = url('delete-user/'.$data->id);
                    $button = '<a class="" title="View Class Roster" href="'.$viewUrl.'">
                                <i class="fa fa-eye text-orange"></i>
                                </a>';
                    $button .= '&nbsp;';
                    $button .= '<a class="" title="Account Setting" href="'.$editUrl.'">
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
            return view('class.class-list');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to activate class
    */
    public function activateClass(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            $class = Classes::findOrFail($request->input('class_id'));
            if (!empty($class)) {
                $class->status = 1;
                $class->updated_by = Auth::user()->id;
                $class->update();
                return json_encode(['msg' => 'success']);
            }else{
                return json_encode(['msg' => 'error']);
            }
            
        }else{
            return json_encode(['msg' => 'unauthorized']);
        }
    }

    /***
     * Method to deactivate class
    */
    public function deactivateClass(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            $class = Classes::findOrFail($request->input('class_id'));
            if (!empty($class)) {
                $class->status = 0;
                $class->updated_by = Auth::user()->id;
                $class->update();
                return json_encode(['msg' => 'success']);
            }else{
                return json_encode(['msg' => 'error']);
            }
            
        }else{
            return json_encode(['msg' => 'unauthorized']);
        }
    }

    /***
     * Method to display edit class form
    */
    public function editClassForm($classId){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            $class = Classes::findOrFail($classId);
            if (!empty($class)) {
               return view('class.edit-class', compact('class'));
            }else{
                abort(404, 'Class Not Found.');
            }
            
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to edit class
    */
    public function editClass(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            $class = Classes::findOrFail($request->input('class_id'));
            if (!empty($class)) {
                $validatedFields = Validator::make($request->all(), [
                'class_name' => ['required', 'string', 'max:20']
                ]);

                // Unique name validation
                $checkName = Classes::where('class_name', $request->input('class_name'))->where('id','!=',$request->input('class_id'))->count();
                if($checkName > 0){
                    \Session::flash('msgErr','Oops! Class '.$request->input('class_name').' already exists, try another.' );
                    return redirect()->back()->withInput();
                }

                if ($validatedFields->fails()) {
                    \Session::flash('msgErr','Oops! Class was not updated, try again.' );
                    return redirect()->back()->withErrors($validatedFields->errors())->withInput();
                }else{
                    $class->class_name = $request->input('class_name');
                    $class->status = (empty($request->input('status'))) ? 0 : 1;
                    $class->updated_by = Auth::user()->id;
                    $class->update();

                    \Session::flash('msg','Success!  Class was updated successfully.' );
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
