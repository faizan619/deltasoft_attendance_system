<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employees;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function user_dashboard()
    {
        $user = Auth::user()->role;
        // return $user;
        if($user == "admin"){
            return redirect()->back()->with('error',"Admin Can't Put Their Attendance. Login With your Employee ID");
        }
        else{
            $user = Employees::all();
            // return $user;
            return view('usersAttendance.attendance');
        }
    }

    // public function store(Request $request)
    // {
    //     // return $request;

    //     $attendanceTime = $request->input('attendance_time');
    //     $attendanceCheckoutTime = $request->input('attendance_checkout_time');
    //     $userId = Auth::user()->id;

    //     $userEmail = Auth::user()->email;
    //     $empCheck = Employees::where('email', $userEmail)->first();
    //     // return $empCheck;

    //     $today = Carbon::today()->toDateString(); // e.g., 2025-04-12


    //     if($request->reason){
    //         // return $request;
    //         $reason = $request->reason;
    //         if($attendanceTime){
    //             $atten = new Attendance();
    //             $atten->checkIn = $attendanceTime;
    //             $atten->emp_id = $userId;
    //             $atten->remark = $reason;
    //             $atten->save();

    //             return redirect()->route('reached')->with('status', 'Reached Office Record Saved');
    //         }
    //     }

    //     if ($attendanceTime) {
    //         // Combine today's date with entry time
    //         $actualEntryTime = Carbon::parse("$today {$empCheck->entryTime}");
    //         $attendanceTimeParsed = Carbon::parse($attendanceTime);

    //         $earliestAllowed = $actualEntryTime->copy()->subMinutes(30);
    //         $latestAllowed = $actualEntryTime->copy()->addMinutes(20);

    //         if ($attendanceTimeParsed->between($earliestAllowed, $latestAllowed)) {
    //             // Save the attendance
    //             $attend = new Attendance();
    //             $attend->checkIn = $attendanceTime;
    //             $attend->emp_id = $userId;
    //             $attend->save();

    //             return redirect()->route('reached')->with('status', 'Reached Office Record Saved');
    //         } elseif ($attendanceTimeParsed->lt($earliestAllowed)) {
    //             // $reachTime = $attendanceTime;
    //             $note = "You are too Early to CheckIn!";
    //             $title = "Checkin";
    //             return view('usersAttendance.attendance_reason', compact('attendanceTime', 'note', 'title', 'empCheck'));
    //         } else {
    //             $title = "Checkin";
    //             $note = "You are too Late to CheckIn";
    //             return view('usersAttendance.attendance_reason', compact('attendanceTime', 'note', 'title', 'empCheck'));
    //         }
    //     }

    //     if ($attendanceCheckoutTime) {
    //         // Combine today's date with exit time
    //         $actualExitTime = Carbon::parse("$today {$empCheck->exitTime}");
    //         $checkoutTimeParsed = Carbon::parse($attendanceCheckoutTime);

    //         $earliestCheckout = $actualExitTime->copy()->subMinutes(15);
    //         $latestCheckout = $actualExitTime->copy()->addMinutes(20);

    //         if ($checkoutTimeParsed->between($earliestCheckout, $latestCheckout)) {
    //             $checkOut = Attendance::where('emp_id', $userId)->latest()->first();
    //             if ($checkOut) {
    //                 $checkOut->checkOut = $attendanceCheckoutTime;
    //                 $checkOut->save();
    //             }

    //             return redirect()->route('reached')->with('status', 'Checkout time saved.');
    //         } elseif ($checkoutTimeParsed->lt($earliestCheckout)) {
    //             $note = "You are checking out too early.!";
    //             $title = "Checkout";
    //             return view('usersAttendance.attendance_reason', compact('attendanceTime', 'note', 'title', 'empCheck'));
    //         } else {
    //             $note = "You are checking out too late.!";
    //             $title = "Checkout";
    //             return view('usersAttendance.attendance_reason', compact('attendanceTime', 'note', 'title', 'empCheck'));
    //         }
    //     }


    //     return back()->with('error', 'No valid attendance data submitted.');
    // }

    public function store(Request $request)
    {
        // return $request;
        $attendanceTime = $request->input('attendance_time');
        $attendanceCheckoutTime = $request->input('attendance_checkout_time');
        $userId = Auth::user()->id;
        $userEmail = Auth::user()->email;
        $empCheck = Employees::where('email', $userEmail)->first();
        $today = Carbon::today()->toDateString();

        // Check if already checked in today
        $todayAttendance = Attendance::where('emp_id', $userId)
            ->whereDate('checkIn', Carbon::today())
            ->first();

        if ($attendanceTime && $todayAttendance) {
            return redirect()->route('reached')->with('status', 'You have already checked in today!');
        }

        if ($attendanceCheckoutTime && $todayAttendance && $todayAttendance->checkOut) {
            return redirect()->route('reached')->with('status', 'You have already checked out today!');
        }


        // Check if Reason is submitted
        if ($request->reason) {
            $reason = $request->reason;

            if ($attendanceTime) {
                $atten = new Attendance();
                $atten->checkIn = $attendanceTime;
                $atten->emp_id = $userId;
                $atten->remark = $reason;
                $atten->save();

                return redirect()->route('reached')->with('status', 'Reached Office Record Saved');
            }

            if ($attendanceCheckoutTime) {
                $checkOut = Attendance::where('emp_id', $userId)->latest()->first();
                if ($checkOut) {
                    $checkOut->checkOut = $attendanceCheckoutTime;
                    $checkOut->remark = $reason;
                    $checkOut->save();
                }

                return redirect()->route('reached')->with('status', 'Checkout time saved.');
            }
        }

        // Process Check-In
        if ($attendanceTime && !$attendanceCheckoutTime) {

            // Check Whether The User has Already CheckIn Today Or Not!
            // $alreadyCheckedIn = Attendance::where('emp_id', $userId)
            //     ->whereDate('checkIn', Carbon::today())
            //     ->exists();

            // if ($alreadyCheckedIn) {
            //     return redirect()->route('reached')->with('status', 'You have already checked in today!');
            // }

            $actualEntryTime = Carbon::parse("$today {$empCheck->entryTime}");
            $attendanceTimeParsed = Carbon::parse($attendanceTime);

            $earliestAllowed = $actualEntryTime->copy()->subMinutes(30);
            $latestAllowed = $actualEntryTime->copy()->addMinutes(20);

            if ($attendanceTimeParsed->between($earliestAllowed, $latestAllowed)) {
                $attend = new Attendance();
                $attend->checkIn = $attendanceTime;
                $attend->emp_id = $userId;
                $attend->save();

                return redirect()->route('reached')->with('status', 'Reached Office Record Saved');
            } elseif ($attendanceTimeParsed->lt($earliestAllowed)) {
                $note = "You are too Early to CheckIn!";
                $title = "Checkin";
                // return redirect()->route('attendanceReason',compact('attendanceTime', 'note', 'title', 'empCheck'));
                return redirect()->route('attendanceReason')->with([
                    'attendanceTime' => $attendanceTime,
                    'title' => $title,
                    'note' => $note,
                    'empCheck' => $empCheck
                ]);
            } else {
                $note = "You are too Late to CheckIn";
                $title = "Checkin";
                // return redirect()->route('attendanceReason',compact('attendanceTime', 'note', 'title', 'empCheck'));
                return redirect()->route('attendanceReason')->with([
                    'attendanceTime' => $attendanceTime,
                    'title' => $title,
                    'note' => $note,
                    'empCheck' => $empCheck
                ]);
            }
        }

        // Process Check-Out
        if ($attendanceCheckoutTime) {
            $actualExitTime = Carbon::parse("$today {$empCheck->exitTime}");
            $checkoutTimeParsed = Carbon::parse($attendanceCheckoutTime);

            $earliestCheckout = $actualExitTime->copy()->subMinutes(15);
            $latestCheckout = $actualExitTime->copy()->addMinutes(20);

            // Check Whether The User has Already CheckOut Today Or Not!
            // $todayAttendance = Attendance::where('emp_id', $userId)
            //     ->whereDate('checkIn', Carbon::today())
            //     ->first();

            // if ($todayAttendance && $todayAttendance->checkOut) {
            //     return redirect()->route('reached')->with('status', 'You have already checked out today!');
            // }


            if ($checkoutTimeParsed->between($earliestCheckout, $latestCheckout)) {
                $checkOut = Attendance::where('emp_id', $userId)->latest()->first();
                if ($checkOut) {
                    $checkOut->checkOut = $attendanceCheckoutTime;
                    $checkOut->save();
                }

                return redirect()->route('reached')->with('status', 'Checkout time saved.');
            } elseif ($checkoutTimeParsed->lt($earliestCheckout)) {
                $note = "You are checking out too early!";
                $title = "Checkout";
                return view('usersAttendance.attendance_reason', compact('attendanceCheckoutTime', 'note', 'title', 'empCheck'));
            } else {
                $note = "You are checking out too late!";
                $title = "Checkout";
                return view('usersAttendance.attendance_reason', compact('attendanceCheckoutTime', 'note', 'title', 'empCheck'));
            }
        }

        return back()->with('error', 'No valid attendance data submitted.');
    }

    public function attendanceReason()
    {
        $attendanceTime = session('attendanceTime');
        $title = session('title');
        $note = session('note');
        $empCheck = session('empCheck');

        return view('usersAttendance.attendance_reason', compact('attendanceTime', 'title', 'note', 'empCheck'));
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
