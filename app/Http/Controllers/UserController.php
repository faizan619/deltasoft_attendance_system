<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employees;
use App\Models\Locations;
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
        // $user->name = "Admin";
        // $user->email = "attendance@deltasoftsys.in";
        // $user->password = Hash::make(deltasoft@123);
        // $user->mobile = 8097845219;
        // $user->address = "5, Garden View, Beside Chopstick Restaurant, Near Sai Nagar Ground, Sai Nagar, Vasai West";
        // $user->role = "admin";
        // $user->save();

        // return "done with saving";

        $data = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $remember = $request->has('remember'); // Checkbox from login form
        if (Auth::attempt($data, $remember)) {
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

    public function admin_dashboard(Request $request)
    {
        $query = Attendance::query();

        // Search by employee name
        if (!empty($request->emp_name)) {
            $matchedUserIds = User::where('username', 'like', '%' . $request->emp_name . '%')->pluck('id');
            $query->whereIn('emp_id', $matchedUserIds);
        }

        // Filter by date range if provided
        if (!empty($request->start) && !empty($request->end)) {
            $startDate = \Carbon\Carbon::parse($request->start)->startOfDay();
            $endDate = \Carbon\Carbon::parse($request->end)->endOfDay();

            $query->whereBetween('checkIn', [$startDate, $endDate]);
        }

        $empData = $query->orderBy('checkIn', 'desc')->simplePaginate(50);

        $empIds = User::pluck('username', 'id');

        return view('dashboard', compact('empData', 'empIds'));
    }



    public function add_employee()
    {
        $locs = Locations::get();
        return view('admin.addEmployee', compact('locs'));
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
            'emplocation'   => 'required',
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
            $employee->location = $request->emplocation;
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

    public function ViewEmpList(Request $request)
    {
        $query = Employees::query();

        if (!empty($request->emp_name)) {
            $query->where('name', 'like', '%' . $request->emp_name . '%');
        }

        $emps = $query->simplePaginate(50);
        return view('admin.employeelist', compact('emps'));
    }

    public function update_employee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'empid'          => 'required|exists:employees,id', // Make sure employee exists
            'empname'        => 'required|string|max:255',
            'empemail'       => 'required|email|unique:users,email,' . $request->empid,
            'empphone'       => 'required',
            'empdesignation' => 'required|string',
            'empentrytime'   => 'required',
            'empexittime'    => 'required|after:empentrytime',
            'empaddress'     => 'required|string',
            'emprole'        => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Update User
            $user = User::where('email', $request->empemail)->first();
            if ($user) {
                $user->username = $request->empname;
                $user->email = $request->empemail;
                $user->mobile = $request->empphone;
                $user->address = $request->empaddress;
                $user->role = $request->emprole;
                $user->save();
            }

            // Update Employee
            $employee = Employees::where('id', $request->empid)->first();
            if ($employee) {
                $employee->name = $request->empname;
                $employee->email = $request->empemail;
                $employee->mobile = $request->empphone;
                $employee->address = $request->empaddress;
                $employee->entryTime = $request->empentrytime;
                $employee->exitTime = $request->empexittime;
                $employee->designation = $request->empdesignation;
                $employee->save();
            }

            DB::commit();

            return redirect()->route('admin_dashboard')->with('success', "Employee Updated Successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin_dashboard')->with('error', "Failed to Update Employee: " . $e->getMessage());
        }
    }
}
