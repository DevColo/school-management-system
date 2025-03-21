<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin')) {
            // total student
            $student_count = DB::table('users')
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('roles.name','student')
                ->where('users.status',1)
                ->count();

            // total classes
            $classes_count = DB::table('classes')
                ->where('status',1)
                ->count();

            // total librarian
            $librarian_count = DB::table('users')
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('roles.name','librarian')
                ->where('users.status',1)
                ->count();

            // total admin
            $admin_count = DB::table('users')
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('roles.name','admin')
                ->where('users.status',1)
                ->count();

            // total super_admin  
            $super_admin_count = DB::table('users')
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('roles.name','superadmin')
                ->where('users.status',1)
                ->count();

            $total_admins = $admin_count + $super_admin_count;

            // user detail
            $user_detail = DB::table('user_detail')->where('user_id',Auth::user()->id)->get();

            return view('home',compact('student_count','classes_count','librarian_count','total_admins','user_detail'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }
}
