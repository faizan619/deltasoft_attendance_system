<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function user_dashboard()
    {
        return view('usersAttendance.attendance');
    }

    public function store(Request $request)
    {
        // return $request;
        $attendanceTime = $request->input('attendance_time');
        $attendanceCheckoutTime = $request->input('attendance_checkout_time');
        $userId = Auth::user()->id;

        if($attendanceTime){
            // Save the attendance to the database
            $attend = new Attendance();
            $attend->checkIn = $attendanceTime;
            $attend->emp_id = $userId;
            $attend->save();
        }
        elseif($attendanceCheckoutTime){
            $checkOut = Attendance::where('emp_id', Auth::user()->id)->latest()->first();
            $checkOut->checkOut = $attendanceCheckoutTime;
            $checkOut->save();
        }

        return redirect()->route('reached')->with('status', 'Reached Office Record Saved');
    }

    public function reached()
    {
        $emp = User::where('id', Auth::id())->first();

        $attendances = $emp->getUserAttendance()
            ->orderByDesc('created_at') // latest first
            ->paginate(30); // paginate 30 per page

        return view('usersAttendance.result', compact('emp', 'attendances'));


    }
}
