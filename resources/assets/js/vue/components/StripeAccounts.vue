<template>
    <div>
        <div class="uk-grid uk-child-width-1-3@s">
            <div v-for="account in accounts">
                <div class="uk-card uk-card-default uk-card-small uk-margin-bottom">
                    <div class="uk-card-header">
                        <ul class="uk-iconnav">
                            <li><a href="#" uk-icon="icon: trash" @click.prevent="destroy(account)"></a></li>
                        </ul>
                    </div>

                    <div class="uk-card-body">
                        <h3 class="uk-card-title">{{ account.display_name }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {

        props: [
            'accounts'
        ],

        data: function () {
            return {
            }
        },

        methods: {
            destroy: function (account) {
                axios.delete('/user/stripe/' + account.id)
                    .then(response => {
                        this.accounts = response.data;
                    });
            }
        }
    }

</script>