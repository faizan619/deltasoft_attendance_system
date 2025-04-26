@extends('layouts.app')

@section('styleTag')
<style>
    .headerstyle {
        text-decoration: none;
    }

    .headerstyle:hover {
        text-decoration: underline;
    }
</style>
<!-- DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

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
    <!-- <div class="container-md-fluid mt-3"> -->
    <div class="col-md-12 text-center">
        <a class="btn btn-danger mb-3" href="{{ route('add_employee') }}">Add New Employee</a>
    </div>
    <div class="card col-md-8 mx-auto">
        <div class="card-header bg-primary">
            <a class="text-light headerstyle" href="{{ route('admin_dashboard') }}">Employee Attendance Records</a> <span> | </span>
            <a class="text-light headerstyle" href="{{ route('emp_list') }}">Employee List</a>
        </div>
        <div class="card-body p-3">
            <table id="attendanceTable" class="table table-sm table-striped table-bordered">
                <thead class="bg-dark text-light">
                    <tr>
                        <th>Emp Name</th>
                        <th>Date</th>
                        <th>CheckIn</th>
                        <th>CheckOut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($empData as $emp)
                    <tr>
                        <td>{{ $empIds->get($emp->emp_id) }}</td>
                        <td>{{ \Carbon\Carbon::parse($emp->created_at)->format('jS F Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($emp->checkIn)->format('g:i A') }}</td>
                        <td>{{ $emp->checkOut ? \Carbon\Carbon::parse($emp->checkOut)->format('g:i A') : 'N/A' }}</td>
                        <td></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="">No Data Found</td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
            <!--  -->
        </div>

    </div>

</div>

@endsection

@section('scriptTag')

<!-- DataTables Buttons + JS libraries -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script>
    $(document).ready(function() {
        $('#attendanceTable').DataTable({
            "paging": true, // Enable pagination
            "lengthChange": true, // Allow user to change number of records per page
            "searching": true, // Enable search/filter
            "ordering": true, // Enable sorting
            "info": true, // Show "Showing X of Y entries"
            "autoWidth": false, // Disable auto width (better control)
            "responsive": true, // Make it responsive
            dom: 'Bfrtip', // THIS LINE is important to show buttons
            buttons: [{
                    extend: 'excelHtml5',
                    title: 'Attendance_Report'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Attendance_Report'
                },
                {
                    extend: 'print',
                    title: 'Attendance_Report'
                }
            ]
        });
    });
</script>
@endsection