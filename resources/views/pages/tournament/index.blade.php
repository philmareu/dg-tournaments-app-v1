@extends('layouts.default')

@section('title')
    {{ $tournament->name }}
@endsection

@include('partials.mapbox')

@section('meta')
    <meta name="description" content="A disc golf tournament event located in {{ $tournament->city }}, {{ $tournament->state_province }} on {{ $tournament->dateSpan->formattedDate() }}.">
@endsection

@push('scripts')
    <script>
        let markers = {!! json_encode($markers) !!};
        let bounds = {!! json_encode($bounds) !!};
    </script>
@endpush

@section('content')
    @parent

    <div id="layout-side-map">
        <div id="map">
            <a href="#" class="uk-icon-button shrink-expand-buttons" uk-icon="icon: plus" id="map-expand-button" onclick="expandMap(event)"></a>
            <a href="#" class="uk-icon-button shrink-expand-buttons" uk-icon="icon: minus" id="map-shrink-button" onclick="shrinkMap(event)"></a>
        </div>

        <div id="side">
            <div class="uk-padding-small" id="tournament-details">
                @include('pages.tournament.header')

                <ul class="uk-tab uk-child-width-expand uk-margin-remove-top">
                    <li class="uk-active"><a href="#"><span uk-icon="icon: info; ratio: 1"></span><br>Info</a></li>
                    <li><a href="{{ url($tournament->path . '/sponsors') }}"><span uk-icon="icon: star; ratio: 1"></span><br>Sponsors</a></li>
                </ul>

                @include('pages.tournament.info')
            </div>

            @include('partials.footer', ['size' => 'small'])
        </div>
    </div>

@endsection

@section('bottom-body')

    <script>
        mapboxgl.accessToken = mapboxToken;
        let tournamentMap = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/philmartinez/ciy6gpair00322spcgmwu0y2w',
            center: [bounds.center[1], bounds.center[0]],
            zoom: 14
        });

        tournamentMap.addControl(new mapboxgl.NavigationControl());

        let sw = new mapboxgl.LngLat(bounds.longitude.min, bounds.latitude.min);
        let ne = new mapboxgl.LngLat(bounds.longitude.max, bounds.latitude.max);
        let llb = new mapboxgl.LngLatBounds(sw, ne);

        tournamentMap.fitBounds(llb);

        if(markers.headquarters.length > 0) {
            let popupHtml = '<div class="popup">' + markers.headquarters.popup + '</div>';

            let popup = new mapboxgl.Popup({
                offset: [0, -30],
                closeButton: false
            }).setHTML(popupHtml);

            let el = document.createElement('a');
            el.setAttribute("uk-icon", "icon: location");
            el.className = "map-marker";

            new mapboxgl.Marker(el, {
                offset: [0, -10]
            })
                .setLngLat([markers.headquarters.longitude, markers.headquarters.latitude])
                .setPopup(popup)
                .addTo(tournamentMap);
        }

        addCourseMarker = function (marker) {

            let popupHtml = '<div class="popup">' + marker.popup + '</div>';

            let popup = new mapboxgl.Popup({
                offset: [0, -30],
                closeButton: false
            }).setHTML(popupHtml);

            let el = document.createElement('a');
            el.setAttribute("uk-icon", "icon: location");
            el.className = "map-marker";

            new mapboxgl.Marker(el, {
                offset: [-7, -28]
            })
                .setLngLat([marker.longitude, marker.latitude])
                .setPopup(popup)
                .addTo(tournamentMap);
        };

        if (markers.courses.length > 0) {
            for (let i = 0, tot = markers.courses.length; i < tot; i++) {
                addCourseMarker(markers.courses[i]);
            }
        }

        moveMap = function (latitude, longitude) {
            tournamentMap.panTo([longitude, latitude]);
        };

        expandMap = function (event) {
            event.preventDefault();
            $('#map').css('height', '100%');
            $('.shrink-expand-buttons').toggle();
            tournamentMap.resize();
        };

        shrinkMap = function (event) {
            event.preventDefault();
            $('#map').css('height', '30%');
            $('.shrink-expand-buttons').toggle();
            tournamentMap.resize();
        }

    </script>

@endsection