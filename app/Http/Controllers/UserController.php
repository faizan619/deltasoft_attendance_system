<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function adminLogin(){
        return view('auth.login');
    }
    public function LoginProceed(Request $request){
        // $user = new User();
        // $user->name = "faizan";
        // $user->email = "alamf6023@gmail.com";
        // $user->password = 1234;
        // $user->mobile = 9987337815;
        // $user->address = "deltasoft system";
        // $user->role = "admin";
        // $user->save();

        $data = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        if(Auth::attempt($data)){
            return redirect()->route('admin_dashboard');
        }
        else{
            return redirect()->back();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('adminLogin');
    }

    public function admin_dashboard(){
        $empData = Employees::with('attendanceLogs')->get();
        return view('dashboard',compact('empData'));
    }

    public function user_dashboard(){
        return view('attendance');
    }
}
