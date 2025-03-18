@extends('layouts.app')

@section('content')
<div class="container-fluid">
    Welcome to admission {{ Auth::user()->username }}

    <!-- Display message about the user's location -->
    <div id="location-status">
        <p id="location-message">Checking your location...</p>
    </div>
</div>

<!-- Add JavaScript to get the user's location and check if within range -->
<script>
    // Define office location and range (in meters)
    const officeLatitude = 19.381066; // Replace with actual office latitude
    const officeLongitude = 72.826853; // Replace with actual office longitude
    const officeRadius = 500; // Radius in meters (100 meters for example)

    // Function to calculate the distance between two lat/lon points using the Haversine formula
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Radius of Earth in kilometers
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        const distance = R * c * 1000; // Distance in meters
        return distance;
    }

    // Check if geolocation is available and get user's location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const userLatitude = position.coords.latitude;
            const userLongitude = position.coords.longitude;

            // Calculate the distance from the office
            const distance = calculateDistance(userLatitude, userLongitude, officeLatitude, officeLongitude);

            // Show the result based on whether the user is within range
            if (distance <= officeRadius) {
                document.getElementById("location-message").textContent = "You are within the office range. Attendance marked as present.";
            } else {
                document.getElementById("location-message").textContent = "You are outside the office range.";
            }
        }, function(error) {
            console.error("Error getting location: " + error.message);
            document.getElementById("location-message").textContent = "Unable to retrieve location.";
        });
    } else {
        document.getElementById("location-message").textContent = "Geolocation is not supported by your browser.";
    }
</script>
@endsection