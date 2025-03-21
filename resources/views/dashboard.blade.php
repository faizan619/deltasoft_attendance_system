@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <p class="text-light fs-2 text-center my-3 py-2"><i> Welcome to Deltasoft Attendance Application</i></p>

    <!-- <div class="container-md-fluid mt-3"> -->
        <div class="card col-md-8 mx-auto">
            <div class="card-header bg-primary text-light">Employee Records</div>
            <div class="card-body px-1 pb-0">
                <table class="table table-sm table-striped table-bordered">
                    <thead class="">
                        <tr>
                            <th>Emp Name</th>
                            <th>Emp Email</th>
                            <th>Emp Mobile</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($empData as $emp)
                        <tr>
                            <td>{{$emp->username}}</td>
                            <td>{{$emp->email}}</td>
                            <td>{{$emp->mobile}}</td>
                            <td>{{$emp->attendanceLogs->checkIn}}</td>
                            <td>{{$emp->attendanceLogs->checkOut}}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="">No Data Found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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