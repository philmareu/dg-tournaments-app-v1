<template>
    <div>

        <div class="uk-width-1-6@s">
            <basic-form :buttons="buttons" :status="status" v-on:submitted="processForm">
                <form-row>
                    <span slot="label">Select Sponsor</span>

                    <select name="sponsor_id" v-model="resource.sponsor_id" class="uk-select">
                        <option disabled value="">Please select one</option>
                        <option v-for="sponsor in sponsorLibrary" :value="sponsor.id">{{ sponsor.title }}</option>
                    </select>
                </form-row>
            </basic-form>
        </div>

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
                        <h3>{{ sponsor.sponsor.title }}</h3>
                        <div v-if="sponsor.sponsor.url !== null">
                            URL: {{ sponsor.sponsor.url }}
                        </div>
                        <div v-if="sponsor.sponsor.upload_id !== null" class="uk-margin">
                            <img :src="logoUrl(sponsor.sponsor.logo, 'poster-small')" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import Form from '../mixins/form/manage';

    export default {

        props: [
            'sponsorship'
        ],

        mixins: [
            Form
        ],

        data: function() {
            return {
                debug: true,
                storeId: this.sponsorship.id,
                resources: this.sponsorship.tournament_sponsors,
                resource: {
                    id: null,
                    sponsor_id: null,
                    approved_at: null
                },
                reset: {
                    id: null,
                    sponsor_id: null,
                    approved_at: null
                },
                modal: '#modal-sponsorship-sponsors-' + this.sponsorship.id,
                endpoint: '/tournament/sponsorship/sponsors',
                sponsorLibrary: []
            }
        },

        methods: {
            logoUrl: function (logo) {
                return SITE_URL + '/images/original/' + logo.filename;
            },
            modalId: function () {
                return 'modal-sponsorship-sponsors-' + this.storeId;
            }
        },
        mounted: function() {
            axios.get('/user/sponsors')
                .then(response => {
                    this.sponsorLibrary = response.data;
                });
        }
    }
</script>