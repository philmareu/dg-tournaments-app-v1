<template>
    <div class="uk-card uk-card-default uk-card-small uk-card-body">
        <div class="uk-grid">
            <div class="uk-width-1-2">
                <basic-form :buttons="buttons" :status="status" v-on:submitted="processForm">
                    <input type="hidden" name="ordinal" value="1">

                    <label for="link-title" class="uk-form-label">Title<span class="uk-text-danger">*</span></label>
                    <div class="uk-form-controls">
                        <input type="text" name="title" id="link-title" v-model="resource.title" class="uk-input uk-form-small">
                    </div>

                    <div class="uk-margin">
                        <form-row id="link-url">
                            <span slot="label">Enter URL<span class="uk-text-danger">*</span></span>
                            <input type="text" name="url" id="link-url" v-model="resource.url" class="uk-input uk-form-small">
                        </form-row>
                    </div>

                </basic-form>
            </div>
            <div class="uk-width-1-2">
                <ul class="uk-list uk-list-divider">
                    <li v-for="link in resources">
                        <a :href="link.url" v-html="link.title"></a>
                        <div class="uk-align-right">
                            <a href="#" uk-icon="icon: file-edit" @click.prevent="showEditForm(link)"></a>
                            <a href="#" uk-icon="icon: trash" @click.prevent="destroy(link)"></a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>

    import Form from '../mixins/form/manage';

    export default {

        mixins: [Form],

        data: function() {
            return {
                storeId: tournament.id,
                resources: tournament.links,
                resource: {
                    id: null,
                    title: null,
                    url: null
                },
                reset: {
                    id: null,
                    title: null,
                    url: null
                },
                modal: '#modal-link',
                endpoint: '/tournament/links',
            }
        },

        methods: {
            showEditForm: function(resource) {
                this.resource = resource;
                this.status = {
                    thinking: false,
                    errors: null,
                    success: null
                };
            }
        }
    }
</script>