<template>
    <ul class="uk-nav uk-nav-default">
        <li class="uk-nav-header"><span uk-icon="icon: star;" class="uk-margin-small-right"></span>Sponsorships</li>
        <li v-for="sponsorship in resources">
            <a :href="url('manage/' + tournament.id + '/sponsorship/' + sponsorship.id)" v-text="sponsorship.title"></a>
        </li>
        <li>
            <a href="#" @click.prevent="showAddForm()"><span uk-icon="icon: plus; ratio: .7;" class="uk-margin-small-right"></span>Add Sponsorship</a>
        </li>

        <form-modal :id="[ 'modal-sponsorships-' + _uid ]" :buttons="buttons" :status="status" v-on:submitted="processForm" v-on:closing="closeModal">
            <span slot="title">Sponsorship Information</span>
            <input type="hidden" name="course_id" v-model="sponsorshipId">

            <p>Define your sponsorship level (ex. Hole, Gold, Title, etc.). Once you have created sponsorships, you will be able to add sponsors.</p>

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

    </ul>
</template>

<script>

    import Form from '../mixins/form/manage';
    import Helpers from '../mixins/helpers';

    export default {

        mixins: [
            Form,
            Helpers
        ],

        components: {
        },

        data: function() {
            return {
                storeId: tournament.id,
                resources: tournament.sponsorships,
                resource: {
                    id: null,
                    title: null,
                    tier: null,
                    quantity: null,
                    cost: null,
                    description: null
                },
                reset: {
                    id: null,
                    title: null,
                    tier: null,
                    quantity: null,
                    cost: null,
                    description: null
                },
                modal: '#modal-sponsorships-' + this._uid,
                endpoint: '/tournament/sponsorships',
                sponsorshipId: null,
                tournament: tournament
            }
        },

        methods: {
            responseProcessedHook: function (response) {
                UIkit.modal(this.modal).toggle();
            },
        }
    }
</script>