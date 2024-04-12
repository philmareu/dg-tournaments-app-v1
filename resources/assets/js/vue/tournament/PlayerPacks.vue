<template>
    <div>
        <div class="uk-card uk-card-default uk-card-small uk-card-body uk-margin">
            <basic-form :buttons="buttons" :status="status" v-on:submitted="processForm">

                <form-row id="link-title">
                    <span slot="label">Player Pack Title (ex. Ams, Before Deadline, etc.) <span class="uk-text-danger">*</span></span>
                    <input type="text" name="title" v-model="resource.title" class="uk-input uk-form-small">
                </form-row>

                <form-row id="link-title">
                    <span slot="label">Description (not items)</span>
                    <textarea name="description" v-model="resource.description" class="uk-textarea"></textarea>
                </form-row>

            </basic-form>
        </div>
        <div class="uk-grid uk-child-width-1-2@s">
            <div v-for="playerPack in resources">
                <div class="uk-margin uk-card uk-card-default uk-card-small uk-card-body">
                    <h4 v-text="playerPack.title"></h4>
                    <ul class="uk-iconnav">
                        <li><a href="#" uk-icon="icon: file-edit" @click.prevent="showEditForm(playerPack)"></a></li>
                        <li><a href="#" uk-icon="icon: trash" @click.prevent="destroy(playerPack)"></a></li>
                    </ul>
                    <p v-text="playerPack.description"></p>

                    <player-pack-items :player-pack="playerPack"></player-pack-items>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PlayerPackItems from './PlayerPackItems.vue';
    import Form from '../mixins/form/manage';

    export default {

        mixins: [
            Form
        ],

        components: {
            PlayerPackItems
        },

        data: function() {
            return {
                storeId: tournament.id,
                modal: '#modal-player-packs',
                endpoint: '/tournament/player-packs',
                resource: {
                    id: null,
                    title: null,
                    description: null
                },
                reset: {
                    id: null,
                    title: null,
                    description: null
                },
                resources: tournament.player_packs
            }
        },

        methods: {
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