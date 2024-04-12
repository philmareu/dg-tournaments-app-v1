<template>
    <div>

        <div v-if="deleted" class="uk-alert uk-alert-success">
            <p>This sponsorship has been removed from tournament.</p>
        </div>
        <div  v-else="">

            <div class="uk-card uk-card-default uk-card-small uk-margin">
                <div class="uk-card-header">
                    <ul class="uk-iconnav">
                        <li><a href="#" uk-icon="icon: file-edit" @click.prevent="showEditForm(sponsorship)"></a></li>
                        <li><a href="#" uk-icon="icon: trash" @click.prevent="destroy(sponsorship)"></a></li>
                        <li>{{ sponsorship.title }}</li>
                    </ul>
                </div>

                <div class="uk-card-body">
                    Info about sponsorships
                </div>
            </div>

            <h2>Sponsors</h2>
            <p>Add sponsors from your <a :href="url('manage/sponsors')">sponsors library</a> to this sponsorship level.</p>
            <sponsors :sponsorship="resource"></sponsors>

            <form-modal id="modal-sponsorship" :buttons="buttons" :status="status" v-on:submitted="processForm" v-on:closing="closeModal">
                <span slot="title">Sponsorship Information</span>

                <div class="uk-margin">
                    <label for="sponsor-title" class="uk-form-label">Title <span class="uk-text-danger">*</span></label>
                    <div class="uk-form-controls">
                        <input type="text" name="title" id="sponsor-title" v-model="resource.title" class="uk-input uk-form-small">
                    </div>
                </div>

                <div class="uk-grid">
                    <div class="uk-width-1-3@s">
                        <form-row>
                            <span slot="label">Tier (1, 2, 3, etc.) <span class="uk-text-danger">*</span></span>
                            <input type="text" name="tier" v-model="resource.tier" class="uk-input uk-form-small">
                        </form-row>
                    </div>
                    <div class="uk-width-1-3@s">
                        <form-row id="sponsor-url">
                            <span slot="label">Cost (USD) <span class="uk-text-danger">*</span></span>
                            <input type="text" name="cost_in_dollars" v-model="resource.cost_in_dollars" class="uk-input uk-form-small">
                        </form-row>
                    </div>
                    <div class="uk-width-1-3@s">
                        <form-row id="sponsor-url">
                            <span slot="label">Quantity <span class="uk-text-danger">*</span></span>
                            <input type="text" name="quantity" v-model="resource.quantity" class="uk-input uk-form-small">
                        </form-row>
                    </div>
                </div>

                <div class="uk-margin">
                    <form-row id="sponsor-url">
                        <span slot="label">Description</span>
                        <textarea name="description" class="uk-textarea" v-model="resource.description"></textarea>
                    </form-row>
                </div>
            </form-modal>
        </div>
    </div>
</template>

<script>
    import Sponsors from './Sponsors.vue';
    import Helpers from '../mixins/helpers';
    import Form from '../mixins/form/manage';

    export default {

        props: [
            'sponsorship'
        ],

        mixins: [
            Form,
            Helpers
        ],

        components: {
            Sponsors
        },

        data: function() {
            return {
                tournament: tournament,
                storeId: tournament.id,
                resources: null,
                resource: this.sponsorship,
                reset: {
                    id: null,
                    title: null,
                    tier: null,
                    quantity: null,
                    cost: null,
                    description: null
                },
                modal: '#modal-sponsorship',
                endpoint: '/tournament/sponsorships',
                deleted: false
            }
        },

        methods: {
            resourceDeletedEvent: function(response) {
                this.deleted = true;
            }
        }
    }
</script>