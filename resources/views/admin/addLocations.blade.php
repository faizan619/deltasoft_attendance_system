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

    #locationform {
        display: none;
    }
</style>
@endsection

@section('title')
Location
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
            <a class="text-light headerstyle" href="{{ route('emp_list') }}">Employee List</a> <span> | </span> <a href="{{ route('viewPresentLocations') }}" class="text-light headerstyle underlinestyle">Locations</a>
        </div>
        <div class="card-body p-3">
            <div class="">
                <button class="btn btn-sm btn-danger mb-3" id="showlocationform">Add Office</button>
                <div id="locationform" class="mb-3">
                    <form action="{{ route('savePresentLocations') }}" method="POST" class="row">
                        @csrf
                        <div class="col-md-6">
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Office Name">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="latitude" id="latitude" class="form-control" placeholder="Enter Latitude" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="longitude" id="longitude" class="form-control" placeholder="Enter Longitude" required>
                        </div>
                        <div class="col-md-12 text-center">
                            <!-- Make this button type="button" to prevent accidental form submission -->
                            <button class="btn btn-danger mt-3" type="button" id="currentlocationfetch" onclick="handleLocation()">Get Current Location</button>
                            <button class="btn btn-success mt-3" type="submit">Save Data</button>
                        </div>
                        <p class="mt-3"></p>
                    </form>

                </div>
            </div>
            <p>
            <form action="{{ route('viewPresentLocations') }}" class="row">
                <div class="col-md-6">
                    <input type="text" name="office_name" id="office_name" class="form-control form-control-sm" placeholder="Enter Office Name">
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-sm btn-primary mr-2"><i class="fas fa-search"></i> Search</button>
                    <a href="{{ route('viewPresentLocations') }}" class="btn btn-sm btn-secondary">Reset</a>
                </div>
            </form>
            </p>
            <table id="attendanceTable" class="table table-sm table-striped table-bordered">
                <thead class="">
                    <tr>
                        <th>Office Name</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Map</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($locs as $loc)
                    <tr>
                        <td>{{$loc->name}}</td>
                        <td>{{$loc->latitude}}</td>
                        <td>{{$loc->longitude}}</td>
                        <td>
                            <a href="https://www.google.com/maps?q={{ $loc->latitude }},{{ $loc->longitude }}" target="_blank" class="btn btn-primary btn-sm">
                                View in Map
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="">No Data Found</td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
            <p>
                {{$locs->links()}}
            </p>
            <!--  -->
        </div>

    </div>

</div>

@endsection

@section('scriptTag')
<script>
    $(document).ready(function() {
        $('#showlocationform').click(function() {
            if ($('#locationform').css('display') == 'none') {
                $('#locationform').css('display', 'block');
            } else {
                $('#locationform').css('display', 'none');
            }
        });
    });


    function handleLocation() {
        if (navigator.geolocation) {
            document.getElementById('currentlocationfetch').innerText = "Fetching...";

            navigator.geolocation.getCurrentPosition(function(position) {
                // Get current latitude and longitude
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;

                // Set the values into the input fields
                document.getElementById('latitude').value = latitude;
                document.getElementById('longitude').value = longitude;

                // Update button text back to original
                document.getElementById('currentlocationfetch').innerText = "Get Current Location";

            }, function(error) {
                document.getElementById('currentlocationfetch').innerText = "Get Current Location";
                alert('Error getting location: ' + error.message);
            });
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    }
</script>
@endsection