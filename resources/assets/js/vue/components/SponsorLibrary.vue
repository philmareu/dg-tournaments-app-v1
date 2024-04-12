<template>
    <div>

        <a href="#" @click.prevent="showAddForm" class="uk-button uk-button-small uk-button-primary uk-margin">Add Sponsor</a>

        <div class="uk-grid-small uk-child-width-1-2 uk-child-width-1-4@s" uk-grid>
            <div v-for="sponsor in resources">
                <div class="uk-card uk-card-default uk-card-small uk-margin-bottom">
                    <div class="uk-card-header">
                        <ul class="uk-iconnav">
                            <li><a href="#" uk-icon="icon: file-edit" @click.prevent="showEditForm(sponsor)"></a></li>
                            <li><a href="#" uk-icon="icon: trash" @click.prevent="destroy(sponsor)"></a></li>
                        </ul>
                    </div>

                    <div class="uk-card-body">
                        <h3 class="uk-card-title uk-text-truncate">{{ sponsor.title }}</h3>

                        <div v-if="sponsor.url !== null">
                            URL: {{ sponsor.url }}
                        </div>
                        <div v-if="sponsor.upload_id !== null" class="uk-margin">
                            <img :src="image(sponsor.logo.filename, 'poster-small')" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form-modal id="modal-sponsors" :buttons="buttons" :status="status" v-on:submitted="processForm" v-on:closing="closeModal">
            <span slot="title">Sponsor</span>
            <input type="hidden" name="id" v-model="resource.id">
            <input v-if="upload.id !== null" type="hidden" name="upload_id" v-model="upload.id">

            <form-row id="resource-title">
                <span slot="label">Title <span class="uk-text-danger">*</span></span>
                <input type="text" name="title" id="sponsor-title" v-model="resource.title" class="uk-input">
            </form-row>

            <form-row id="resource-url">
                <span slot="label">URL</span>
                <input type="text" name="url" id="sponsor-url" v-model="resource.url" class="uk-input">
            </form-row>

            <label for="" class="uk-form-label">Upload</label>
            <upload-html :resourceName="resourceName"></upload-html>

            <div v-if="upload.id !== null">
                <img :src="image(upload.filename, 'poster-small')" alt="" v-if="upload.id !== null">
            </div>
        </form-modal>
    </div>
</template>

<script>

    import Form from '../mixins/form/manage';
    import FormModal from '../components/FormModal.vue';
    import FormRow from '../components/FormRow.vue';
    import Upload from '../mixins/form/upload';
    import UploadHtml from '../components/Upload.vue';
    import Helpers from '../mixins/helpers';

    export default {

        mixins: [
            Form,
            Upload,
            Helpers
        ],

        components: {
            UploadHtml,
            FormModal,
            FormRow
        },

        data: function () {
            return {
                storeId: null,
                resources: [],
                resource: {
                    id: null,
                    title: null,
                    url: null
                },
                reset: {
                    id: null,
                    title: null,
                    url: null,
                    upload_id: null
                },
                modal: '#modal-sponsors',
                endpoint: '/user/sponsors',
                resourceName: 'sponsor'
            }
        },

        methods: {
            showAddFormHook: function () {
                this.setupUploadField();
            },
            showEditFormHook: function (resource) {
                this.upload = resource.logo;
                this.setupUploadField();
            },
            clearValues: function () {
                this.resource = this.reset;
                this.upload.id = null;
                this.status.errors = null;
            },
        },

        beforeCreate: function () {
            axios.get('/user/sponsors')
                .then(response => {
                    this.resources = response.data;
                });
        }
    }

</script>