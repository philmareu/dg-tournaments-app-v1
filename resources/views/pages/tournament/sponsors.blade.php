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
                    <li><a href="{{ url($tournament->path) }}"><span uk-icon="icon: info; ratio: 1"></span><br>Info</a></li>
                    <li class="uk-active"><a href="#"><span uk-icon="icon: star; ratio: 1"></span><br>Sponsors</a></li>
                </ul>

                @foreach($tournament->sponsorships as $sponsorship)
                    <h2>{{ $sponsorship->title }} Sponsors</h2>

                    <div class="uk-child-width-1-3" uk-grid>
                        @foreach($sponsorship->tournamentSponsors as $tournamentSponsor)
                            @if(is_null($tournamentSponsor->sponsor->upload_id))
                                <div class="uk-flex uk-flex-middle uk-text-center">
                                    @if(is_null($tournamentSponsor->sponsor->url))
                                        {{ $tournamentSponsor->sponsor->title }}
                                    @else
                                        <a href="{{ $tournamentSponsor->sponsor->url }}">{{ $tournamentSponsor->sponsor->title }}</a>
                                    @endif
                                </div>
                            @else
                                <div>
                                    @if(is_null($tournamentSponsor->sponsor->url))
                                        <img src="{{ url('images/small/' . $tournamentSponsor->sponsor->logo->filename) }}" alt="Sponsor Title">
                                    @else
                                        <a href="{{ $tournamentSponsor->sponsor->url }}" target="_blank"><img src="{{ url('images/small/' . $tournamentSponsor->sponsor->logo->filename) }}" alt="Sponsor Title"></a>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
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
            center: [markers.headquarters.longitude, markers.headquarters.latitude],
            zoom: 14
        });

        let sw = new mapboxgl.LngLat(bounds.longitude.min, bounds.latitude.min);
        let ne = new mapboxgl.LngLat(bounds.longitude.max, bounds.latitude.max);
        let llb = new mapboxgl.LngLatBounds(sw, ne);

        tournamentMap.fitBounds(llb);

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