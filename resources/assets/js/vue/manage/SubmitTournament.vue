<template>
    <div>
        <div v-if="submitted">
            <div class="uk-padding-large uk-text-center">
                <span uk-icon="icon: star; ratio: 4;"></span>
                <p>You've successfully submitted your tournament!</p>
                <div class="uk-text-center">
                    <a :href="manageUrl" class="uk-button uk-button-primary uk-button-small">Manage Tournament</a>
                </div>
            </div>
        </div>

        <form @submit.prevent="submit($event)" v-else>
            <input type="hidden" name="latitude" v-model="resource.latitude">
            <input type="hidden" name="longitude" v-model="resource.longitude">

            <p>If your event is PDGA sanctioned, then it should already have a page setup. If you are the director or authorized staff member you should <a
                    :href="url('search')">search</a> for your page and then click on the "Claim" button. Please <a
                    :href="url('contact-us')">contact us</a> if you have any issues.</p>

            <div class="uk-margin-bottom">
                <label class="uk-form-label">Click on map to select location</label>
                <location :id="mapId"
                          :latitude="mapLat"
                          :longitude="mapLng" v-on:location-updated="updateLocation"></location>
            </div>

            <div class="uk-grid">
                <div class="uk-width-1-2@s">
                    <label for="name" class="uk-form-label">Name <span class="uk-text-danger">*</span></label>
                    <input id="name" type="text" name="name" v-model="resource.name" class="uk-input uk-form-small">
                </div>
                <div class="uk-width-1-4@s">
                    <form-row>
                        <span slot="label">Starting Date <span class="uk-text-danger">*</span></span>
                        <input type="text"
                               name="start"
                               id="basic-start-date"
                               class="basic-date-input uk-width-1-1 uk-input uk-form-small">
                    </form-row>
                </div>
                <div class="uk-width-1-4@s">
                    <form-row>
                        <span slot="label">Ending Date <span class="uk-text-danger">*</span></span>
                        <input type="text"
                               name="end"
                               id="basic-end-date"
                               class="basic-date-input uk-width-1-1 uk-input uk-form-small">
                    </form-row>
                </div>
            </div>

            <div class="uk-grid">
                <div class="uk-width-1-3@s">
                    <form-row id="basic-name">
                        <span slot="label">City <span class="uk-text-danger">*</span></span>
                        <input type="text" name="city" id="basic-city" v-model="resource.city" class="uk-input uk-form-small">
                    </form-row>
                </div>
                <div class="uk-width-1-6@s">
                    <form-row id="basic-name">
                        <span slot="label">State/Province</span>
                        <input type="text" name="state_province" id="basic-state" v-model="resource.state_province" class="uk-input uk-form-small">
                    </form-row>
                </div>
                <div class="uk-width-1-6@s">
                    <form-row id="basic-name">
                        <span slot="label">Country <span class="uk-text-danger">*</span></span>
                        <input type="text" name="country" id="basic-country" v-model="resource.country" class="uk-input uk-form-small">
                    </form-row>
                </div>
                <div class="uk-width-1-3@s">
                    <form-row id="timezones">
                        <span slot="label">Timezone <span class="uk-text-danger">*</span></span>
                        <select name="timezone" id="timezones" v-model="resource.timezone" class="uk-select uk-form-small">
                            <option v-for="timezone in timezones" :value="timezone" v-text="timezone"></option>
                        </select>
                    </form-row>
                </div>
            </div>

            <div class="uk-grid">
                <div class="uk-width-1-4@s">
                    <form-row id="director">
                        <span slot="label">Director <span class="uk-text-danger">*</span></span>
                        <input type="text" name="director" id="director" v-model="resource.director" class="uk-input uk-form-small">
                    </form-row>
                </div>
                <div class="uk-width-1-4@s">
                    <form-row id="basic-name">
                        <span slot="label">Email</span>
                        <input type="text" name="email" id="basic-email" v-model="resource.email" class="uk-input uk-form-small">
                    </form-row>
                </div>
                <div class="uk-width-1-4@s">
                    <form-row id="basic-name">
                        <span slot="label">Phone</span>
                        <input type="text" name="phone" id="basic-phone" v-model="resource.phone" class="uk-input uk-form-small">
                    </form-row>
                </div>
                <div class="uk-width-1-4@s">
                    <form-row id="format">
                        <span slot="label">Format <span class="uk-text-danger">*</span></span>
                        <select name="format_id" id="format" v-model="resource.format_id" class="uk-select uk-form-small">
                            <option v-for="format in formats" :value="format.id" v-text="format.title"></option>
                        </select>
                    </form-row>
                </div>
            </div>

            <form-row id="special-event-type">
                <span slot="label">Classes</span>
                <label v-for="tclass in classes" class="uk-form-label uk-display-inline uk-margin-right">
                    <input type="checkbox" name="class_ids[]" v-model="resource.class_ids" class="uk-checkbox" :value="tclass.id">
                    <span v-text="tclass.title"></span>
                </label>
            </form-row>

            <form-row id="special-event-type">
                <span slot="label">Special Event Types</span>
                <label v-for="type in specialEventTypes" class="uk-form-label uk-display-inline uk-margin-right">
                    <input type="checkbox" name="special_event_type_ids[]" v-model="resource.special_event_type_ids" class="uk-checkbox" :value="type.id">
                    <span v-text="type.title"></span>
                </label>
            </form-row>

            <form-row id="paypal">
                <span slot="label">PayPal email (CC and Paypal payments for sponsorships)</span>
                <input type="text" name="paypal" id="paypal" v-model="resource.paypal" class="uk-input uk-form-small">
            </form-row>

            <div class="uk-margin">
                <label for="description" class="uk-form-label">Description</label>
                <div class="uk-form-controls">
                    <textarea name="description" class="uk-textarea" cols="30" rows="4" v-model="resource.description"></textarea>
                </div>
            </div>

            <div class="uk-margin">
                <label for="accepted">
                    <input id="accepted" type="checkbox" value="yes" name="accepted"> This information is valid and applies to a disc golf tournament.
                </label>
            </div>

            <div v-if="status.thinking">
                <div uk-spinner></div>
            </div>
            <button type="submit" class="uk-button uk-button-primary uk-button-small" v-if="! status.thinking">Submit Tournament</button>

            <div v-if="status.errors != null" class="uk-alert-danger" uk-alert>
                <a class="uk-alert-close" @click.prevent="closeAlert('errors')" uk-close></a>
                <p v-text="status.errors"></p>
            </div>
            <div v-if="status.success != null" class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" @click.prevent="closeAlert('success')" uk-close></a>
                <p v-text="status.success"></p>
            </div>
        </form>
    </div>
