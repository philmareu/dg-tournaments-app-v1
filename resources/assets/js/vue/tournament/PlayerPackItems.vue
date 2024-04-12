<template>
    <div>
        <basic-form :buttons="buttons" :status="status" v-on:submitted="processForm">

            <form-row id="link-title">
                <span slot="label">Pack Item<span class="uk-text-danger">*</span></span>
                <input type="text" name="title" v-model="resource.title" class="uk-input uk-form-small">
            </form-row>

        </basic-form>

        <ul class="uk-list uk-list-divider">
            <li v-for="item in resources">
                {{ item.title }}
                <div class="uk-align-right">
                    <a href="#" uk-icon="icon: file-edit" @click.prevent="showEditForm(item)"></a>
                    <a href="#" uk-icon="icon: trash" @click.prevent="destroy(item)"></a>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>

    import Form from '../mixins/form/manage';

    export default {

        props: [
            'playerPack'
        ],

        mixins: [
            Form
        ],

        data: function() {
            return {
                storeId: this.playerPack.id,
                resources: this.playerPack.items,
                resource: {
                    id: null,
                    title: null
                },
                reset: {
                    id: null,
                    title: null
                },
                modal: '#modal-player-pack-items-' + this.playerPack.id,
                endpoint: '/tournament/player-pack/items'
            }
        },

        methods: {
            modalId: function () {
                return 'modal-player-pack-items-' + this.playerPack.id;
            },
            showEditForm: function (resource) {
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