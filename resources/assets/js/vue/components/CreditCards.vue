<template>
    <div>
        <div v-if="thinking" class="uk-padding-large uk-flex uk-flex-center" uk-spinner></div>

        <div v-show="thinking === false">
            <table class="uk-table uk-table-condensed uk-table-small uk-table-striped">
                <thead>
                <tr>
                    <th>Brand</th>
                    <th>Last 4</th>
                    <th>Exp.</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="card in cards">
                    <td>{{ card.brand }}</td>
                    <td>{{ card.last4 }}</td>
                    <td>{{ card.exp_month }}/{{ card.exp_year }}</td>
                    <td><a href="#" uk-icon="icon: trash;" @click.prevent="remove(card)"></a></td>
                </tr>
                </tbody>
            </table>

            <h3>Add Card</h3>

            <div class="uk-width-1-1 uk-width-1-2@s">
                <div id="card-element" class="uk-margin">
                    <!-- a Stripe Element will be inserted here. -->
                </div>
                <!-- Used to display Element errors -->
                <div id="card-errors" role="alert"></div>

                <button type="button" @click="add" class="uk-button uk-button-primary uk-button-small">Add Card</button>
            </div>
        </div>
    </div>
</template>

<script>

    export default {

        props: [
            'user'
        ],

        data: function() {
            return {
                thinking: true,
                cards: [],
                stripe: Stripe(stripePublishableKey),
            }
        },

        methods: {
            add: function () {
                this.thinking = true;
                if(this.userHasStripeAccount()) {
                    this.getStripeToken(this.addCard);
                } else {
                    this.getStripeToken(this.createCustomer);
                }
            },
            createCustomer: function (token) {
                axios.post('/user/stripe/customer', {token: token})
                    .then(response => {
                        this.getCreditCards();
                    })
                    .catch(error => {
                    });
            },
            addCard: function (token) {
                axios.post('/user/stripe/card', {token: token})
                    .then(response => {
                        this.getCreditCards();
                        this.card.clear();
                    });
            },
            getStripeToken: function (callback) {

                var vm = this;
                this.stripe
                    .createToken(this.card)
                    .then(function(result) {
                        if (result.error) {
                            // Inform the user if there was an error
                            var errorElement = document.getElementById('card-errors');
                            errorElement.textContent = result.error.message;
                        } else {
                            callback(result.token.id);
                        }
                    });
            },
            remove: function(card) {

                this.thinking = true;

                axios.delete('/user/stripe/card/' + card.id)
                    .then(response => {
                        this.getCreditCards();
                    })
            },
            userHasStripeAccount() {
                return this.user.stripe_customer_id !== null
            },
            getCreditCards: function () {
                axios.get('/user/credit-cards')
                    .then(response => {
                        this.cards = response.data.data;
                        this.thinking = false;
                    });
            }
        },

        mounted: function() {

            this.getCreditCards();

            var elements = this.stripe.elements();

            // Custom styling can be passed to options when creating an Element.
            var style = {
                base: {
                    // Add your base input styles here. For example:
                    fontSize: '16px',
                    lineHeight: '24px'
                }
            };

            // Create an instance of the card Element
            this.card = elements.create('card', {style: style});

            // Add an instance of the card Element into the `card-element` <div>
            this.card.mount('#card-element');

            this.card.addEventListener('change', function(event) {
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });
        }
    }

</script>