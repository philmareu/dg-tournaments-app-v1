@if(env('LOAD_MAPBOX', true) && env('ONLINE', true))

    @push('css')
        <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.41.0/mapbox-gl.css' rel='stylesheet' />
    @endpush

    @push('scripts')
        <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.42.2/mapbox-gl.js'></script>
    @endpush

@endif