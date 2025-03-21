<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

     /***
     * Method to Manage Roles
    */
    public function manageRoles(){
        if(Auth::user()->hasRole('superadmin')) {
            if(request()->ajax()){
            return datatables()->of(Role::all())
                ->addColumn('user_count', function($data){  
                    // total student
                    $user_count = DB::table('users')
                        ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                        ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                        ->where('roles.id',$data->id)
                        ->where('users.status',1)
                        ->count();        
                    return  $user_count;
                })
                ->addColumn('status', function($data){  
                    // total student
                    $user_count = DB::table('users')
                        ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                        ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                        ->where('roles.id',$data->id)
                        ->where('users.status',1)
                        ->count();        
                    if ($data->status == 1) {
                        $class = 'btn-success';
                        $status = 'Active'; 
                    }else{
                        $status='Inactive';
                        $class = 'btn-danger';
                    }
                    $status_btn = '<a href="#" class="'.$class.' white-text delete btn">'.$status.'</a>'; 
                    return  $user_count;
                })
                ->addColumn('action', function($data){
                    $editUrl = url('edit-role/'.$data->id);
                    $deleteUrl = url('delete-role/'.$data->id);
                    $button = '<a class="" title="Edit Role" href="'.$editUrl.'">
                                    <i class="fas fa-edit text-dark-pastel-blue"></i>
                                </a>';
                    $button .= '&nbsp;';
                    $button .= '<a onclick="deleteRole('.$data->id.')" title="Delete Role" href="javascript:void(0);">
                                <i class="fa fa-trash text-orange-red" aria-hidden="true"></i>
                                </a>';
                    return $button;

                })
                ->rawColumns(['user_count','action','status'])
                ->make(true);
            }            
            return view('roles-and-permission.manage-roles');
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    public function createRoles($value='')
    {
        return view('admin.createRole');
    }

    public function store(Request $request)
    {
        $role = $request->validate(['name' => ['required', 'max:20', 'unique:roles']]);
        Role::create($role);

        \Session::flash('msg','Role successfully created');
        return redirect()->back();
    }

    /***
     * Method to delete Role
    */
    public function deleteRole(Request $request){
        if(Auth::user()->hasRole('superadmin')) {
            $role = Role::findOrFail($request->input('role_id'));
            if (!empty($role)) {
                $users = DB::table('users')
                        ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                        ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                        ->where('roles.id',$request->input('role_id'))
                        ->where('users.status',1)
                        ->select('users.id')
                        ->get();  
                if (!empty($users[0])) {
                    foreach($users as $user){
                        $user = User::findOrFail($user->id);
                        $user->roles()->detach();
                        $user->status = 0;
                        $user->update();
                    }
                }    
                $role->delete();
                return json_encode(['msg' => 'success']);
            }else{
                return json_encode(['msg' => 'error']);
            }
            
        }else{
            return json_encode(['msg' => 'unauthorized']);
        }
    }

    /***
     * Method to display edit role form
    */
    public function editRoleForm($role_id){
        if(Auth::user()->hasRole('superadmin')) {
            $role = Role::findOrFail($role_id);
            if (!empty($role)) {
                return view('roles-and-permission.edit-role-form', compact('role'));
            }else{
                 abort(404, 'Role not found.');
            }
            
        }else{
             abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to display edit role form
    */
    public function updateRole(Request $request){
        if(Auth::user()->hasRole('superadmin')) {
            $input = $request->all();
            if (!empty($input['role_id'])) {
                $role = Role::findOrFail($input['role_id']);
                if ((!empty($role)) && ($role->name != $input['name'])) {
                    $validatedFields = Validator::make($input, [
                        'name' => ['required', 'max:20', 'unique:roles'],
                    ]);
                    if ($validatedFields->fails()) {
                        \Session::flash('msgErr','Oops! role was not updated, try again.' );
                        return redirect()->back()->withErrors($validatedFields->errors())->withInput();
                    }else{
                        $role->name = $input['name'];
                        $role->update();
                        \Session::flash('msg','Role successfully updated');
                        return redirect()->back();
                    }
                }else{
                    \Session::flash('msg','Role successfully updated');
                    return redirect()->back();
                }
            }else{
                \Session::flash('msgErr','Role not found');
                return redirect()->back();
            }
        }else{
            abort(403, 'Unauthorized access.');
        }
    }
}
