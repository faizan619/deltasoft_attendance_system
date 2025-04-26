@extends('layouts.app')

@section('content')
    <div class="p-3 rounded shadow  col-10 mx-auto text-light border mt-5">
        

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

        <h2 class="text-center">Add New Employee</h2>
        <form action="{{ route('save_employee') }}" method="POST" class="row px-2 rounded mt-3">
            @csrf
            <div class="col-md-4 mt-3">
                <label for="empname">Name:</label>
                <input type="text" name="empname" id="empname" class="form-control" placeholder="Enter Employee Name">
                @error('empname')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4 mt-3">
                <label for="empemail">Email:</label>
                <input type="email" name="empemail" id="empemail" class="form-control" placeholder="Enter Employee Email">
                @error('empemail')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4 mt-3">
                <label for="empphone">Number:</label>
                <input type="number" name="empphone" id="empphone" class="form-control" placeholder="Enter Employee Number">
                @error('empphone')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4 mt-3">
                <label for="emppassword">Password:</label>
                <input type="password" name="emppassword" id="emppassword" class="form-control" placeholder="Enter Employee Password">
                @error('emppassword')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4 mt-3">
                <label for="empdesignation">Designation:</label>
                <input type="text" name="empdesignation" id="empdesignation" class="form-control" placeholder="Enter Employee Designation">
                @error('empdesignation')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-2 mt-3">
                <label for="empentrytime">Entry Time:</label>
                <input type="time" name="empentrytime" id="empentrytime" class="form-control" placeholder="Enter Employee Designation">
                @error('empentrytime')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-2 mt-3">
                <label for="empexittime">Exit Time:</label>
                <input type="time" name="empexittime" id="empexittime" class="form-control" placeholder="Enter Employee Designation">
                @error('empexittime')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-8 mt-3">
                <label for="empaddress">Address:</label>
                <textarea name="empaddress" id="empaddress" placeholder="Enter Employee Address" class="form-control" rows="1"></textarea>
                @error('empaddress')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4 mt-3">
                <label for="emprole">Role</label>
                <select name="emprole" id="emprole" class="form-control">
                    <option value="">Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="employee">Employee</option>
                    <option value="client">Client</option>
                </select>
                @error('emprole')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-12 text-center pt-3">
                <button class="btn btn-danger">Submit</button>
            </div>
        </form>
    </div>
@endsection