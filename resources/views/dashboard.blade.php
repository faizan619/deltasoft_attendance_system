@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        Welcome to admission {{ Auth::user()->username }}

        <!-- Display latitude and longitude here -->
        <div id="location">
            <p>Latitude: <span id="latitude"></span></p>
            <p>Longitude: <span id="longitude"></span></p>
        </div>
    </div>

    <!-- Add JavaScript to get the user's location -->
    <script>
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
    </script>
@endsection
