@extends('layouts.app')

@section('content')
<div class="container pt-5">
    <!-- <div class="">Hello Give me Your Reason.</div> -->
    <!-- <div class="">You Reach at {{\Carbon\Carbon::parse($attendanceTime)->format('g:i A')}}</div> -->
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-danger text-light text-right">Enter Reason</div>
                <div class="card-body text-center">
                    <h3 class="mb-3">{{$note}}</h3>
                    <!-- <h4></h4> -->
                    <h5> @if ($title == "Checkin")
                        You'r Entry Timing is {{\Carbon\Carbon::parse($empCheck->entryTime)->format('g:i A')}}
                        @elseif ($title == "Checkout")
                        You'r Leaving Timing is {{\Carbon\Carbon::parse($empCheck->exitTime)->format('g:i A')}}
                        @endif and You'r doing {{$title}} at {{\Carbon\Carbon::parse($attendanceTime)->format('g:i A')}}</h5>
                    <form method="post" action="{{ route('attendance.store') }}" class="row mt-3">
                        @csrf
                        @if($attendanceTime)
                            <input type="datetime-local" name="attendance_time" id="attendance_time" value="{{$attendanceTime}}" style="display: none;">
                        @elseif ($attendanceCheckoutTime)
                            <input type="datetime-local" name="attendanceCheckoutTime" id="attendanceCheckoutTime" value="{{$attendanceCheckoutTime}}" style="display: none;">
                        @endif
                        <div class="col-md-8">
                            <input type="text" name="reason" id="reason" placeholder="Enter Your Reason" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection