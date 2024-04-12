<template>
    <div class="location-map-wrapper">
        <div :id="id" class="location-map"></div>
    </div>
</template>

<script>
    export default {

        props: [
            'id',
            'latitude',
            'longitude'
        ],

        data: function() {
            return {
                mapboxToken: mapboxToken,
                map: [],
                marker: null
            }
        },

        watch: {
            latitude: function () {
                this.map.setCenter([this.longitude, this.latitude]);
                this.marker.setLngLat(new mapboxgl.LngLat(this.longitude, this.latitude));
            }
        },

        mounted: function() {
            mapboxgl.accessToken = this.mapboxToken;
            this.map = new mapboxgl.Map({
                container: this.id,
                style: 'mapbox://styles/philmartinez/ciy6gpair00322spcgmwu0y2w',
                center: [this.longitude, this.latitude],
                zoom: 14
            });

            this.map.addControl(new mapboxgl.NavigationControl());

            let el = document.createElement('a');
            el.setAttribute("uk-icon", "icon: location");
            el.className = "map-marker";

            this.marker = new mapboxgl.Marker(el, {
                offset: [0, -10]
            }).setLngLat([this.longitude, this.latitude])
                .addTo(this.map);

            let $vm = this;
            this.map.on('click', function(event) {
                $vm.marker.setLngLat(event.lngLat);
                $vm.$emit('location-updated', event.lngLat);
            });
        },

        methods: {

        }

    }

</script>