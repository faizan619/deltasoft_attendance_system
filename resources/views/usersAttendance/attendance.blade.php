@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success my-2">
            {{ session('success') }}
        </div>
    @endif

    Welcome to admission {{ Auth::user()->username }}

    <!-- Display message about the user's location -->
    <div id="location-status">
        <p id="location-message">Checking your location...</p>
        <input type="hidden" name="lon" id="lon11" value="{{ $loc->longitude }}">
        <input type="hidden" name="lat" id="lat11" value="{{ $loc->latitude }}">
        <p id="available-for-attendance"></p>
    </div>
</div>

<script>
    // const officeLatitude = 19.381066;
    // const officeLongitude = 72.826853;

    const officeLatitude = document.getElementById('lon11').value;
    // console.log('lat : ',officeLatitude)
    const officeLongitude = document.getElementById('lat11').value;
    // console.log('lon : ',officeLongitude)

    
    
    const officeRadius = 500; // meters

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c * 1000; // meters
    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const userLatitude = position.coords.latitude;
            const userLongitude = position.coords.longitude;

            const distance = calculateDistance(userLatitude, userLongitude, officeLatitude, officeLongitude);

            if (distance <= officeRadius) {
                document.getElementById("location-message").textContent = "You are within the office range. Marking attendance...";

                // Build form dynamically and auto-submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('attendance.store') }}";

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                const dateInput = document.createElement('input');
                dateInput.type = 'datetime-local';
                dateInput.name = 'attendance_time';
                dateInput.style.display = 'none';

                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                dateInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;

                form.appendChild(dateInput);
                document.body.appendChild(form);
                form.submit();
            } else {
                document.getElementById("location-message").textContent = "You are outside the office range.";
                // Build form dynamically and auto-submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('attendance.store') }}";

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                const dateInput = document.createElement('input');
                dateInput.type = 'datetime-local';
                dateInput.name = 'attendance_checkout_time';
                dateInput.style.display = 'none';

                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                dateInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;

                form.appendChild(dateInput);
                document.body.appendChild(form);
                form.submit();
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