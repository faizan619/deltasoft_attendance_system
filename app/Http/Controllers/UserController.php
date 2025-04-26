<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employees;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function adminLogin()
    {
        return view('auth.login');
    }
    public function LoginProceed(Request $request)
    {
        // $user = new User();
        // $user->name = "faizan";
        // $user->email = "alamf6023@gmail.com";
        // $user->password = 1234;
        // $user->mobile = 9987337815;
        // $user->address = "deltasoft system";
        // $user->role = "admin";
        // $user->save();

        $data = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        if (Auth::attempt($data)) {
            // return redirect()->route('admin_dashboard');
            $user = Auth::user(); // Get the logged-in user

            if ($user->role == 'admin') {
                return redirect()->route('admin_dashboard');
            } elseif ($user->role == 'employee') {
                return redirect()->route('user_dashboard');
            } else {
                Auth::logout(); // Optional: logout unknown role
                return redirect()->back()->with('failed', 'Unauthorized role!');
            }
        } else {
            return redirect()->back()->with('failed', 'Please Enter Correct Credentials!');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('adminLogin');
    }

    public function admin_dashboard()
    {
        // $empData = User::with(['getUserAttendance' ])->get();
        $empData = Attendance::orderBy('checkIn', 'desc')->get();
        $empIds = User::pluck('username', 'id');

        return $empData;
        return view('dashboard', compact('empData', 'empIds'));
    }

    public function add_employee()
    {
        return view('admin.addEmployee');
    }

    public function save_employee(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'empname'       => 'required|string|max:255',
            'empemail'      => 'required|email|unique:users,email',
            'empphone'      => 'required',
            'emppassword'   => 'required|min:6',
            'empdesignation' => 'required|string',
            'empentrytime'  => 'required',
            'empexittime'   => 'required|after:empentrytime',
            'empaddress'    => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        DB::beginTransaction(); // Start the transaction

        try {
            $user = new User();
            $user->username = $request->empname;
            $user->email = $request->empemail;
            $user->password = Hash::make($request->emppassword);
            $user->mobile = $request->empphone;
            $user->address = $request->empaddress;
            $user->role = $request->emprole;
            $user->save();

            $employee = new Employees();
            $employee->name = $request->empname;
            $employee->email = $request->empemail;
            $employee->mobile = $request->empphone;
            $employee->address = $request->empaddress;
            $employee->entryTime = $request->empentrytime;
            $employee->exitTime = $request->empexittime;
            $employee->designation = $request->empdesignation;
            $employee->save();

            DB::commit(); // Commit if everything is fine

            // return "Data is Saved";
            return redirect()->route('admin_dashboard')->with('success', "Employee Address Successfully");
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback if any error occurs
            return redirect()->route('admin_dashboard')->with('success', "Failed to Save Data");
            // return "Failed to save data: " . $e->getMessage();
        }
    }

    public function ViewEmpList()
    {
        $emps = Employees::all();
        // return $emps;
        return view('admin.employeelist', compact('emps'));
    }
}
