<template>
    <div class="uk-card uk-card-default uk-card-small">

        <div class="uk-card-body">
            <div v-if="registrationStatus == 'notPosted'">
                <p>Registration hasn't been posted yet.</p>
            </div>

            <div v-if="registrationStatus == 'closed'">
                <p>Registration is closed</p>
            </div>

            <basic-form :buttons="buttons" :status="status" v-on:submitted="processForm">

                <div class="uk-grid">
                    <div class="uk-width-1-3@s">
                        <label for="registration-opens-at" class="uk-form-label">Opens</label>
                        <div class="uk-form-controls">
                            <input type="text"
                                   name="opens_at"
                                   id="registration-opens-at"
                                   class="date-input uk-width-1-1 uk-input uk-form-small">
                        </div>
                    </div>
                    <div class="uk-width-1-3@s">
                        <form-row>
                            <span slot="label">Closes</span>
                            <input type="text"
                                   name="closes_at"
                                   id="registration-closes-at"
                                   class="date-input uk-width-1-1 uk-input uk-form-small">
                        </form-row>
                    </div>
                    <div class="uk-width-1-3@s">
                        <form-row>
                            <span slot="label">Registration URL</span>
                            <input type="text" name="url" class="uk-input uk-form-small" v-model="resource.url">
                        </form-row>
                    </div>
                </div>
            </basic-form>
        </div>
    </div>
</template>

<script>

    import Form from '../mixins/form/manage';

    export default {

        components: {
        },

        mixins: [Form],

        data: function() {
            return {
                storeId: tournament.id,
                resource: tournament.registration,
                modal: '#modal-registration',
                endpoint: '/tournament/registration',
                moment: moment,
            }
        },

        computed: {
            registrationStatus: function() {

                let now = moment();

                if(this.resource.opens_at === null) return 'notPosted';

                if(this.opensAt.isAfter(now)) return 'notOpen';

                if(this.resource.closes_at === null) {
                    return 'open';
                } else if (this.closesAt.isBefore(now)) {
                    return 'closed';
                } else {
                    return 'open';
                }
            },
            opensAt: function() {
                return moment(this.resource.opens_at, "YYYY-MM-DD HH:mm:ss");
            },
            closesAt: function() {
                return moment(this.resource.closes_at, "YYYY-MM-DD HH:mm:ss");
            }
        },

        methods: {

        },

        mounted: function () {

            if(this.resource.opens_at !== null) {
                document.getElementById('registration-opens-at').value = this.opensAt.format('M-D-YYYY');
            }

            if(this.resource.closes_at !== null) {
                document.getElementById('registration-closes-at').value = this.closesAt.format('M-D-YYYY');
            }

            $('.date-input').datepicker({
                zIndex: 2000,
                autoHide: true,
                format: 'm-d-yyyy'
            });
        }
    }
</script>