<template>
    <div>
        <div id="map" class="custom-popup"></div>

        <div id="sidebar">
            <ul class="tabs uk-margin-remove-bottom uk-child-width-expand" uk-tab>
                <li><a href=""><span id="stats-container"></span></a></li>
                <li><a href="" class="uk-text-bold"><span uk-icon="icon: settings;"></span><span class="uk-visdible@s uk-margin-small-left">Filter</span></a></li>
            </ul>

            <ul class="uk-switcher uk-margin-bottom">
                <li id="hits-panel">
                    <div class="uk-panel hits-wrapper">
                        <div id="hits"></div>
                    </div>

                    <footer>
                        <div class="uk-text-center">
                            &copy; 2017 DG Tournaments
                        </div>
                    </footer>
                </li>
                <li id="filters-panel">
                    <div class="filters-wrapper">
                        <h3>Cost Range</h3>
                        <div id="cost" class="facet uk-clearfix"></div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>

    export default {

        props: [],

        mixins: [

        ],

        components: {

        },

        data: function() {
            return {
                search: instantsearch({
                    appId: algoliaAppId,
                    apiKey: algoliaSearchKey,
                    indexName: 'sponsorships',
                    urlSync: true,
                    searchParameters: {
                        aroundLatLngViaIP: false,
                        "query": ""
                    }
                }),
                mapboxToken: mapboxToken,
                map: []
            }
        },

        methods: {

        },

        beforeCreate: function () {

        },

        mounted: function() {

            /*
            |--------------------------------------------------------------------------
            | Widget - Cost
            |--------------------------------------------------------------------------
            */

            this.search.addWidget(
                instantsearch.widgets.rangeSlider({
                    container: '#cost',
                    attributeName: 'cost_in_dollars',
                    templates: {
                        header: ''
                    },
                    tooltips: {
                        format: function(rawValue) {
                            return '$' + rawValue;
                        }
                    },
                    pips: false,
                    autoHideContainer: false,
                })
            );

            /*
            |--------------------------------------------------------------------------
            | Widget - Map
            |--------------------------------------------------------------------------
            */

            let $vm = this;

            this.search.addWidget({
                markers: [],
                init: function (initOptions) {

                    let numericRefinements = initOptions.instantSearchInstance.searchParameters.numericRefinements;

                    mapboxgl.accessToken = $vm.mapboxToken;
                    $vm.map = new mapboxgl.Map({
                        container: 'map',
                        style: 'mapbox://styles/philmartinez/ciy6gpair00322spcgmwu0y2w',
                        center: [-74.50, 40],
                        zoom: 4
                    });

//                    if(typeof numericRefinements === 'undefined') {
//                        axios.get('/cache/bounds/sponsorships')
//                            .then(response => {
//
//                                let sw = new mapboxgl.LngLat(response.data.west, response.data.south);
//                                let ne = new mapboxgl.LngLat(response.data.east, response.data.north);
//                                let llb = new mapboxgl.LngLatBounds(sw, ne);
//
//                                $vm.map.fitBounds(llb);
//                            });
//                    } else {
//
//                        let sw = new mapboxgl.LngLat(numericRefinements.longitude['>'][0], numericRefinements.latitude['>'][0]);
//                        let ne = new mapboxgl.LngLat(numericRefinements.longitude['<'][0], numericRefinements.latitude['<'][0]);
//                        let llb = new mapboxgl.LngLatBounds(sw, ne);
//
//                        $vm.map.fitBounds(llb);
//                    }

                    // this.map.addControl(new mapbox.NavigationControl(), 'bottom-left');

                    this.addMarkers = function (markers) {

                        // Remove markers
                        this.markers.forEach(function (doomedMarker) {
                            doomedMarker.remove();
                        });

                        // Reset list of markers
                        this.markers = [];

                        if (markers.length > 0) {
                            for (let i = 0, tot = markers.length; i < tot; i++) {
                                this.addMarker(markers[i]);
                            }
                        }
                    };

                    this.addMarker = function (marker) {

                        let name = '<div class="name"><a href="' + marker.tournament.path + '" onClick="ga(\'send\', \'event\', \'Tournaments Sponsorships Map\', \'View tournament\', \'Map marker\');">' + marker.tournament.name + '</a></div>';
                        let title = '<div class="title">' + marker.title + '($' + marker.cost_in_dollars + ')' + '</div>';
                        let popupHtml = '<div class="popup">' + name + title + '</div>';

                        let popup = new mapboxgl.Popup({
                            offset: [0, -30],
                            closeButton: false
                        }).setHTML(popupHtml);

                        let el = document.createElement('a');
                        el.setAttribute("uk-icon", "icon: location");
                        el.className = "map-marker";

                        let newMarker = new mapboxgl.Marker(el, {
                            offset: [0, -10]
                        })
                            .setLngLat([marker.longitude, marker.latitude])
                            .setPopup(popup)
                            .addTo($vm.map);

                        this.markers.push(newMarker);
                    };
                },
                render: function (params) {
                    let helper = params.helper;
                    let results = params.results;

                    $vm.map.once('moveend', function (event) {
                        let bounds = this.getBounds();

                        axios.put('/cache/bounds/sponsorships', {
                            south: bounds.getSouth(),
                            north: bounds.getNorth(),
                            west: bounds.getWest(),
                            east: bounds.getEast()
                        })
                            .then(function (response) {

                            });

                        helper.removeNumericRefinement('latitude', '>');
                        helper.removeNumericRefinement('latitude', '<');
                        helper.removeNumericRefinement('longitude', '>');
                        helper.removeNumericRefinement('longitude', '<');

                        helper.addNumericRefinement('latitude', '>', bounds.getSouth());
                        helper.addNumericRefinement('latitude', '<', bounds.getNorth());
                        helper.addNumericRefinement('longitude', '>', bounds.getWest());
                        helper.addNumericRefinement('longitude', '<', bounds.getEast());

                        helper.search();
                    });

                    this.addMarkers(results.hits);
                }
            });

            /*
            |--------------------------------------------------------------------------
            | Widget - Hits
            |--------------------------------------------------------------------------
            */

            this.search.addWidget(
                instantsearch.widgets.hits({
                    container: '#hits',
                    hitsPerPage: 50,
                    templates: {
                        item: document.getElementById('hit-template').innerHTML,
                        empty: document.getElementById('no-results-template').innerHTML
                    }
                })
            );

            /*
            |--------------------------------------------------------------------------
            | Widget - Stats
            |--------------------------------------------------------------------------
            */

            this.search.addWidget(
                instantsearch.widgets.stats({
                    container: '#stats-container',
                    cssClasses: {
                        time: 'uk-hidden'
                    },
                    templates: {
                        body: function(stats) {
                            return '<span uk-icon="icon: list;" class="uk-margin-small-right"></span>' + stats.nbHits;
                        }
                    }
                })
            );

            /*
            |--------------------------------------------------------------------------
            | Start Search
            |--------------------------------------------------------------------------
            */

            this.search.start();
            this.search.once('render', function(){  });

            /*
            |--------------------------------------------------------------------------
            | Window Adjustments
            |--------------------------------------------------------------------------
            */

            $(function() {

                let windowHeight = parseInt($(window).height());
                let menuHeight   = parseInt($('nav').css('height'));
                let footerHeight = parseInt($('footer').css('height'));

                let hitsHeight = (windowHeight - menuHeight - footerHeight);

                $('#sponsorship-map-sidebar').css('height', hitsHeight);
            });
        }
    }

</script>