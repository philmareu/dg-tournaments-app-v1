<template>
    <div>
        <div class="overlay uk-flex uk-flex-center uk-flex-middle">
            <div uk-spinner></div>
        </div>
        <div id="map" class="custom-popup">
            <div class="uk-position-small uk-position-bottom-center z-1000">
                <a href="https://www.algolia.com"><img src="/images/original/search-by-algolia.png" alt="Algolia Logo" width="125"></a>
            </div>
        </div>

        <div id="sidebar">
            <ul class="tabs uk-margin-remove-bottom uk-child-width-expand" uk-tab>
                <li class="uk-active"><a href=""><span id="stats-container"></span></a></li>
                <li><a href="" class="uk-text-bold"><span uk-icon="icon: settings;"></span><span class="uk-visdible@s uk-margin-small-left">Filters</span></a></li>
            </ul>

            <ul class="uk-switcher uk-margin-bottom">
                <li id="hits-panel">
                    <div class="uk-panel hits-wrapper">
                        <div id="pagination" class="uk-flex uk-flex-middle uk-flex-center uk-margin-small-top"></div>
                        <div id="hits"></div>
                    </div>
                </li>
                <li id="filters-panel">
                    <div class="filters-wrapper">

                        <save-search :user="user" :map="map" v-if="user !== null"></save-search>

                        <div v-if="user === null" class="uk-padding">
                            <p>Would you like to save your searches and get notified when new tournaments are posted that match your search criteria? <a
                                    href="#" @click.prevent="login">Login</a> or <a href="/register">create an account</a> and enjoy this great feature.</p>
                        </div>

                        <div class="filter uk-margin">
                            <h2 class="uk-margin-remove">Month Range</h2>
                            <div id="dates" class="facet uk-clearfix algolia-range-slider"></div>
                        </div>

                        <hr>

                        <div class="filter uk-margin">
                            <h2 class="uk-margin-remove">Class</h2>
                            <div id="class" class="facet uk-clearfix"></div>
                        </div>

                        <hr>

                        <div class="filter uk-margin">
                            <h2 class="uk-margin-remove">PDGA Tier</h2>
                            <div id="pdga-tiers" class="facet uk-clearfix"></div>
                        </div>

                        <hr>

                        <div class="filter uk-margin">
                            <h2 class="uk-margin-remove">Format</h2>
                            <div id="format" class="facet uk-clearfix"></div>
                        </div>

                        <hr>

                        <div class="filter uk-margin">
                            <h2 class="uk-margin-remove">Special Events</h2>
                            <div id="special-event-types" class="facet uk-clearfix"></div>
                        </div>

                        <hr>

                        <div class="filter uk-margin">
                            <h2 class="uk-margin-remove">Sanctioned</h2>
                            <div id="sanctioned" class="facet uk-clearfix"></div>
                        </div>

                        <hr>

                        <div class="filter uk-margin">
                            <h2 class="uk-margin-remove">Sponsorships</h2>
                            <div id="has_sponsorships" class="facet uk-clearfix"></div>
                        </div>

                        <hr>

                        <div class="filter uk-margin">
                            <h2 class="uk-margin-remove">Sponsorship Prices</h2>
                            <div id="price" class="facet uk-clearfix algolia-range-slider"></div>
                        </div>

                        <hr>

                        <div class="filter uk-margin">
                            <div id="clear-all" class="facet uk-clearfix"></div>
                        </div>

                    </div>
                </li>
            </ul>

            <footer id="default-footer" class="uk-padding-small uk-light">
                <footer-information></footer-information>
            </footer>
        </div>
    </div>
</template>

