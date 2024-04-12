<template>
    <ul class="uk-nav uk-nav-default">
        <li class="uk-nav-header"><span uk-icon="icon: location;" class="uk-margin-small-right"></span>Courses</li>
        <li v-for="course in resources">
            <a :href="url('manage/' + tournament.id + '/course/' + course.id)" v-text="course.name"></a>
        </li>
        <li>
            <a href="#" @click.prevent="showSelectCourseModal"><span uk-icon="icon: plus; ratio: .7;" class="uk-margin-small-right"></span>Add Course</a>
        </li>

        <modal :id="[ 'modal-select-course-' + _uid ]">
            <span slot="title">Select Course or Create New</span>

            <table class="uk-table uk-table-small uk-table-striped">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>City</th>
                        <th>State</th>
                    </tr>
                </thead>
                <tbody>
                    <tr  v-for="course in surroundingCourses">
                        <td>
                            <a href="#" @click.prevent="showAddForm(course.id)">{{ course.name}}</a>
                        </td>
                        <td>
                            {{ course.city }}
                        </td>
                        <td>
                            {{ course.state_province }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div slot="footer">
                <div><a href="#" @click.prevent="showAddForm()" class="uk-button uk-button-primary uk-button-small">Create New Course</a></div>
            </div>
        </modal>

        <form-modal :id="[ 'modal-add-course-' + _uid ]" :buttons="buttons" :status="status" v-on:submitted="processForm">
            <span slot="title">Course Information</span>
            <input type="hidden" name="course_id" v-model="courseId">
            <input type="hidden" name="latitude" v-model="resource.latitude">
            <input type="hidden" name="longitude" v-model="resource.longitude">

            <div class="uk-margin">
                <label class="uk-form-label">Course location (click on map to update).</label>
                <location :id="mapId"
                          :latitude="mapLat"
                          :longitude="mapLng"
                          v-on:location-updated="updateLocation"></location>

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
                <span slot="label">Address <span class="uk-text-danger">*</span></span>
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

    </ul>
</template>

<script>

    import Form from '../mixins/form/manage';
    import Location from './Location.vue';
    import Helpers from '../mixins/helpers';

    export default {

        mixins: [
            Form,
            Helpers
        ],

        components: {
            Location
        },

        data: function() {
            return {
                storeId: tournament.id,
                resources: tournament.courses,
                resource: {
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
                modal: '#modal-add-course-' + this._uid,
                endpoint: '/tournament/courses',
                mapId: '#course-location' + this._uid,
                mapLat: 38.959475150289336,
                mapLng: -95.25525861058098,
                surroundingCourses: [],
                courseId: null,
                tournament: tournament
            }
        },

        methods: {
            showSelectCourseModal: function() {
                axios.get('/tournament/surrounding-courses/' + tournament.id)
                    .then(response => {
                        this.surroundingCourses = response.data;
                        UIkit.modal('#modal-select-course-' + this._uid).toggle();
                    })
                    .catch(error => {
                    });
            },
            showAddForm: function(courseId) {
                if(typeof courseId !== 'undefined') {
                    this.resource = _.find(this.surroundingCourses, ['id', courseId]);
                    this.mapLat = this.resource.latitude;
                    this.mapLng = this.resource.longitude;
                    this.courseId = courseId;
                    this.resource.id = null;
                } else {
                    this.mapLat = tournament.latitude;
                    this.mapLng = tournament.longitude;
                }

                UIkit.modal(this.modal).toggle();

                let $this = this;
                UIkit.util.on(this.modal, 'hide', function () {
                    $this.resource = $this.reset;
                    $this.courseId = null;
                });
            },
            closeSelectCourseModal: function () {
//                UIkit.modal('#modal-select-course').toggle();
            },
            closeAddCourseModal: function () {
//                UIkit.modal(this.modal).toggle();
            },
            courseUrl: function(course) {
                return tournament.path + '/course/' + course.id;
            },
            updateLocation: function(lngLat) {
                this.resource.latitude = lngLat.lat;
                this.resource.longitude = lngLat.lng;
            },
            responseProcessedHook: function (response) {
                UIkit.modal(this.modal).toggle();
                this.buttons.cancel.html = this.buttons.cancel.defaultHtml;
                this.resource = this.reset;
                this.courseId = null;
            },
        },
        beforeCreate: function() {



//            axios.get('/tournament-courses/' + tournament.id)
//                .then(response => {
//                    this.courses = response.data;
//                });
        }
    }
</script>