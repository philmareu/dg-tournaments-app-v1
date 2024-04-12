<template>
    <div id="tournament" class="uk-card uk-card-default uk-card-small">
        <div class="uk-card-header">
            <h3 class="uk-card-title">Caddy Book</h3>
        </div>

        <div class="uk-card-body">
            <a href="#" @click.prevent="showEditHolesModal" class="uk-button uk-button-primary uk-button-small uk-margin"><span uk-icon="icon: pencil;"></span> Edit</a>

            <div v-for="hole in holeArray" v-if="holeHasNote(hole)">

                <h4>Hole - {{ hole }}</h4>
                <p v-text="getHoleNote(hole)"></p>
                <hr>

            </div>

            <form-modal :id="modalId()" :buttons="buttons" :status="status" v-on:submitted="saveHoleNotes">
                <span slot="title">Course Hole Notes</span>

                <form-row v-for="hole in holeArray" :key="hole.id">
                    <span slot="label">Hole {{ hole }}</span>
                    <textarea :name="dataFieldName(hole)" class="uk-textarea" :value="getHoleNote(hole)"></textarea>
                </form-row>
            </form-modal>
        </div>
    </div>
</template>

<script>
    import Form from '../mixins/form/manage';

    export default {

        props: [
            'course'
        ],

        mixins: [
            Form
        ],

        data: function() {
            return {
                storeId: null,
                resources: null,
                resource: this.course,
                reset: {
                    id: null
                },
                modal: '#modal-hole-notes-' + this.course.id,
                endpoint: '/tournament/courses',
                holeNotes: this.course.hole_notes_array,
                holeArray: this.course.hole_array
            }
        },

        methods: {
            showEditHolesModal: function() {
                UIkit.modal(this.modal).toggle();
            },
            saveHoleNotes: function(form, data) {
                this.status.thinking = true;
                this.status.errors = null;
                this.buttons.cancel.html = this.buttons.cancel.done;

                axios.put('/tournament/course/holes/' + this.resource.id, data)
                    .then(response => {
                        this.status.thinking = false;
                        this.holeNotes = response.data;
                        UIkit.modal('#modal-hole-notes').toggle();
                        this.status.success = null;
                    })
                    .catch(error => {
                        this.displayErrors(error.response.data);
                    });

                this.closeHoleModal();
            },
            closeHoleModal: function() {
                UIkit.modal(this.modal).toggle();
            },
            getHoleNote: function(hole) {

                if ("undefined" === typeof this.holeNotes[hole]) {
                    return null;
                }

                return this.holeNotes[hole][0].notes;
            },
            holeHasNote: function (hole) {
                return this.getHoleNote(hole) !== null;
            },
            dataFieldName: function(hole) {
                return 'notes[' + hole + ']';
            },
            resourceDeletedEvent: function (resource) {
                window.location = tournament.path;
            },
            resourceUpdatedEvent: function (response) {
                this.holeArray = response.data.hole_array;
            },
            modalId: function () {
                return 'modal-hole-notes-' + this.course.id;
            }
        }
    }
</script>