</template>

<script>

    import Location from '../tournament/Location.vue';
    import FormRow from '../components/FormRow.vue';
    import Helpers from '../mixins/helpers';

    import Form from '../mixins/form/manage';

    export default {

        mixins: [
            Form,
            Helpers
        ],

        components: {
            Location,
            FormRow
        },

        data: function () {
            return {
                resource: {
                    latitude: null,
                    longitude: null,
                    start: null,
                    end: null,
                    special_event_type_ids: [],
                    class_ids: [],
                    timezone: 'America/Chicago',
                    format_id: null
                },
                reset: {
                    id: null,
                    latitude: 38.959475150289336,
                    longitude: -95.25525861058098,
                    start: null,
                    end: null,
                    city: null,
                    state_province: null,
                    country: null,
                    email: null,
                    phone: null,
                    description: null,
                    special_event_type_ids: [],
                    class_ids: [],
                    timezone: 'America/Chicago',
                    format_id: null
                },
                endpoint: '/tournament',
                specialEventTypes: specialEventTypes,
                classes: classes,
                timezones: timezones,
                mapId: 'location-map',
                mapLat: 38.959475150289336,
                mapLng: -95.25525861058098,
                formats: formats,
                submitted: false,
                newId: null
            }
        },

        methods: {
            updateLocation: function (lngLat) {
                this.resource.latitude = lngLat.lat;
                this.resource.longitude = lngLat.lng;
            },
            submit: function (event) {
                let form = $(event.target);
                this.processForm(form, form.serialize());
            },
            responseProcessedHook: function(response) {
                this.newId = response.data.id;
                this.submitted = true;
                window.scrollTo(0, 0);
            }
        },
        computed: {
            manageUrl: function () {
                return '/manage/' + this.newId;
            }
        },
        mounted: function () {
//            let start = moment(this.resource.start);
//            let end = moment(this.resource.end);
//            document.getElementById('basic-start-date').value = start.format('M-D-YYYY');
//            document.getElementById('basic-end-date').value = end.format('M-D-YYYY');
//            document.getElementById('basic-start-date-hidden').value = start.format('M-D-YYYY');
//            document.getElementById('basic-end-date-hidden').value = end.format('M-D-YYYY');

            $('.basic-date-input').datepicker({
                zIndex: 2000,
                autoHide: true,
                format: 'm-d-yyyy'
            });
        }
    }

</script>