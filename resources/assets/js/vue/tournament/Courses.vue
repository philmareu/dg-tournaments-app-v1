<template>
    <div>

        <div class="uk-card uk-card-default uk-card-small">
            <div class="uk-card-header">
                <ul class="uk-iconnav">
                    <li><a href="#" uk-icon="icon: plus" @click.prevent="showSelectCourseModal"></a></li>
                </ul>
            </div>
        </div>

        <div v-if="resources.length === 0">
            <div class="uk-card uk-card-default uk-card-small uk-card-body uk-text-center uk-text-muted">
                <span uk-icon="icon: image; ratio: 3;"></span>
                <p>No Courses Listed</p>
            </div>
        </div>

        <div class="uk-card uk-card-default uk-card-small uk-margin" v-for="course in resources">
            <div class="uk-card-header">
                <ul class="uk-iconnav">
                    <li><a href="#" uk-icon="icon: file-edit" @click.prevent="showEditForm(course)"></a></li>
                    <li><a href="#" uk-icon="icon: trash" @click.prevent="destroy(course)"></a></li>
                </ul>
            </div>

            <div class="uk-card-body">
                <div class="uk-card-badge uk-label">{{ course.holes }} Holes</div>
                <h3 class="uk-card-title">{{ course.name }}</h3>
                <div class="uk-margin">
                    {{ course.address }} <br>
                    {{ course.city }}, {{ course.state_province }} ({{ course.country }}) <br>
                </div>
                <label for="" class="uk-form-label">Directions</label>
                <div class="uk-placeholder">
                    <div>{{ course.directions }}</div>
                </div>

                <label for="" class="uk-form-label">Notes</label>
                <div class="uk-placeholder">
                    <div>{{ course.notes }}</div>
                </div>

                <course-hole-information :course="course"></course-hole-information>

            </div>
        </div>

        <modal id="modal-select-course" v-on:closing="closeSelectCourseModal">
            <span slot="title">Select Course or Create New</span>

            <ul class="uk-list">
                <li v-for="course in surroundingCourses" class="uk-margin-remove">
                    <a href="#" @click.prevent="showAddForm(course.id)"><strong>{{ course.name}}</strong>, {{ course.city }} {{ course.state_province }}</a>
                </li>
            </ul>

            <div slot="footer">
                <div><a href="#" @click.prevent="showAddForm()" class="uk-button uk-button-primary uk-button-small">Create New Course</a></div>
            </div>
        </modal>

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

    </div>
</template>

<script>

    import Form from '../mixins/form/manage';
    import CourseHoleInformation from './CourseHoleInformation.vue';
    import Location from './Location.vue';

    export default {

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
                modal: '#modal-courses',
                endpoint: '/tournament/courses',
                mapId: '#course-location',
                mapLat: 38.959475150289336,
                mapLng: -95.25525861058098,
                surroundingCourses: [],
                courseId: null
            }
        },

        methods: {
            showSelectCourseModal: function() {
                if(LOAD_GOOGLE_ANALYTICS) ga('send', 'event', 'Tournament Page', 'Edit tournament', 'Courses');

                axios.get('/tournament/surrounding-courses/' + tournament.id)
                    .then(response => {
                        this.surroundingCourses = response.data;
                        UIkit.modal('#modal-select-course').toggle();
                    })
                    .catch(error => {
                        alert('Sorry, we were unable to process your request. Please contact admin@dgtournaments.com for support.');
                    });
            },
            showAddForm: function(courseId) {
                if(typeof courseId !== 'undefined') {
                    this.resource = _.find(this.surroundingCourses, ['id', courseId]);
                    this.mapLat = this.resource.latitude;
                    this.mapLng = this.resource.longitude;
                    this.courseId = courseId;
                    this.resource.id = null;
                }

                UIkit.modal(this.modal).toggle();
            },
            closeSelectCourseModal: function () {
                UIkit.modal('#modal-select-course').toggle();
            },
            closeAddCourseModal: function () {
                UIkit.modal('#modal-course-form').toggle();
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
            }
        },

        beforeCreate: function() {
//            axios.get('/tournament-courses/' + tournament.id)
//                .then(response => {
//                    this.courses = response.data;
//                });
        }
    }
</script>