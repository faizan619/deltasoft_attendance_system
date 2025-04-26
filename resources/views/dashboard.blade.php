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
        <!-- @if (Auth::user()->role == "admin")
        <li class="nav-item border border-danger rounded mx-3 bg-danger">
            <a class="nav-link text-light" href="{{ route('add_employee') }}">Add Employee</a>
        </li>
        @endif -->
        <div class="card-header bg-primary">
            <a class="text-light headerstyle" href="{{ route('admin_dashboard') }}">Employee Attendance Records</a> <span> | </span>
            <a class="text-light headerstyle" href="{{ route('emp_list') }}">Employee List</a><span> | </span>
        </div>
        <div class="card-body px-1 pb-0">
            <table class="table table-sm table-striped table-bordered">
                <thead class="">
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
            <div class="mb-3">
                {{$empData->links()}}
            </div>
        </div>
        <!-- <div class="card-footer">

            </div> -->

    </div>

    <!-- </div> -->

    <!-- Display latitude and longitude here -->
    <!-- <div id="location">
            <p>Latitude: <span id="latitude"></span></p>
            <p>Longitude: <span id="longitude"></span></p>
        </div> -->
</div>

<!-- Add JavaScript to get the user's location -->
<!-- <script>
        // Check if geolocation is available
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                // Display the latitude and longitude in the HTML
                document.getElementById("latitude").textContent = latitude;
                document.getElementById("longitude").textContent = longitude;
            }, function(error) {
                console.error("Error getting location: " + error.message);
                document.getElementById("location").innerHTML = "<p>Location could not be retrieved.</p>";
            });
        } else {
            console.log("Geolocation is not supported by this browser.");
            document.getElementById("location").innerHTML = "<p>Geolocation is not supported by your browser.</p>";
        }
    </script> -->
@endsection