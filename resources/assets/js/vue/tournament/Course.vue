<template>
    <div>

        <div v-if="deleted" class="uk-alert uk-alert-success">
            <p>This course has been removed from tournament.</p>
        </div>
        <div  v-else="">
            <div class="uk-card uk-card-small uk-card-default uk-margin">

                <div class="uk-card-header">
                    <ul class="uk-iconnav">
                        <li><a href="#" uk-icon="icon: file-edit" @click.prevent="showEditForm(resource)"></a></li>
                        <li><a href="#" uk-icon="icon: trash" @click.prevent="destroy(resource)"></a></li>
                    </ul>
                </div>

                <div class="uk-card-body">
                    <div class="uk-margin">
                        {{ resource.holes }} Holes <br>
                        {{ resource.address }} <br>
                        {{ resource.city }}, {{ resource.state_province }} ({{ resource.country }}) <br>
                    </div>
                    <h3>Directions</h3>
                    <p>{{ resource.directions }}</p>

                    <h3>Notes</h3>
                    <p>{{ resource.notes }}</p>

                </div>
            </div>

            <course-hole-information :course="resource"></course-hole-information>

            <form-modal id="modal-courses" :buttons="buttons" :status="status" v-on:submitted="processForm" v-on:closing="closeModal">
                <span slot="title">Course Information</span>
                <input type="hidden" name="course_id" v-model="courseId">
                <input type="hidden" name="latitude" v-model="resource.latitude">
                <input type="hidden" name="longitude" v-model="resource.longitude">

                <div class="uk-margin">
                    <label class="uk-form-label">Course location (click on map to update).</label>
                    <location :id="mapId"
                              :latitude="mapLat"
                              :longitude="mapLng" v-on:location-updated="updateLocation"></location>

                </div>

                <label for="name" class="uk-form-label">Name <span class="uk-text-danger">*</span></label>
                <div class="uk-form-controls">
                    <input id="name" type="text" name="name" class="uk-input uk-form-small" v-model="resource.name">
                </div>

                <form-row>
                    <span slot="label">Holes <span class="uk-text-danger">*</span></span>
                    <input type="text" name="holes" class="uk-input uk-form-small" v-model="resource.holes">
                </form-row>

                <form-row>
                    <span slot="label">Address</span>
                    <input type="text" name="address" class="uk-input uk-form-small" v-model="resource.address">
                </form-row>

                <form-row>
                    <span slot="label">Address 2</span>
                    <input type="text" name="address_2" class="uk-input uk-form-small" v-model="resource.address_2">
                </form-row>

                <form-row>
                    <span slot="label">City <span class="uk-text-danger">*</span></span>
                    <input type="text" name="city" class="uk-input uk-form-small" v-model="resource.city">
                </form-row>

                <form-row>
                    <span slot="label">State/Province</span>
                    <input type="text" name="state_province" class="uk-input uk-form-small" v-model="resource.state_province">
                </form-row>

                <form-row>
                    <span slot="label">Country <span class="uk-text-danger">*</span></span>
                    <input type="text" name="country" class="uk-input uk-form-small" v-model="resource.country">
                </form-row>

                <form-row>
                    <span slot="label">Directions</span>
                    <textarea name="directions" class="uk-textarea uk-form-small" v-model="resource.directions" rows="4"></textarea>
                </form-row>

                <form-row>
                    <span slot="label">Notes</span>
                    <textarea name="notes" class="uk-textarea uk-form-small" v-model="resource.notes" rows="4"></textarea>
                </form-row>
            </form-modal>
        </div>

    </div>
</template>

<script>

    import Form from '../mixins/form/manage';
    import CourseHoleInformation from './CourseHoleInformation.vue';
    import Location from './Location.vue';

    export default {

        props: [
            'course'
        ],

        mixins: [
            Form
        ],

        components: {
            CourseHoleInformation,
            Location
        },

        data: function() {
            return {
                storeId: tournament.id,
                resources: null,
                resource: this.course,
                reset: {
                    id: null,
                    name: null,
                    holes: null,
                    latitude: 38,
                    longitude: -102,
                    address: null,
                    address_2: null,
                    city: null,
                    state_province: null,
                    country: null,
                    direction: null,
                    notes: null
                },
                modal: '#modal-courses',
                endpoint: '/tournament/courses',
                mapId: '#course-location',
                mapLat: 38.959475150289336,
                mapLng: -95.25525861058098,
                surroundingCourses: [],
                courseId: null,
                deleted: false
            }
        },

        methods: {
            showEditFormHook: function (resource) {
                this.mapLat = resource.latitude;
                this.mapLng = resource.longitude;
            },
            courseUrl: function(course) {
                return tournament.path + '/course/' + course.id;
            },
            updateLocation: function(lngLat) {
                this.resource.latitude = lngLat.lat;
                this.resource.longitude = lngLat.lng;
            },
            resourceUpdatedEvent: function(response) {
                UIkit.modal(this.modal).toggle();
            },
            resourceDeletedEvent: function(response) {
                this.deleted = true;
            }
        },

//        beforeCreate: function() {
//            axios.get('/tournament-courses/' + tournament.id)
//                .then(response => {
//                    this.courses = response.data;
//                });
//        }
    }
</script>