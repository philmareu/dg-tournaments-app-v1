<template>
    <div class="uk-card uk-card-small uk-card-default uk-card-body">

        <basic-form :buttons="buttons" :status="status" v-on:submitted="processForm">
            <input type="hidden" name="latitude" v-model="resource.latitude">
            <input type="hidden" name="longitude" v-model="resource.longitude">

            <div v-show="hasPdgaTiers()">
                <div class="uk-alert uk-alert-success">
                    <p>The name, dates, city, state and country attributes can only be edited on the <a href="http://pdag.com">PDGA</a> website.</p>
                </div>

                <input type="hidden" name="name" v-model="resource.name">
                <input type="hidden" name="start" id="basic-start-date-hidden">
                <input type="hidden" name="end" id="basic-end-date-hidden">
                <input type="hidden" name="city" v-model="resource.city">
                <input type="hidden" name="state_province" v-model="resource.state_province">
                <input type="hidden" name="country" v-model="resource.country">
            </div>

            <div class="uk-margin" uk-grid>
                <div class="uk-width-1-2@s">
                    <label class="uk-form-label">Poster</label>
                    <poster></poster>
                </div>
                <div class="uk-width-1-2@s">
                    <label class="uk-form-label">Click on map to change headquarters location.</label>
                    <location :id="mapId"
                              :latitude="mapLat"
                              :longitude="mapLng" v-on:location-updated="updateLocation"></location>
                </div>
            </div>

            <div class="uk-grid">
                <div class="uk-width-1-2@s">
                    <label for="name" class="uk-form-label">Name<span class="uk-text-danger">*</span></label>
                    <input id="name" type="text" name="name" v-model="resource.name" class="uk-input uk-form-small" :disabled="hasPdgaTiers()">
                </div>
                <div class="uk-width-1-4@s">
                    <form-row>
                        <span slot="label">Starting Date<span class="uk-text-danger">*</span></span>
                        <input type="text"
                               name="start"
                               id="basic-start-date"
                               class="basic-date-input uk-width-1-1 uk-input uk-form-small" :disabled="hasPdgaTiers()">
                    </form-row>
                </div>
                <div class="uk-width-1-4@s">
                    <form-row>
                        <span slot="label">Ending Date<span class="uk-text-danger">*</span></span>
                        <input type="text"
                               name="end"
                               id="basic-end-date"
                               class="basic-date-input uk-width-1-1 uk-input uk-form-small" :disabled="hasPdgaTiers()">
                    </form-row>
                </div>
            </div>

            <div class="uk-grid">
                <div class="uk-width-1-3@s">
                    <form-row id="basic-name">
                        <span slot="label">City<span class="uk-text-danger">*</span></span>
                        <input type="text" name="city" id="basic-city" v-model="resource.city" class="uk-input uk-form-small" :disabled="hasPdgaTiers()">
                    </form-row>
                </div>
                <div class="uk-width-1-6@s">
                    <form-row id="basic-name">
                        <span slot="label">State/Province</span>
                        <input type="text" name="state_province" id="basic-state" v-model="resource.state_province" class="uk-input uk-form-small" :disabled="hasPdgaTiers()">
                    </form-row>
                </div>
                <div class="uk-width-1-6@s">
                    <form-row id="basic-name">
                        <span slot="label">Country<span class="uk-text-danger">*</span></span>
                        <input type="text" name="country" id="basic-country" v-model="resource.country" class="uk-input uk-form-small" :disabled="hasPdgaTiers()">
                    </form-row>
                </div>
                <div class="uk-width-1-3@s">
                    <form-row id="timezones">
                        <span slot="label">Timezone<span class="uk-text-danger">*</span></span>
                        <select name="timezone" id="timezones" v-model="resource.timezone" class="uk-select uk-form-small">
                            <option v-for="timezone in timezones" :value="timezone" v-text="timezone"></option>
                        </select>
                    </form-row>
                </div>
            </div>

            <div class="uk-grid">
                <div class="uk-width-1-4@s">
                    <form-row id="director">
                        <span slot="label">Director</span>
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
                        <span slot="label">Format<span class="uk-text-danger">*</span></span>
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

        </basic-form>
    </div>
</template>

<script>
    import Form from '../mixins/form/manage';
    import BasicForm from '../components/BasicForm.vue';
    import Location from '../tournament/Location.vue';

    export default {

        mixins: [
            Form
        ],

        components: {
            Location,
            BasicForm
        },

        data: function() {
            return {
                resources: null,
                resource: tournament,
                reset: {},
                modal: '#modal-information',
                endpoint: '/tournament',

                pdgaEventUrl: 'http://pdga.com/tour/event/' + tournament.data_source_tournament_id,
                specialEventTypes: [],
                classes: classes,
                formats: formats,
                timezones: timezones,
                mapId: 'location-map',
                mapLat: tournament.latitude,
                mapLng: tournament.longitude
            }
        },

        methods: {
            showEditForm: function () {

            },
            hasPdgaTiers: function () {
                return this.resource.pdga_tiers.length > 0;
            },
            updateLocation: function (lngLat) {
                this.resource.latitude = Math.trunc(parseFloat(lngLat.lat) * 1000000) / 1000000;
                this.resource.longitude = Math.trunc(parseFloat(lngLat.lng) * 1000000) / 1000000;
            }
        },
        mounted: function () {

            axios.get('/lists/special-event-types')
                .then(response => {
                    this.specialEventTypes = response.data;
                    let start = moment(this.resource.start);
                    let end = moment(this.resource.end);
                    document.getElementById('basic-start-date').value = start.format('M-D-YYYY');
                    document.getElementById('basic-end-date').value = end.format('M-D-YYYY');
                    document.getElementById('basic-start-date-hidden').value = start.format('M-D-YYYY');
                    document.getElementById('basic-end-date-hidden').value = end.format('M-D-YYYY');

                    $('.basic-date-input').datepicker({
                        zIndex: 2000,
                        autoHide: true,
                        format: 'm-d-yyyy'
                    });

                    UIkit.modal('#modal-information').toggle();
                });
        }
    }
</script>