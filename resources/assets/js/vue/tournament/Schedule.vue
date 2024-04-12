<template>
    <div class="uk-card uk-card-default uk-card-small">

        <div class="uk-card-body">
            <div class="uk-grid">
                <div class="uk-width-1-2@s">
                    <basic-form :buttons="buttons" :status="status" v-on:submitted="processForm">

                        <div class="uk-grid">
                            <div class="uk-width-1-2">
                                <form-row id="schedule-date-times">
                                    <span slot="label">Date <span class="uk-text-danger">*</span></span>
                                    <input type="text" name="date" id="schedule-start-date" class="uk-input schedule-date-input uk-form-small">
                                </form-row>
                            </div>
                            <div class="uk-width-1-2">
                                <form-row id="schedule-location" class="uk-margin">
                                    <span slot="label">Location</span>
                                    <input type="text" name="location" id="schedule-location" v-model="resource.location" class="uk-input uk-form-small">
                                </form-row>
                            </div>
                        </div>

                        <form-row id="schedule-summary">
                            <span slot="label">Summary <span class="uk-text-danger">*</span></span>
                            <textarea name="summary" id="schedule-summary" rows="3" v-model="resource.summary" class="uk-textarea"></textarea>
                        </form-row>

                        <div class="uk-grid uk-margin-small-top uk-margin-bottom">
                            <div class="uk-width-1-2">
                                <form-row id="schedule-date-times" class="uk-margin">
                                    <span slot="label">Start</span>
                                    <input type="text" name="start" id="schedule-start-time" class="uk-input schedule-time-input uk-form-small">
                                </form-row>
                            </div>
                            <div class="uk-width-1-2">
                                <form-row id="schedule-date-times" class="uk-margin">
                                    <span slot="label">End</span>
                                    <input type="text" name="end" id="schedule-end-time" class="uk-input schedule-time-input uk-form-small">
                                </form-row>
                            </div>
                        </div>
                    </basic-form>
                </div>
                <div class="uk-width-1-2@s">
                    <div v-for="(items, day) in scheduleByDay" class="uk-margin">
                        <h4>{{ day }}</h4>
                        <div v-for="(item, index) in items" class="uk-margin-small">
                            <a href="#" uk-icon="icon: file-edit" @click.prevent="showEditForm(item)" class="uk-margin-small-right uk-link-muted"></a><a href="#" uk-icon="icon: trash" @click.prevent="destroy(item)" class="uk-margin-small-right uk-link-muted"></a>{{ item.time_span }} / {{ item.summary }} <span class="uk-text-muted" v-if="item.location !== null">({{ item.location }})</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Form from '../mixins/form/manage';

    export default {

        mixins: [
            Form
        ],

        data: function() {
            return {
                scheduleByDay: tournament.schedule_by_day,
                storeId: tournament.id,
                resources: tournament.schedule,
                resource: {
                    id: null,
                    date: null,
                    start: null,
                    end: null,
                    summary: null,
                    location: null
                },
                reset: {
                    id: null,
                    summary: null,
                    location: null,
                    start: null,
                    end: null
                },
                modal: '#modal-schedule',
                endpoint: '/tournament/schedule'
            }
        },

        methods: {
            showEditForm: function(resource) {
                if(LOAD_GOOGLE_ANALYTICS) ga('send', 'event', 'Tournament Page', 'Edit tournament', 'Edit schedule');
                this.clearValues();
                document.getElementById('schedule-start-date').value = moment(resource.date, 'YYYY-MM-DD HH:mm:ss').format('M-D-YYYY');
                if(resource.start !== null) document.getElementById('schedule-start-time').value = moment(resource.start, 'YYYY-MM-DD HH:mm:ss').format('h:mm A');
                if(resource.end !== null) document.getElementById('schedule-end-time').value = moment(resource.end, 'YYYY-MM-DD HH:mm:ss').format('h:mm A');
                this.resource = resource;
                this.status = {
                    thinking: false,
                    errors: null,
                    success: null
                };
            },
            clearValues: function () {
                document.getElementById('schedule-start-date').value = moment(tournament.start, 'YYYY-MM-DD HH:mm:ss').format('M-D-YYYY');
                document.getElementById('schedule-start-time').value = null;
                document.getElementById('schedule-end-time').value = null;
                this.resource = this.reset;
            },
            responseProcessedHook: function (response) {
                this.scheduleByDay = response.data;
            }
        },

        mounted: function () {
            $('.schedule-date-input').datepicker({
                zIndex: 2000,
                autoHide: true,
                format: 'm-d-yyyy',
                date: moment(tournament.start, "YYYY-MM-DD").format('MM/DD/YYYY')
            });

            $('.schedule-time-input').timepicker({
                timeFormat: 'g:i A'
            });

            var date = moment(tournament.start);
            document.getElementById('schedule-start-date').value = date.format('M-D-YYYY');
//            document.getElementById('schedule-start-time').value = '8:00 AM';
        }
    }
</script>