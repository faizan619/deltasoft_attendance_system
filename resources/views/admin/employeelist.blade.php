@extends('layouts.app')

@section('styleTag')
<style>
    .headerstyle {
        text-decoration: none;
    }

    .underlinestyle {
        text-decoration: underline;
    }

    .headerstyle:hover {
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <p class="fs-2 text-center my-3 py-2"><i> Welcome to Deltasoft Attendance Application</i></p>

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
    <div class="col-md-12 text-center">
        <a class="btn btn-danger mb-3" href="{{ route('add_employee') }}">Add New Employee</a>
    </div>
    <div class="card col-md-8 mx-auto">
        <div class="card-header bg-primary">
            <a class="text-light headerstyle" href="{{ route('admin_dashboard') }}">Employee Attendance Records</a> <span> | </span>
            <a class="text-light headerstyle underlinestyle" href="{{ route('emp_list') }}">Employee List</a> <span> | </span> <a href="{{ route('viewPresentLocations') }}" class="text-light headerstyle">Locations</a>
        </div>
        <div class="card-body px-1 pb-0">
            <p>
            <form action="{{ route('emp_list') }}" class="row">
                <div class="col-md-5">
                    <input type="text" name="emp_name" id="emp_name" class="form-control form-control-sm" placeholder="Enter Employee Name" value="{{ request('emp_name') }}">
                </div>
                <div class="col-md-7">
                    <button type="submit" class="btn btn-sm btn-primary mr-2"><i class="fas fa-search"></i> Search</button>
                    <a href="{{ route('emp_list') }}" class="btn btn-sm btn-secondary">Reset</a>
                </div>
            </form>
            </p>
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
                            <button
                                class="btn btn-warning editBtn"
                                data-id="{{ $emp->id }}"
                                data-name="{{ $emp->name }}"
                                data-email="{{ $emp->email }}"
                                data-mobile="{{ $emp->mobile }}"
                                data-entrytime="{{ $emp->entryTime }}"
                                data-exittime="{{ $emp->exitTime }}"
                                data-designation="{{ $emp->designation }}"
                                data-address="{{ $emp->address }}"
                                data-role="{{ $emp->role }}"
                                data-password="{{ $emp->password }}"
                                data-bs-toggle="modal"
                                data-bs-target="#editEmployeeDetails">
                                Edit
                            </button>
                            <!-- <button class="btn btn-danger">Delete</button> -->
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="">No Data Found</td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
            <p>
                {{$emps->links()}}
            </p>
        </div>
    </div>

</div>

@section('scriptTag')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.editBtn').click(function() {
            var button = $(this);
            $('#empname').val(button.data('name'));
            $('#empemail').val(button.data('email'));
            $('#empphone').val(button.data('mobile'));
            $('#empentrytime').val(button.data('entrytime'));
            $('#empexittime').val(button.data('exittime'));
            $('#empdesignation').val(button.data('designation'));
            $('#empaddress').val(button.data('address'));
            $('#emppassword').val(button.data('password')); // Be careful with passwords.
            $('#empid').val(button.data('id'));

            // 🚀 For Role Dropdown:
            var role = button.data('role');
            $('#emprole').val(role);

            // 🚨 Sometimes safer to force change event (in case browser doesn't refresh dropdown automatically)
            $('#emprole').trigger('change');
        });
    });
</script>
@endsection

<!-- Modal -->
<div class="modal fade" id="editEmployeeDetails" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Employee Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('update_employee') }}" method="POST" class="row px-2 rounded mt-3">
                @csrf
                <input type="hidden" name="empid" id="empid">

                <div class="modal-body">
                    <div class="row px-2">
                        <div class="col-md-4" required>
                            <label for="empname">Name:</label>
                            <input type="text" name="empname" id="empname" class="form-control" placeholder="Enter Employee Name">
                            @error('empname')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4" required>
                            <label for="empemail">Email:</label>
                            <input type="email" name="empemail" id="empemail" class="form-control" placeholder="Enter Employee Email">
                            @error('empemail')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4" required>
                            <label for="empphone">Number:</label>
                            <input type="number" name="empphone" id="empphone" class="form-control" placeholder="Enter Employee Number">
                            @error('empphone')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4 mt-3" required>
                            <label for="empdesignation">Designation:</label>
                            <input type="text" name="empdesignation" id="empdesignation" class="form-control" placeholder="Enter Employee Designation">
                            @error('empdesignation')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-2 mt-3" required>
                            <label for="empentrytime">Entry Time:</label>
                            <input type="time" name="empentrytime" id="empentrytime" class="form-control" placeholder="Enter Employee Designation">
                            @error('empentrytime')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-2 mt-3" required>
                            <label for="empexittime">Exit Time:</label>
                            <input type="time" name="empexittime" id="empexittime" class="form-control" placeholder="Enter Employee Designation">
                            @error('empexittime')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-8 mt-3" required>
                            <label for="empaddress">Address:</label>
                            <textarea name="empaddress" id="empaddress" placeholder="Enter Employee Address" class="form-control" rows="3"></textarea>
                            @error('empaddress')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4 mt-3">
                            <label for="emprole">Role</label>
                            <select name="emprole" id="emprole" class="form-control" required>
                                <option value="">Select Role</option>
                                <option value="admin" {{ old('emprole') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="employee" {{ old('emprole') == 'employee' ? 'selected' : '' }}>Employee</option>
                                <option value="client" {{ old('emprole') == 'client' ? 'selected' : '' }}>Client</option>

                            </select>

                            @error('emprole')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection