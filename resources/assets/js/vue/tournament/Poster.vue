<template>
    <div class="uk-grid">
        <div class="uk-width-1-3@s">
            <img :src="posterUrl" :alt="upload.alt">
        </div>

        <div class="uk-width-2-3@s">
            <upload-html :resourceName="resourceName"></upload-html>
            <button type="button" class="uk-button uk-button-danger uk-button-small" @click="destroy" v-if="upload.id !== null">Delete</button>
        </div>
    </div>
</template>

<script>
    import Upload from '../mixins/form/upload';
    import UploadHtml from '../components/Upload.vue';
    import Modal from '../components/Modal.vue';

    export default {

        components: {
            UploadHtml,
            Modal
        },

        mixins: [
            Upload
        ],

        data: function() {
            return {
                modalId: 'modal-poster',
                resourceName: 'poster',
                thinking: false,
                upload: tournament.poster
            }
        },

        methods: {
            showFullPoster: function() {
                UIkit.modal('#modal-full-poster').toggle();
            },
            hideFullPoster: function() {
                UIkit.modal('#modal-full-poster').toggle();
            },
            uploadProcessedHook: function (upload) {

                axios.put('/tournament/poster/' + tournament.id, {upload_id: upload.id})
                    .then(response => {
                        UIkit.modal('#' + this.modalId).toggle();
                    });
            },
            destroy: function () {
                axios.delete('/tournament/poster/' + tournament.id)
                    .then(response => {
                        this.upload = response.data;
                        UIkit.modal('#' + this.modalId).toggle();
                    })
                    .catch(error => {

                    });
            }
        },

        computed: {
            posterUrl: function () {
                return SITE_URL + '/images/original/' + this.upload.filename;
            },
            fullSizeUrl: function () {
                return SITE_URL + '/images/original/' + this.upload.filename;
            }
        },

        mounted: function() {
            this.setupUploadField();
        }
    }
</script>