<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\UserDetail;
use App\Models\UserAdmin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserAccountMail;

class UserController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct(){
        $this->middleware('auth');
    }

    /***
     * Method to diplay user profile page
    */
    public function userProfilePage($user_id){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('lecturer')) {
            $user = User::findOrFail($user_id);
            if (empty($user)) {
                abort(400, 'User Not Found.');
            }
            $user_detail = DB::table('user_detail')->where('user_id',$user_id)->get();
            return view('admin.admin-profile', compact('user_detail','user'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to diplay user profile edit page
    */
    public function userProfileEditPage($user_id){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('lecturer')) {
            $user = User::findOrFail($user_id);
            if (empty($user)) {
                abort(400, 'User Not Found.');
            }
            $user_detail = DB::table('user_detail')->where('user_id',$user_id)->get();
            return view('admin.edit-admin-profile', compact('user_detail','user'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to update user profile
    */
    public function updateUserProfile(Request $request){
        $validatedFields = Validator::make($request->all(), [
            //'user_name'  => ['required', 'string', 'max:30', 'unique:users'],
            'first_name' => ['required', 'string', 'max:30'],
            'other_name' => ['nullable','max:30'],
            'last_name'  => ['required', 'string', 'max:30'],
            'gender'     => ['required', 'string'],
            'job_title'  => ['nullable','max:30'],
            //'email'      => ['nullable','max:30','unique:users'],
            'phone'      => ['nullable','max:14'],
            'address'    => ['nullable','max:60'],
        ]);
        if ($validatedFields->fails()) {
            if ($request->input('user_id') == Auth::user()->id) {
                \Session::flash('msgErr','Your profile was not updated, try again.' );
            }else{
                \Session::flash('msgErr','User profile was not updated, try again.' );
            }
            

            return redirect()->back()->withErrors($validatedFields->errors())->withInput();
        }else{
            $user_id = $request->input('user_id');
            $userDetail = UserDetail::where('user_id', $user_id)->first();
            $userDetail->update($validatedFields->validated());

            $user = User::findOrFail($user_id);

            if (!$user) {
                \Session::flash('msgErr','User not found.' );
                return redirect()->back();
            }

            // Check if the requested username is different from the current username
            if ($request->input('user_name') !== $user->user_name) {
                // Check if the new username already exists for another user
                $existingUser = User::where('user_name', $request->input('user_name'))->first();

                if ($existingUser) {
                    // Another user already has this username, so skip the update
                    \Session::flash('msgErr','Username already exists.' );
                }else{
                    $user->user_name = $request->input('user_name');
                }
            }

            $user->email = $request->input('email');
            $user->update();
            if ($request->input('user_id') == Auth::user()->id) {
                \Session::flash('msg','Your profile was updated successfully.' );
            }else{
                \Session::flash('msg','User profile was updated successfully.' );
            }
             

            return redirect()->back();
        }
    }

    /***
     * Method to diplay change password form
    */
    public function changePasswordForm($user_id){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('lecturer') || Auth::user()->hasRole('student')) {
            $user = User::findOrFail($user_id);
            if (empty($user)) {
                abort(400, 'User Not Found.');
            }
            $user_detail = DB::table('user_detail')->where('user_id',$user_id)->get();
            return view('user.change-password', compact('user_detail','user'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to change password
    */
    public function changePassword(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('lecturer') || Auth::user()->hasRole('student')) {
           $validatedFields = Validator::make($request->all(), [
                'password' => ['required', 'string', 'min:6', 'confirmed']
            ]);
            if ($validatedFields->fails()) {
                \Session::flash('msgErr','Your password was not changed, try again.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }else{
                $user_id = $request->input('user_id');
                $user = User::findOrFail($user_id);
                $user->password = Hash::make($request->input('password'));
                $user->update();

                \Session::flash('msg','Your password was changed successfully.' );
                return redirect()->back();
            }
        }else{
            abort(403, 'Unauthorized access.');
        }
    }
    

    /***
     * Method to diplay admin form
    */
    public function addAdminForm(){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            $roles = Role::whereNotIn('name', ['student','superadmin','guest','librarian'])->get();
            return view('admin.add-admin-form', compact('roles'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to add admin
    */
    public function addAdmin(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('lecturer') || Auth::user()->hasRole('student')) {
            $validatedFields = Validator::make($request->all(), [
                'user_name'  => ['required', 'string', 'max:30', 'unique:users'],
                'first_name' => ['required', 'string', 'max:30'],
                'other_name' => ['nullable','max:30'],
                'last_name'  => ['required', 'string', 'max:30'],
                'gender'     => ['required', 'string'],
                'role'     => ['required', 'string'],
                'job_title'  => ['nullable','max:30'],
                'email'      => ['nullable','max:30','unique:users'],
                'phone'      => ['nullable','max:14'],
                'address'    => ['nullable','max:60'],
                'profile_image'    => ['nullable','mimes:jpeg,png,jpg,gif,svg','max:5048'],
                'password' => ['required', 'string', 'min:6']
            ]);
            if ($validatedFields->fails()) {
                \Session::flash('msgErr','user was not registered, try again.' );
                return redirect()->back()->withErrors($validatedFields->errors())->withInput();
            }else{
                // upload profile image
                $image = $request->file('profile_image');
                if (empty($image)){
                    $imageName='avatar.jpg';
                }else{
                    $imageName = time().'.'.$image->extension(); 
                    $image->move(public_path('admin_img'), $imageName);
                }
                $user = User::create([
                    'user_name' => $request->input('user_name'),
                    'email' => $request->input('email'),
                    'profile_image' => $imageName,
                    'password' => Hash::make($request->input('password')),
                    'status' => 1,
                ]);

                // assign user role
                $user->assignRole($request->input('role'));

                $newUser = DB::table('users')->where('user_name',$request->input('user_name'))->select('*')->get();

                if (!empty($newUser[0])) {
                    UserDetail::create([
                        'user_id' => $newUser[0]->id,
                        'first_name' => $request->input('first_name'),
                        'other_name' => $request->input('other_name'),
                        'last_name' => $request->input('last_name'),
                        'gender' => $request->input('gender'),
                        'job_title' => $request->input('job_title'),
                        'phone' => $request->input('phone'),
                        'address' => $request->input('address'),
                        
                    ]);

                    $newUserDetail = DB::table('user_detail')->where('user_id',$newUser[0]->id)->select('*')->get();

                     if (!empty($newUser[0])) {
                        UserAdmin::create([
                            'created_by' => Auth::user()->id,
                            'user_detail_id' => $newUserDetail[0]->id,
                        ]);
                    }

                    if (!empty($request->input('email'))) {
                        $message = "Hi ". $request->input('last_name').", your ".$request->input('role')." account has been created successfully."; 
                        $message .= "\nYour login credentials:"; 
                        $message .= "\nusername: ".$request->input('user_name');  
                        $message .= "\npassword: ".$request->input('password');  

                        Mail::to($user->email)->send(new UserAccountMail($message));
                    }

                    \Session::flash('msg','User has been registered successfully.' );
                }else{
                    \Session::flash('msgWrn','User has been registered successfully.' );
                }
                
                return redirect()->back();
            }
        }else{
            abort(403, 'Unauthorized access.');
        }
    }

    /***
     * Method to update User Profile Photo
    */
    public function updateUserProfilePhoto(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            $user = User::findOrFail(Auth::user()->id);

            if (!empty($request->file('profile_image'))){
                $filePath = public_path().'/admin_img/'.$user->profile_image;
                if ($user->profile_image != 'avatar.jpg') {
                    if (File::exists($filePath)) {
                        File::delete($filePath);
                    }
                }
                
                $file = $request->file('profile_image');
                $extension = $file->getClientOriginalExtension();
                $imageName = time().'.'.$extension;  
                $file->move(public_path('admin_img'), $imageName);
                $user->profile_image = $imageName;
            }
            if(empty($request->file('profile_image')) && empty($request->input('existing_image'))){
                $defaultImageName = 'avatar.jpg';
                $user->profile_image = $defaultImageName;
            }
                      
            $user->update();
            
            \Session::flash('msg','Your profile photo was successfully updated');
            return redirect()->back();
        }
    }

    /***
     * Method to display admins list
    */
    public function viewAdminList()
{
    if (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
        if (request()->ajax()) {
            // Fetch only users with role 'superadmin' or 'admin'
            $admins = User::whereHas('roles', function ($query) {
                $query->where('name', '!=', 'student');
            })->latest()->get();

            return datatables()->of($admins)
                ->addColumn('first_name', function ($data) {
                    return DB::table('user_detail')->where('user_id', $data->id)->value('first_name') ?? '';
                })
                ->addColumn('other_name', function ($data) {
                    return DB::table('user_detail')->where('user_id', $data->id)->value('other_name') ?? '';
                })
                ->addColumn('last_name', function ($data) {
                    return DB::table('user_detail')->where('user_id', $data->id)->value('last_name') ?? '';
                })
                ->addColumn('gender', function ($data) {
                    $gender = DB::table('user_detail')->where('user_id', $data->id)->value('gender');
                    return $gender === 'f' ? 'Female' : ($gender === 'm' ? 'Male' : '');
                })
                ->addColumn('job_title', function ($data) {
                    return DB::table('user_detail')->where('user_id', $data->id)->value('job_title') ?? '';
                })
                ->addColumn('phone', function ($data) {
                    return DB::table('user_detail')->where('user_id', $data->id)->value('phone') ?? '';
                })
                ->addColumn('address', function ($data) {
                    return DB::table('user_detail')->where('user_id', $data->id)->value('address') ?? '';
                })
                ->addColumn('status', function ($data) {
                    $class = $data->status == 1 ? 'btn-success' : 'btn-danger';
                    $status = $data->status == 1 ? 'Active' : 'Inactive';
                    return '<a href="#" class="' . $class . ' white-text delete btn">' . $status . '</a>';
                })
                ->addColumn('action', function ($data) {
                    $viewUrl = url('user-profile/' . $data->id);
                    $editUrl = url('account-setting/' . $data->id);
                    $changePassword = url('change-password/' . $data->id);
                    $deleteUrl = url('delete-user/' . $data->id);

                    $button = '<a title="View Profile" href="' . $viewUrl . '">
                                    <i class="fa fa-eye text-orange"></i>
                                </a>';
                    $button .= '&nbsp;<a title="Account Setting" href="' . $editUrl . '">
                                    <i class="fas fa-cogs text-dark-pastel-blue"></i>
                                </a>';
                    $button .= '&nbsp;<a title="Change Password" href="' . $changePassword . '">
                                    <i class="fas fa-key text-secondary"></i>
                                </a>';
                    
                    if ($data->status == 1) {
                        $button .= '&nbsp;<a onclick="deactivateUser(' . $data->id . ')" title="Deactivate User" href="javascript:void(0);">
                                        <i class="fa fa-times-circle text-orange-red"></i>
                                    </a>';
                    } else {
                        $button .= '&nbsp;<a onclick="activateUser(' . $data->id . ')" class="activate_user" href="javascript:void(0);" title="Activate User">
                                        <i class="fa fa-check text-green"></i>
                                    </a>';
                    }

                    $button .= '&nbsp;<a title="Delete" href="' . $deleteUrl . '">
                                    <i class="fa fa-trash text-orange-red"></i>
                                </a>';

                    return $button;
                })
                ->rawColumns(['first_name', 'other_name', 'last_name', 'action', 'status'])
                ->make(true);
        }

        return view('admin.admin-list');
    } else {
        abort(403, 'Unauthorized access.');
    }
}


    /***
     * Method to activate user
    */
    public function activateUser(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            $user = User::findOrFail($request->input('user_id'));
            if (!empty($user)) {
                $user->assignRole('lecturer');
                $user->status = 1;
                $user->update();
                return json_encode(['msg' => 'success']);
            }else{
                return json_encode(['msg' => 'error']);
            }
            
        }else{
            return json_encode(['msg' => 'unauthorized']);
        }
    }

    /***
     * Method to deactivate user
    */
    public function deactivateUser(Request $request){
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            $user = User::findOrFail($request->input('user_id'));
            if (!empty($user)) {
                //$user->roles()->detach();
                $user->status = 0;
                $user->update();
                return json_encode(['msg' => 'success']);
            }else{
                return json_encode(['msg' => 'error']);
            }
            
        }else{
            return json_encode(['msg' => 'unauthorized']);
        }
    }
}
