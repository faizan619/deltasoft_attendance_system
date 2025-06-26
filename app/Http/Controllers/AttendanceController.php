<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employees;
use App\Models\Locations;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

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
            $userEmail =  Auth::user()->email;
            $user = Employees::where('email',$userEmail)->first();
            // return $user;
            $loc = Locations::find($user->location);
            if($loc){
                return view('usersAttendance.attendance',compact('user','loc'));
            }
            else{
                return redirect()->back()->with('error','Your Location is not Assigned! Contact Admin');
            }
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'reason' => 'nullable|string|max:255',
            'attendance_time' => 'nullable|date',
            'attendance_checkout_time' => 'nullable|date',
        ]);

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

            // Check if attendanceTime is present in request
            
            // $attendanceTime = $request->attendance_time;
            // $attendanceCheckoutTime = $request->attendanceCheckoutTime;

            if (!empty($attendanceTime)) {
                $atten = new Attendance();
                $atten->checkIn = $attendanceTime;
                $atten->emp_id = $userId;
                $atten->remark = $reason;
                $atten->save();

                return redirect()->route('reached')->with('status', 'Reached Office Record Saved');
            }

            if (!empty($attendanceCheckoutTime)) {
                $checkOut = Attendance::where('emp_id', $userId)->latest()->first();

                if ($checkOut && empty($checkOut->checkOut)) {
                    $checkOut->checkOut = $attendanceCheckoutTime;
                    $checkOut->remark = $reason;
                    $checkOut->save();

                    return redirect()->route('reached')->with('status', 'Checkout time saved.');
                } else {
                    return "Already checked out or no check-in record found";
                }
            }
        }

        // Process Check-In
        if ($attendanceTime && !$attendanceCheckoutTime) {

            // Check Whether The User has Already CheckIn Today Or Not!

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
                return redirect()->route('attendanceReason')->with([
                    'attendanceTime' => $attendanceTime,
                    'title' => $title,
                    'note' => $note,
                    'empCheck' => $empCheck
                ]);
            } else {
                $note = "You are too Late to CheckIn";
                $title = "Checkin";
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

            $IsUserCheckIn = Attendance::where('emp_id', $userId)->whereDate('created_at', Date::now()) ->get('checkIn');   // yai sirf date dekhega
            if($IsUserCheckIn->isEmpty()){
                
                $actualEntryTime = Carbon::parse("$today {$empCheck->entryTime}");
                $attendanceTimeParsed = Carbon::parse($attendanceCheckoutTime);

                $earliestAllowed = $actualEntryTime->copy()->subMinutes(30);
                $latestAllowed = $actualEntryTime->copy()->addMinutes(20);

                if ($attendanceTimeParsed->between($earliestAllowed, $latestAllowed)) {
                    $attend = new Attendance();
                    $attend->checkIn = $attendanceCheckoutTime;
                    $attend->emp_id = $userId;
                    $attend->save();

                    return redirect()->route('reached')->with('status', 'Reached Office Record Saved');
                } elseif ($attendanceTimeParsed->lt($earliestAllowed)) {
                    $note = "You are too Early to CheckIn!";
                    $title = "Checkin";
                    return redirect()->route('attendanceReason')->with([
                        'attendanceTime' => $attendanceCheckoutTime,
                        'title' => $title,
                        'note' => $note,
                        'empCheck' => $empCheck
                    ]);
                } else {
                    $note = "You are too Late to CheckIn";
                    $title = "Checkin";
                    return redirect()->route('attendanceReason')->with([
                        'attendanceTime' => $attendanceCheckoutTime,
                        'title' => $title,
                        'note' => $note,
                        'empCheck' => $empCheck
                    ]);
                }
            }

            $actualExitTime = Carbon::parse("$today {$empCheck->exitTime}");
            $checkoutTimeParsed = Carbon::parse($attendanceCheckoutTime);

            $earliestCheckout = $actualExitTime->copy()->subMinutes(15);
            $latestCheckout = $actualExitTime->copy()->addMinutes(20);

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