<script>

    import SaveSearch from './SaveSearch.vue';
    import User from '../../mixins/tournament/user';
    import FooterInformation from '../../components/FooterInformationSmall.vue';

    export default {

        props: [
            'user'
        ],

        mixins: [
            User
        ],

        components: {
            SaveSearch,
            FooterInformation
        },

        data: function() {
            return {
                mapboxToken: mapboxToken,
                map: []
            }
        },

        methods: {
            login: function () {
                UIkit.modal('#modal-login').toggle();
            }
        },

        beforeCreate: function () {

        },

        mounted: function() {

            let search = instantsearch({
                appId: algoliaAppId,
                apiKey: algoliaSearchKey,
                indexName: 'tournaments',
                urlSync: true,
                searchFunction: function (helper) {
                    $('.overlay').show();
                    helper.search();
                },
                searchParameters: {
                    aroundLatLngViaIP: false,
                    "query": ""
                }
            });

            /*
            |--------------------------------------------------------------------------
            | Widget - Price
            |--------------------------------------------------------------------------
            */

            search.addWidget(
                instantsearch.widgets.refinementList({
                    container: '#has_sponsorships',
                    attributeName: 'has_sponsorships',
                    operator: 'or',
                    showMore: true,
//                    sortBy: ['count:desc'],
                    templates: {
                        header: ''
                    },
                    autoHideContainer: false,
                    cssClasses: {
                        header: 'uk-margin-bottom',
                        item: 'uk-float-left uk-margin-small-right',
                        count: 'uk-badge uk-badge-notification',
                        checkbox: 'uk-checkbox'
                    }
                })
            );


            search.addWidget(
                instantsearch.widgets.rangeSlider({
                    container: '#price',
                    attributeName: 'sponsorship_prices',
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
            | Widget - Dates
            |--------------------------------------------------------------------------
            */

            search.addWidget(
                instantsearch.widgets.rangeSlider({
                    container: '#dates',
                    attributeName: 'year_month',
                    templates: {
                        header: ''
                    },
                    tooltips: {
                        format: function(rawValue) {
                            return rawValue.toString().substring(4) + '/' + rawValue.toString().substring(0, 4);
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

            search.addWidget({
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

                    $vm.map.addControl(new mapboxgl.NavigationControl());

                    if(typeof numericRefinements === 'undefined') {
                        axios.get('/cache/bounds')
                            .then(response => {

                                console.log(response.data);

                                let sw = new mapboxgl.LngLat(response.data.west, response.data.south);
                                let ne = new mapboxgl.LngLat(response.data.east, response.data.north);
                                let llb = new mapboxgl.LngLatBounds(sw, ne);

                                $vm.map.fitBounds(llb);
                            });
                    } else {
                        let sw = new mapboxgl.LngLat(numericRefinements.longitude['>'][0], numericRefinements.latitude['>'][0]);
                        let ne = new mapboxgl.LngLat(numericRefinements.longitude['<'][0], numericRefinements.latitude['<'][0]);
                        let llb = new mapboxgl.LngLatBounds(sw, ne);

                        $vm.map.fitBounds(llb);
                    }

                    // this.map.addControl(new mapboxgl.NavigationControl(), 'bottom-left');

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

                        let name = '<div class="name"><a href="' + marker.path + '" onClick="ga(\'send\', \'event\', \'Home Page\', \'View tournament\', \'Map marker\');">' + marker.name + '</a></div>';
                        let dates = '<div class="dates">' + marker.dates + '</div>';
                        let address = '<div class="address">' + marker.city + ', ' + marker.state_province + '</div>';

                        let popupHtml = '<div class="popup">' + name + address + dates + '</div>';

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

                        axios.put('/cache/bounds', {
                            south: bounds.getSouth(),
                            north: bounds.getNorth(),
                            west: bounds.getWest(),
                            east: bounds.getEast()
                            })
                            .then(function (response) {
                                console.log(response);
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
            | Widget - Class
            |--------------------------------------------------------------------------
            */

            search.addWidget(
                instantsearch.widgets.refinementList({
                    container: '#class',
                    attributeName: 'classes',
                    operator: 'or',
                    showMore: true,
                    sortBy: ['count:desc'],
                    templates: {
                        header: ''
                    },
                    autoHideContainer: false,
                    cssClasses: {
                        header: 'uk-margin-bottom',
                        item: 'uk-float-left uk-margin-small-right',
                        count: 'uk-badge uk-badge-notification',
                        checkbox: 'uk-checkbox'
                    }
                })
            );


            /*
            |--------------------------------------------------------------------------
            | Widget - Clear
            |--------------------------------------------------------------------------
            */

            search.addWidget(
                instantsearch.widgets.clearAll({
                    container: '#clear-all',
                    templates: {
                        link: 'Reset everything'
                    },
                    cssClasses: {
                        link: 'uk-button uk-button-default'
                    },
                    autoHideContainer: false
                })
            );

            /*
            |--------------------------------------------------------------------------
            | Widget - Format
            |--------------------------------------------------------------------------
            */

            search.addWidget(
                instantsearch.widgets.refinementList({
                    container: '#format',
                    attributeName: 'format',
                    operator: 'or',
                    showMore: true,
                    sortBy: ['count:desc'],
                    templates: {
                        header: ''
                    },
                    autoHideContainer: false,
                    cssClasses: {
                        header: 'uk-margin-bottom',
                        item: 'uk-float-left uk-margin-small-right',
                        count: 'uk-badge uk-badge-notification',
                        checkbox: 'uk-checkbox'
                    }
                })
            );

            /*
            |--------------------------------------------------------------------------
            | Widget - Hits
            |--------------------------------------------------------------------------
            */

            search.addWidget(
                instantsearch.widgets.hits({
                    container: '#hits',
                    templates: {
                        item: document.getElementById('hit-template').innerHTML,
                        empty: document.getElementById('no-results-template').innerHTML
                    }
                })
            );

            /*
            |--------------------------------------------------------------------------
            | Widget - Pagination
            |--------------------------------------------------------------------------
            */

            search.addWidget(
                instantsearch.widgets.pagination({
                    container: '#pagination',
                    maxPages: 10,
                    showFirstLast: false,
                    cssClasses: {
                        root: 'uk-pagination uk-margin-remove',
                        active: 'uk-active'
                    }
                })
            );

            /*
            |--------------------------------------------------------------------------
            | Widget - Stats
            |--------------------------------------------------------------------------
            */

            search.addWidget(
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
            | Widget - Tier
            |--------------------------------------------------------------------------
            */

            search.addWidget(
                instantsearch.widgets.refinementList({
                    container: '#pdga-tiers',
                    attributeName: 'pdga_tiers',
                    operator: 'or',
                    showMore: true,
                    sortBy: ['count:desc'],
                    templates: {
                        header: ''
                    },
                    autoHideContainer: false,
                    cssClasses: {
                        header: 'uk-margin-bottom',
                        item: 'uk-float-left uk-margin-small-right',
                        count: 'uk-badge uk-badge-notification',
                        checkbox: 'uk-checkbox'
                    }
                })
            );

            /*
            |--------------------------------------------------------------------------
            | Widget - Sanctioned
            |--------------------------------------------------------------------------
            */

            search.addWidget(
                instantsearch.widgets.refinementList({
                    container: '#sanctioned',
                    attributeName: 'sanctioned',
                    operator: 'or',
                    showMore: true,
                    sortBy: ['count:desc'],
                    templates: {
                        header: ''
                    },
                    autoHideContainer: false,
                    cssClasses: {
                        header: 'uk-margin-bottom',
                        item: 'uk-float-left uk-margin-small-right',
                        count: 'uk-badge uk-badge-notification',
                        checkbox: 'uk-checkbox'
                    }
                })
            );

            /*
            |--------------------------------------------------------------------------
            | Widget - Special Event Types
            |--------------------------------------------------------------------------
            */

            search.addWidget(
                instantsearch.widgets.refinementList({
                    container: '#special-event-types',
                    attributeName: 'special_event_types',
                    operator: 'or',
                    showMore: true,
                    sortBy: ['count:desc'],
                    templates: {
                        header: ''
                    },
                    autoHideContainer: false,
                    cssClasses: {
                        header: 'uk-margin-bottom',
                        item: 'uk-float-left uk-margin-small-right',
                        count: 'uk-badge uk-badge-notification',
                        checkbox: 'uk-checkbox'
                    }
                })
            );

            /*
            |--------------------------------------------------------------------------
            | Start Search
            |--------------------------------------------------------------------------
            */

            search.start();
            search.on('render', function() {
                $('.overlay').hide();
            });
        }
    }

</script>