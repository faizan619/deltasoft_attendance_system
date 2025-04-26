@extends('layouts.app')

@section('styleTag')
<style>
    .headerstyle{
        text-decoration: none;
    }
    .headerstyle:hover{
        text-decoration: underline;
    }   
</style>
@endsection

@section('content')
<div class="container-fluid">
    <p class="text-light fs-2 text-center my-3 py-2"><i> Welcome to Deltasoft Attendance Application</i></p>

    <div class="col-md-8 mx-auto">
        @if (session('success'))
        <div class="alert alert-success w-100">
            {{ session('success') }}
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger w-100">
            {{ session('error') }}
        </div>
        @endif
    </div>
    <!-- <div class="container-md-fluid mt-3"> -->
        <div class="col-md-12 text-center">
        <a class="btn btn-danger mb-3" href="{{ route('add_employee') }}">Add New Employee</a>
        </div>
    <div class="card col-md-8 mx-auto">
        <!-- @if (Auth::user()->role == "admin")
        <li class="nav-item border border-danger rounded mx-3 bg-danger">
            <a class="nav-link text-light" href="{{ route('add_employee') }}">Add Employee</a>
        </li>
        @endif -->
        <div class="card-header bg-primary">
            <a class="text-light headerstyle" href="{{ route('admin_dashboard') }}">Employee Attendance Records</a> <span> | </span>
            <a class="text-light headerstyle" href="{{ route('emp_list') }}">Employee List</a><span> | </span>
            <a class="text-light headerstyle" href="{{ route('admin_dashboard') }}">Employee List</a><span> | </span>
        </div>
        <div class="card-body px-1 pb-0">
            <table class="table table-sm table-striped table-bordered">
                <thead class="">
                    <tr>
                        <th>Emp Name</th>
                        <th>Mobile</th>
                        <th>CheckIn Time</th>
                        <th>CheckOut Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($emps as $emp)
                        <tr>
                            <td>{{ $emp->name }}</td>
                            <td>{{ $emp->mobile }}</td>
                            <td>{{ \Carbon\Carbon::parse($emp->entryTime)->format('g:i A') }}</td>
                        <td>{{ \Carbon\Carbon::parse($emp->exitTime)->format('g:i A') }}</td>
                        <td>
                                <button class="btn btn-warning">Edit</button>
                                <button class="btn btn-danger">Delete</button>
                        </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="">No Data Found</td>
                    </tr>
                    @endforelse
                    
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection