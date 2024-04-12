<template>
    <div>
        <nav class="uk-navbar-container" id="primary" uk-navbar>


            <!--Left small navigation-->
            <div class="uk-navbar-left uk-hidden@s">
                <a class="uk-navbar-toggle" href="#offcanvas-nav" uk-toggle="target: #offcanvas-nav"><span uk-navbar-toggle-icon></span></a>
                <span class="uk-light">DG Tournaments</span>
            </div>


            <!--Left large navigation-->
            <div class="uk-navbar-left uk-visible@s uk-light">
                <ul class="uk-navbar-nav">
                    <a :href="url('')" class="uk-navbar-item logo uk-link-reset">
                        <span uk-icon="icon: dgt; ratio: .2;" class="uk-margin-small-right"></span>DG Tournaments
                    </a>
                    <li :class="[ active === 'home' ? 'uk-active' : '']"><a :href="url('')"><span class="uk-margin-small-right" uk-icon="icon: home;"></span>Home</a></li>
                    <li :class="[ active === 'search' ? 'uk-active' : '']"><a :href="url('search')"><span class="uk-margin-small-right" uk-icon="icon: search;"></span>Search</a></li>
                    <li :class="[ active === 'manage' ? 'uk-active' : '']"><a :href="url('manage')"><span class="uk-margin-small-right" uk-icon="icon: pencil;"></span>Manage</a></li>
                </ul>
            </div>


            <!--Right large navigation-->
            <div class="uk-navbar-right">

                <!--Global search for large screens-->
                <div class="uk-navbar-item uk-visible@s">
                    <global-search id="aa-search-input"></global-search>
                </div>

                <ul class="uk-navbar-nav actions">

                    <div class="uk-navbar-item uk-visible@s" v-if="user === null">
                        <a :href="url('manage/submit')" class="uk-button uk-button-primary uk-button-small">Submit Tournament</a>
                    </div>

                    <li class="uk-visible@s" v-if="user === null">
                        <a :href="url('login')">Log In</a>
                    </li>

                    <!--<div class="uk-navbar-item uk-visible@s" v-if="user === null">-->
                        <!--<a :href="url('register')" class="">Sign Up</a>-->
                    <!--</div>-->

                    <shopping-cart :order="order"
                                   v-on:order-updated="updateOrder"
                    ></shopping-cart>

                    <li class="uk-hidden@s" v-if="user === null">
                        <a href="#offcanvas-guest" uk-toggle><span uk-icon="icon: user"></span></a>
                    </li>

                    <li v-if="user !== null">
                        <a href="#"><span class="uk-icon uk-icon-image uk-border-circle" :style="userImageUrl()"></span><span class="uk-visible@s uk-margin-small-left">{{ user.name }}</span></a>
                        <div class="uk-navbar-dropdown" uk-dropdown="mode: click">
                            <ul class="uk-nav uk-navbar-dropdown-nav">
                                <li><a :href="url('account/profile')" class="uk-padding-remove"><span class="uk-margin-small-right" uk-icon="icon: user; ratio: .8;"></span>Profile</a></li>
                                <li><a :href="url('account/settings')" class="uk-padding-remove"><span class="uk-margin-small-right" uk-icon="icon: cog; ratio: .8;"></span>Settings</a></li>
                                <li><a :href="url('account/memberships')" class="uk-padding-remove"><span class="uk-margin-small-right" uk-icon="icon: social; ratio: .8;"></span>Memberships</a></li>
                                <li><a :href="url('account/orders')" class="uk-padding-remove"><span class="uk-margin-small-right" uk-icon="icon: cart; ratio: .8;"></span>Orders</a></li>
                                <li><a :href="url('account/billing')" class="uk-padding-remove"><span class="uk-margin-small-right" uk-icon="icon: credit-card; ratio: .8;"></span>Billing</a></li>
                                <li><a :href="url('account/notifications')" class="uk-padding-remove"><span class="uk-margin-small-right" uk-icon="icon: mail; ratio: .8;"></span>Notifications</a></li>

                                <li class="uk-nav-divider"></li>
                                <li><a href="#" @click.prevent="logout" class="uk-padding-remove"><span class="uk-margin-small-right" uk-icon="icon: sign-out; ratio: .8;"></span>Log Out</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>

            </div>
        </nav>

        <!--Offcanvas for small screen left navigation-->
        <div id="offcanvas-nav" uk-offcanvas="overlay: true">
            <div class="uk-offcanvas-bar">
                <span uk-icon="icon: dgt; ratio: .15;" class="uk-margin-small-right uk-margin"></span>DG Tournaments
                <global-search id="aa-search-input-small"></global-search>
                <ul class="uk-nav uk-nav-default uk-margin-top">
                    <li><a :href="url('')"><span class="uk-margin-small-right" uk-icon="icon: home; ration: .7;"></span>Home</a></li>
                    <li><a :href="url('search')"><span class="uk-margin-small-right" uk-icon="icon: search; ration: .7;"></span>Search</a></li>
                    <li><a :href="url('manage')"><span class="uk-margin-small-right" uk-icon="icon: pencil; ration: .7;"></span>Manage</a></li>

                    <li class="uk-nav-header">Connect</li>
                    <li><a href="https://twitter.com/dgtournaments1"><span class="uk-margin-small-right" uk-icon="icon: twitter; ratio: .7;"></span>Twitter</a></li>
                    <li><a href="https://instagram.com/dgtournaments"><span class="uk-margin-small-right" uk-icon="icon: instagram; ratio: .7;"></span>Instagram</a></li>
                    <li><a href="https://facebook.com/dgtournaments"><span class="uk-margin-small-right" uk-icon="icon: facebook; ratio: .7;"></span>Facebook</a></li>

                    <li class="uk-nav-header">DG Tournaments</li>
                    <li class=""><a :href="url('about')"><span class="uk-margin-small-right" uk-icon="icon: info; ratio: .70"></span>About DGT</a></li>
                    <li class=""><a :href="url('blog')"><span class="uk-margin-small-right" uk-icon="icon: pencil; ratio: .70"></span>Blog</a></li>
                    <li class=""><a :href="url('contact-us')"><span class="uk-margin-small-right" uk-icon="icon: mail; ratio: .70"></span>Contact Us</a></li>
                    <li class=""><a :href="url('terms-of-service')"><span class="uk-margin-small-right" uk-icon="icon: file; ratio: .70"></span>Terms of Service</a></li>
                    <li class=""><a :href="url('privacy-policy')"><span class="uk-margin-small-right" uk-icon="icon: file; ratio: .70"></span>Privacy Policy</a></li>
                </ul>
            </div>
        </div>


        <!--Offcanvas for small screen guest right navigation-->
        <div id="offcanvas-guest" uk-offcanvas="overlay: true; flip: true;">
            <div class="uk-offcanvas-bar">
                <p class="uk-text-center">Would you like to enhance your DG Tournaments experience? Login or create an account and take advantage of many great features.</p>
                <hr>
                <div class="uk-text-center uk-grid-collapse uk-flex uk-flex-middle" uk-grid>
                    <div class="uk-width-1-2">
                        <a :href="url('login')">Login</a>
                    </div>
                    <div class="uk-width-1-2">
                        <a :href="url('register')" class="uk-button uk-button-primary uk-width-1-1">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import GlobalSearch from '../components/GlobalSearch.vue';
    import ShoppingCart from '../components/ShoppingCart.vue';

    import Helpers from '../mixins/helpers';

    export default {

        props: [
            'user',
            'order',
            'hasNewNotifications'
        ],

        mixins: [
            Helpers
        ],

        components: {
            GlobalSearch,
            ShoppingCart
        },

        data: function () {
            return {
                feed: []
            }
        },

        methods: {
            logout: function () {
                axios.post('/logout')
                    .then(response => {
                        window.location = SITE_URL + '/login';
                    });
            },
            tournamentUrl: function (tournament) {
                return SITE_URL + '/disc-golf-tournament/' + tournament.id + '/' + tournament.slug;
            },
            viewedNotifications: function () {
                this.$emit('viewed-notifications');
            },
            updateCart: function () {
//                this
            },
            manageTournamentUrl: function (tournament) {
                return SITE_URL + '/manage/' + tournament.id;
            },
            updateOrder: function (order) {
                this.$emit('order-updated',  order);
            },
            userImageUrl: function () {
                return 'background-image: url(' + this.image(this.user.image.filename, 'poster-small') + ')';
            }
        },

        computed: {
            active: function () {
                if(typeof active === 'undefined') return '';

                return active;
            }
        }
    }

</script>