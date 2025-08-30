@extends('layouts.driver')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Route Map</h4>
                </div>
                <div class="card-body">
                    @if(isset($route) && isset($coordinates))
                        <div id="map" style="height: 400px; width: 100%;"></div>
                        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}"></script>
                        <script>
                            function initMap() {
                                var school = {lat: {{ $coordinates['school']['lat'] }}, lng: {{ $coordinates['school']['lng'] }}};
                                var stops = @json($coordinates['stops']);
                                var map = new google.maps.Map(document.getElementById('map'), {
                                    zoom: 12,
                                    center: school
                                });
                                var schoolMarker = new google.maps.Marker({
                                    position: school,
                                    map: map,
                                    title: 'School',
                                    icon: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png'
                                });
                                stops.forEach(function(stop) {
                                    var marker = new google.maps.Marker({
                                        position: {lat: stop.lat, lng: stop.lng},
                                        map: map,
                                        title: stop.name + ' (' + stop.address + ')'
                                    });
                                });
                            }
                            window.onload = initMap;
                        </script>
                    @else
                        <p>No map data available for this route.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
