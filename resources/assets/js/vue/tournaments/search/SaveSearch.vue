<template>
    <div>

        <div class="filter uk-margin">
            <h2 class="uk-margin-remove">Saved Searches</h2>

            <div v-if="resources.length === 0">
                <p>Save your search settings for convenience and optionally get notifications of new tournaments.</p>
            </div>

            <div v-for="search in resources" class="uk-margin">
                <ul class="uk-iconnav">
                    <li><a href="#" uk-icon="icon: file-edit" @click.prevent="showEditForm(search)"></a></li>
                    <li><a href="#" uk-icon="icon: trash" @click.prevent="destroy(search)"></a></li>
                    <li><a href="#" @click.prevent="loadSearch(search)">{{ search.title }}</a></li>
                </ul>
            </div>

            <a href="#" @click.prevent="showAddForm" class="uk-button uk-button-default uk-button-small">Save Search Settings</a>

        </div>

        <hr>




        <!--<div class="uk-card uk-card-default uk-card-small uk-margin-bottom" v-for="search in resources">-->
            <!--<div class="uk-card-header">-->
                <!--<ul class="uk-iconnav">-->
                    <!--<li><a href="#" uk-icon="icon: file-edit" @click.prevent="showEditForm(search)"></a></li>-->
                    <!--<li><a href="#" uk-icon="icon: trash" @click.prevent="destroy(search)"></a></li>-->
                <!--</ul>-->
            <!--</div>-->

            <!--<div class="uk-card-body">-->
                <!--<div class="uk-card-badge" v-if="search.wants_notification" uk-icon="icon: bell;"></div>-->
                <!--<h3 class="uk-card-title"></h3>-->
                <!--&lt;!&ndash;<a href="#" @click.prevent="loadSearch(search)">{{ search.title }}</a>&ndash;&gt;-->
            <!--</div>-->
        <!--</div>-->

        <div></div>

        <form-modal id="modal-save-search" :buttons="buttons" :status="status" v-on:submitted="processForm" v-on:closing="closeModal">
            <span slot="title">Save Search</span>
            <input type="hidden" name="url" v-model="resource.url">

            <form-row id="search-title">
                <span slot="label">Title</span>
                <input type="text" name="title" id="search-title-field" v-model="resource.title" class="uk-input">
            </form-row>

            <div class="uk-margin">
                <form-row id="search-wants-notification">
                    <span slot="label">Send notification</span>
                    <input type="checkbox" name="wants_notification" id="search-wants-notification-field" v-model="resource.wants_notification" class="uk-checkbox" value="1">
                </form-row>
            </div>

            <div class="uk-margin">
                <form-row id="search-frequency">
                    <span slot="label">How often to check for tournaments?</span>
                    <select name="frequency" v-model="resource.frequency">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                    </select>
                </form-row>
            </div>
        </form-modal>
    </div>
</template>

<script>

    import FormModal from '../../components/FormModal.vue';
    import FormRow from '../../components/FormRow.vue';

    import User from '../../mixins/tournament/user';
    import Form from '../../mixins/form/manage';

    export default {

        props: [
            'user'
        ],

        components: {
            FormModal,
            FormRow
        },

        mixins: [
            User,
            Form
        ],

        data: function () {
            return {
                storeId: null,
                resources: this.user.searches,
                resource: {
                    id: null,
                    title: null,
                    url: null,
                    wants_notification: true,
                    frequency: 'daily'
                },
                reset: {
                    id: null,
                    title: null,
                    url: null,
                    wants_notification: 1,
                    frequency: 'daily'
                },
                modal: '#modal-save-search',
                endpoint: '/user/searches',
            }
        },

        methods: {
            showAddFormHook: function () {
                this.resource.url = window.location.href;
            },
            loadSearch: function (search) {

                axios.put('/cache/bounds', {north: search.north, east: search.east, south: search.south, west: search.west})
                    .then(response => {
                        window.location = this.searchUrl(search.query);
                    })
                    .catch(error => {
                    });
            },
            searchUrl: function (query) {
                return SITE_URL + '/search?' + query;
            }
        }
        
    }

</script>