/**
 * Load the resources used on all pages
 */

require('./base');

/**
 * Page specific packages
 */

window.datepicker = require('@fengyuanchen/datepicker');
window.timepicker = require('timepicker');

/**
 * Vue components
 */

// Default
Vue.component('sponsorship-map', require('./vue/tournaments/sponsor/SponsorshipMap.vue'));
Vue.component('tournament-map', require('./vue/tournaments/search/TournamentMap.vue'));
Vue.component('activity-feed', require('./vue/tournaments/search/ActivityFeed.vue'));
Vue.component('credit-cards', require('./vue/components/CreditCards.vue'));
Vue.component('order-sponsorships', require('./vue/checkout/Sponsorships.vue'));
Vue.component('checkout', require('./vue/checkout/Checkout.vue'));
Vue.component('approve-claim-request', require('./vue/tournament/ApproveClaimRequest.vue'));
Vue.component('stripe-accounts', require('./vue/components/StripeAccounts.vue'));
Vue.component('display-registration', require('./vue/tournament/display/Registration.vue'));
Vue.component('action-bar', require('./vue/tournament/ActionBar.vue'));
Vue.component('display-sponsorships', require('./vue/tournament/display/Sponsorships.vue'));
Vue.component('claim', require('./vue/tournament/Claim.vue'));
Vue.component('footer-information-small', require('./vue/components/FooterInformationSmall.vue'));
Vue.component('footer-information-large', require('./vue/components/FooterInformationLarge.vue'));

// Manage
Vue.component('sponsor-library', require('./vue/components/SponsorLibrary.vue'));
Vue.component('poster', require('./vue/tournament/Poster.vue'));
Vue.component('course', require('./vue/tournament/Course.vue'));
Vue.component('course-nav', require('./vue/tournament/CourseNav.vue'));
Vue.component('sponsorship-nav', require('./vue/tournament/SponsorshipNav.vue'));
Vue.component('form-modal', require('./vue/components/FormModal.vue'));
Vue.component('basic-form', require('./vue/components/BasicForm.vue'));
Vue.component('form-row', require('./vue/components/FormRow.vue'));
Vue.component('modal', require('./vue/components/Modal.vue'));
Vue.component('location', require('./vue/tournament/Location.vue'));
Vue.component('submit-tournament', require('./vue/manage/SubmitTournament.vue'));
Vue.component('information', require('./vue/tournament/Information.vue'));
Vue.component('registration', require('./vue/tournament/Registration.vue'));
Vue.component('schedule', require('./vue/tournament/Schedule.vue'));
Vue.component('player-packs', require('./vue/tournament/PlayerPacks.vue'));
Vue.component('links', require('./vue/tournament/Links.vue'));
Vue.component('media', require('./vue/tournament/Media.vue'));
Vue.component('courses', require('./vue/tournament/Courses.vue'));
Vue.component('sponsorship', require('./vue/tournament/Sponsorship.vue'));
Vue.component('stripe', require('./vue/tournament/Stripe.vue'));
Vue.component('refund', require('./vue/manage/order/Refund.vue'));

const bugsnag = require('@bugsnag/js');
const bugsnagVue = require('@bugsnag/plugin-vue');

window.bugsnagClient = bugsnag({
    apiKey: 'e6dafe167baecf4f0cd93ba2ac4fc986',
    appVersion: 'current'
});

window.bugsnagClient.use(bugsnagVue, Vue);

/**
 * Load the app
 */

require('./vue/app');