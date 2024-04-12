<template>
    <div>
        <!--<div id="map" class="custom-popup"></div>-->

        <div id="sidebar">
            <div v-if="user !== null">
                <div class="uk-padding-small">
                    <div class="uk-grid">
                        <div class="uk-width-1-4">
                            <img :src="url('images/small/' + user.image.filename)" alt="" class="uk-border-circle">
                        </div>
                        <div class="uk-width-3-4">
                            <div class="username">{{ user.name }}</div>
                            <div v-if="user.pdga_number !== null"><a :href="[ 'https://pdga.com/player/' + user.pdga_number ]" target="_blank">PDGA# {{ user.pdga_number }}</a></div>
                            <div><a :href="url('account/profile')">Edit Profile</a></div>
                        </div>
                    </div>
                </div>

                <ul class="uk-child-width-expand uk-margin-remove-top" uk-tab>
                    <li><a href="#"><span uk-icon="icon: list; ratio: 1"></span><br>Activity</a></li>
                    <li><a href="#"><span uk-icon="icon: location; ratio: 1"></span><br>Following</a></li>
                </ul>

                <ul class="uk-switcher uk-margin uk-padding-small">
                    <li>
                        <div v-if="feed.length > 0" v-for="activity in feed" v-html="activity"></div>
                        <div v-if="feed.length == 0" class="uk-text-center uk-text-muted uk-padding">
                            <span uk-icon="icon: list; ratio: 4;"></span>
                            <p>No Activities.</p>
                            <p>This tab will show recent activities and notifications relating to tournaments you follow and your notifications settings. The map will show all the upcoming tournaments you follow.</p>
                            <a :href="url('search')" class="uk-button uk-button-primary">Search Tournaments</a>
                        </div>
                    </li>
                    <li>
                        <div v-for="follow in user.following_tournaments" class="uk-text-truncate">
                            <div class="uk-grid uk-grid-small">
                                <div class="uk-width-1-6">
                                    <img :src="url('images/poster-small/' + follow.resource.poster.filename)" alt="Round Image" class="uk-border-circle uk-margin">
                                </div>
                                <div class="uk-width-5-6">
                                    <a :href="follow.resource.path" v-text="follow.resource.name" class="uk-padding-remove"></a>
                                    <div class="timestamp" v-text="updatedAt(follow.resource.updated_at)"></div>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </li>
                </ul>
            </div>

            <div v-else="">
                <!--<header class="uk-padding-small uk-text-center">-->
                    <!--<h1>Recent Activity</h1>-->
                    <!--<p>Stay informed! Customize this recent activity feed by following tournaments. <a :href="url('about')">Learn more about DG Tournaments.</a></p>-->
                    <!--<a :href="url('search')" class="uk-button uk-button-primary uk-button-small">Search Tournaments</a>-->
                <!--</header>-->

                <div>
                    <div v-if="feed.length > 0" v-for="activity in feed" v-html="activity"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import User from '../../mixins/tournament/user';
    import FooterInformation from '../../components/FooterInformationSmall.vue';
    import Helpers from '../../mixins/helpers';

    export default {

        props: [
            'user',
            'markers',
            'feed'
        ],

        mixins: [
            User,
            Helpers
        ],

        components: {
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
            },
            addMarkers: function () {

                if (this.markers.length > 0) {
                    for (let i = 0, tot = this.markers.length; i < tot; i++) {
                        this.addMarker(this.markers[i]);
                    }
                }
            },
            addMarker: function (marker) {


                let popup = new mapboxgl.Popup({
                    offset: [0, -30],
                    closeButton: false
                }).setHTML(marker.popup);

                let el = document.createElement('a');
                el.setAttribute("uk-icon", "icon: location");
                el.className = "map-marker";

                new mapboxgl.Marker(el, {
                        offset: [0, -10]
                    })
                    .setLngLat([marker.longitude, marker.latitude])
                    .setPopup(popup)
                    .addTo(this.map);
            },
            updatedAt: function (date) {

                let t = moment(date, 'YYYY-MM-DD HH:mm:ss');

                return "Updated " + t.add(moment().utcOffset(), 'minutes').fromNow();
            }
        },

        beforeCreate: function () {

            // convert UTC to current timezone
            // then .fromNow

            let date = '2018-01-15 14:12:00';

            // convert
            console.log(moment().utcOffset());

            console.log(moment().diff().format('Z'));
        },

//        watch: {
//            user: function () {
//                if(this.user !== null) {
//                    axios.get('/user/feed')
//                        .then(response => {
//                            this.feed = response.data;
//                        });
//                }
//            }
//        },

        mounted: function() {

            /*
            |--------------------------------------------------------------------------
            | Widget - Map
            |--------------------------------------------------------------------------
            */

            mapboxgl.accessToken = this.mapboxToken;
            this.map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/philmartinez/ciy6gpair00322spcgmwu0y2w',
                center: [-74.50, 40],
                zoom: 4
            });

            this.map.addControl(new mapboxgl.NavigationControl());

            this.addMarkers();

        }
    }

</script>