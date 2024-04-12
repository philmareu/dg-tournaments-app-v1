<template>
    <div class="uk-card uk-card-default uk-card-small">

        <div class="uk-card-header">
            <h3 class="uk-card-title">Stripe</h3>
        </div>

        <div class="uk-card-body">
            <p>Connect your Stripe account to this tournament and receive credit card payments for sponsorships. You can add <a
                    href="https://stripe.com">Stripe</a> account at your <a
                    :href="getUserStripeUrl()">stripe connect</a> page.</p>

            <div v-if="status.thinking" uk-spinner></div>

            <div v-if="status.thinking === false">
                <label class="uk-form-label">Select Account</label>
                <div>
                    <input type="radio" name="stripe_account_id" v-model="stripeAccountId" class="uk-radio" :value="0" @change="accountSelected($event)">
                    <span>None</span>
                </div>
                <div v-for="account in stripeAccounts">
                    <label>
                        <input type="radio" name="stripe_account_id" v-model="stripeAccountId" class="uk-radio" :value="account.id" @change="accountSelected($event)">
                        <span v-text="account.display_name"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Form from '../mixins/form/manage';

    export default {

        props: [
            'user'
        ],

        mixins: [
            Form
        ],

        data: function() {
            return {
                stripeAccountId: tournament.stripe_account_id === null ? 0 : tournament.stripe_account_id
            }
        },

        computed: {
            stripeAccounts() {
                return this.user !== null ? this.user.stripe_accounts : [];
            },
            userHasStripeAccounts: function () {
                return this.stripeAccounts.length > 0;
            },
            stripeAccountIsConnected() {
                return tournament.stripe_account_id !== null;
            },
        },

        methods: {
            connect: function (event) {

                let checked = $(event.target).prop('checked');

                if(! checked) {


                }
            },
            accountSelected: function (event) {
                this.status.thinking = true;

                let stripe_account_id = $(event.target).val();

                if(stripe_account_id === '0') {

                    axios.delete('/tournament/stripe/' + tournament.id)
                        .then(response => {
                            this.stripeAccountId = 0;
                            this.status.thinking = false;
                        })
                        .catch(error => {

                        })
                } else {
                    axios.put('/tournament/stripe/' + tournament.id, {stripe_account_id: stripe_account_id})
                        .then(response => {
                            this.status.thinking = false;
                        })
                        .catch(error => {

                        })
                }

            },
            getUserStripeUrl: function () {
                return SITE_URL + '/manage/stripe';
            }
        }
    }
</script>