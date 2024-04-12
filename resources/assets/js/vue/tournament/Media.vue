<template>
    <div class="uk-card uk-card-default uk-card-small uk-card-body">
        <div class="uk-grid">
            <div class="uk-width-1-2@s">
                <basic-form :buttons="buttons" :status="status" v-on:submitted="processForm">
                    <input type="hidden" name="id" v-model="resource.id">
                    <input type="hidden" name="uploaded_id" v-model="upload.id">

                    <upload-html :resourceName="resourceName"></upload-html>

                    <form-row id="upload-title">
                        <span slot="label">Title<span class="uk-text-danger">*</span></span>
                        <input type="text" name="title" id="upload-title" v-model="resource.title" class="uk-input uk-form-small">
                    </form-row>

                    <div class="uk-margin" v-if="resource.id !== null">
                        <span v-text="resource.title"></span>
                    </div>
                </basic-form>
            </div>
            <div class="uk-width-1-2@s">
                <ul class="uk-list uk-list-divider" v-if="resources.length">
                    <li v-for="upload in resources">
                        <a :href="getUrl(upload)" v-html="upload.title"></a>
                        <div class="uk-align-right">
                            <a href="#" uk-icon="icon: file-edit" @click.prevent="showEditForm(upload)"></a>
                            <a href="#" uk-icon="icon: trash" @click.prevent="destroy(upload)"></a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    import Form from '../mixins/form/manage';
    import Upload from '../mixins/form/upload';
    import UploadHtml from '../components/Upload.vue';

    export default {
        
        components: {
            UploadHtml
        },

        mixins: [
            Form,
            Upload
        ],

        data: function() {
            return {
                storeId: tournament.id,
                resources: tournament.media,
                resource: {
                    id: null,
                    upload_id: null
                },
                reset: {
                    id: null,
                    upload_id: null
                },
                modal: '#modal-media',
                endpoint: '/tournament/media',
                icon: '<span uk-icon="icon: file"></span>',
                resourceName: 'media',
                allow: '*.(jpg|jpeg|png|pdf)',
            }
        },

        methods: {
            getUrl: function (upload) {
                return SITE_URL + '/storage/' + upload.filename;
            },
            showEditForm: function (resource) {
                this.resource = resource;
                this.upload = resource;
                this.status = {
                    thinking: false,
                    errors: null,
                    success: null
                };
                this.setupUploadField();
            },
            responseProcessedHook: function (response) {
                this.resources = response.data;
                this.upload.id = null;
            },
            uploadProcessedHook: function (upload) {
                this.resource.title = upload.title;
            },
            destroyPath: function (upload) {
                return this.endpoint + '/' + tournament.id + '/' + upload.id;
            }
        },
        computed: {
            updatePath: function () {
                return this.endpoint + '/' + tournament.id;
            }
        },
        mounted: function () {
            this.setupUploadField();
        }
    }
</script>