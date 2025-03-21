<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\SubjectAdmin;
use App\Models\Classes;
use App\Models\AssignSubject;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    /**
     * Method to display the Subject Form.
     */
    public function addSubjectForm(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) { 
            // get active classes
            $classes =  Classes::where('status',1)->get();
            return view('subject.add-subject', compact('classes'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Method to add Subject.
     */
    public function addSubject(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            //dd($request->all());die;
           $validatedFields = Validator::make($request->all(), [
                'class' => ['required','max:255'],
                'subject' => ['required', 'string', 'max:25', 'unique:subjects']
            ]);
            if ($validatedFields->fails()) {
                \Session::flash('msgErr','Oops! Subject was not created, try again.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }else{
                $subject = new Subject;
                $subject->subject = $request->input('subject');
                $subject->status = (empty($request->input('status'))) ? 0 : 1;
                $subject->save();

                $subjectAdmin = new SubjectAdmin;
                $subjectAdmin->subject_id = $subject->id;
                $subjectAdmin->user_id = Auth::user()->id;
                $subjectAdmin->save();

                if (!empty($request->input('class'))) {
                    foreach($request->input('class') as $class){
                        $assignSubject = new AssignSubject;
                        $assignSubject->subject_id = $subject->id;
                        $assignSubject->class_id = $class;
                        $assignSubject->assigned_by = Auth::user()->id;
                        $assignSubject->save();
                    }
                }

                \Session::flash('msg','Success! Subject was created successfully.' );
                return redirect()->back();
            }
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to display Subject list
    */
    public function viewSubjectList(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
        if(request()->ajax()){
            return datatables()->of(Subject::latest()->get())
                ->addColumn('username', function($data){  
                    $output = '';
                    $subjectAdmin = SubjectAdmin::where('subject_id', $data->id)->first();  
                    if (!empty($subjectAdmin)) {
                        $result = User::where('id', $subjectAdmin->user_id)->first(); 
                        $output = (!empty($result->user_name))? $result->user_name :'';
                    }
                    return  $output;
                })
                ->addColumn('classes', function($data){  
                    $output = ' ';
                    $classes = AssignSubject::where('subject_id', $data->id)->get();  
                    if (!empty($classes)) {
                        foreach ($classes as $class) {
                            $theClass = Classes::where('id', $class->class_id)->first(); 
                            $output = $output.'<a href="'.url('class-list?search='.$theClass->class_name).'" class="btn-warning text-dark text-md btn">'.$theClass->class_name.'</a>&nbsp;'; 
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
                    $viewUrl = url('subject-grades-list/'.$data->id);
                    $editUrl = url('edit-subject/'.$data->id);
                    $deleteUrl = url('delete-subject/'.$data->id);
                    $button = '<a class="" title="View Subject Roster" href="'.$viewUrl.'">
                                <i class="fa fa-eye text-orange"></i>
                                </a>';
                    $button .= '&nbsp;';
                    $button .= '<a class="" title="Edit Subject" href="'.$editUrl.'">
                                    <i class="fas fa-edit text-dark-pastel-blue"></i>
                                </a>';
                    $button .= '&nbsp;';
                    if ($data->status == 1) {
                        $button .= '<a onclick="deactivateSubject('.$data->id.')" title="Deactivate Subject" href="javascript:void(0);">
                                <i class="fa fa-times-circle text-orange-red"></i>
                                </a>';
                        $button .= '&nbsp;';
                    }else{
                        $button .= '<a onclick="activateSubject('.$data->id.')" class="activate_class" href="javascript:void(0);" title="Activate Subject">
                                <i class="fa fa-check text-green"></i>
                                </a>';
                        $button .= '&nbsp;';
                    }
                    $button .= '<a class="" title="Delete Subject" href="'.$deleteUrl.'">
                                <i class="fa fa-trash text-orange-red" aria-hidden="true"></i>
                                </a>';
                    return $button;

                })
                ->rawColumns(['username','classes','created','updated','action','status'])
                ->make(true);
            }            
            return view('subject.subject-list');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to activate Subject
    */
    public function activateSubject(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            $subject = Subject::findOrFail($request->input('subject_id'));
            if (!empty($subject)) {
                $subject->status = 1;
                $subject->updated_by = Auth::user()->id;
                $subject->update();
                return json_encode(['msg' => 'success']);
            }else{
                return json_encode(['msg' => 'error']);
            }
            
        }else{
            return json_encode(['msg' => 'unauthorized']);
        }
    }

    /***
     * Method to deactivate Subject
    */
    public function deactivateSubject(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            $subject = Subject::findOrFail($request->input('subject_id'));
            if (!empty($subject)) {
                $subject->status = 0;
                $subject->updated_by = Auth::user()->id;
                $subject->update();
                return json_encode(['msg' => 'success']);
            }else{
                return json_encode(['msg' => 'error']);
            }
            
        }else{
            return json_encode(['msg' => 'unauthorized']);
        }
    }

    /***
     * Method to display edit Subject form
    */
    public function editSubjectForm($subjectId){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            $subject = Subject::findOrFail($subjectId);
            // get active classes
            $classList =  Classes::where('status',1)->get();
            $assignedClasses = AssignSubject::where('subject_id', $subjectId)->get();
            //$assignedClassesId = AssignSubject::where('subject_id', $subjectId)->select('class_id');

            // $classList = [];
            // foreach($assignedClasses as $classes){
            //     //$classList[] = Classes::where('status',1)->where('id','!=', $classes->id)->select('id')->get();
            //     $classList = [$classes->id];
            // }
            // $class = [];
            // $class =  Classes::where('status',1)->where('id',$classList)->get();

            // dd($classList);die;
            if (!empty($subject)) {
               return view('subject.edit-subject', compact('classList','subject','assignedClasses'));
            }else{
                abort(404, 'Year Not Found.');
            }
            
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to edit Subject
    */
    public function editSubject(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {   
            $subject = Subject::findOrFail($request->input('subject_id'));
            //dd($request->all());die;
            if (!empty($subject)) {
                $validatedFields = Validator::make($request->all(), [
                'subject' => ['required', 'string', 'max:25']
            ]);

            // Unique semester validation
            $checkName = Subject::where('subject', $request->input('subject'))->where('id','!=',$request->input('subject_id'))->count();
                if($checkName > 0){
                    \Session::flash('msgErr','Oops! Subject'.$request->input('subject').' already exists, try another.' );
                    return redirect()->back()->withInput();
                }

                if ($validatedFields->fails()) {
                    \Session::flash('msgErr','Oops! Subject was not updated, try again.' );
                    return redirect()->back()->withErrors($validatedFields->errors())->withInput();
                }else{
                    $subject = new Subject;
                    $subject->subject = $request->input('subject');
                    $subject->status = (empty($request->input('status'))) ? 0 : 1;
                    $subject->updated_by = Auth::user()->id;
                    $subject->update();

                    // update the subject assignment to class
                    if (!empty($request->input('class'))) {
                        $classes = array_unique($request->input('class'));
                        
                        // Create New Assignment
                        foreach($classes as $class){
                            $assignedClasses = AssignSubject::where('subject_id', $request->input('subject_id'))->where('class_id', $class)->exists(); 
                           if (empty($assignedClasses)) {
                                $assignSubject = new AssignSubject;
                                $assignSubject->subject_id = $request->input('subject_id');
                                $assignSubject->class_id = $class;
                                $assignSubject->assigned_by = Auth::user()->id;
                                $assignSubject->save();
                           }
                            
                        }

                        // Unassignment process
                        AssignSubject::where('subject_id', $request->input('subject_id'))->whereNotIn('class_id', $classes)->delete();
                    }else{
                       $assignedClasses = AssignSubject::where('subject_id', $request->input('subject_id'))->exists(); 
                       if (!empty($assignedClasses)) {
                           AssignSubject::where('subject_id', $request->input('subject_id'))->delete(); 
                       }
                    }

                    \Session::flash('msg','Success!  Subject was updated successfully.' );
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
