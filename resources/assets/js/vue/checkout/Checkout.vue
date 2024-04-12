<template>
    <div>
        <div v-if="isPaid">
            <div class="uk-card uk-card-default uk-card-small uk-margin-bottom">
                <div class="uk-card-body uk-padding-large uk-text-center">
                    <span uk-icon="icon: star; ratio: 4;"></span>
                    <p>Your payment was successful. Thanks!</p>
                </div>
            </div>
        </div>

        <div v-if="processingPayment">
            <div class="uk-card uk-card-default uk-card-small uk-margin-bottom">
                <div class="uk-card-body uk-padding-large uk-text-center">
                    <div uk-spinner></div>
                    <p>Processing Payment</p>
                </div>
            </div>
        </div>

        <div v-show="order !== null && ! processingPayment">
            <div class="uk-margin">
                <span :class="{ 'uk-text-muted' : panel !== 'order' }">Order <span class="uk-margin-left uk-margin-right">&bull;</span> </span>
                <span :class="{ 'uk-text-muted' : panel !== 'details' }">Details <span class="uk-margin-left uk-margin-right">&bull;</span></span>
                <span :class="{ 'uk-text-muted' : panel !== 'pay' }"><span>Confirm & Pay</span></span>
            </div>

            <div v-if="panel === 'order'">
                <div v-if="hasOrder">
                    <h3 class="section-header">Order</h3>

                    <div class="uk-card uk-card-default uk-card-small uk-margin-bottom">
                        <div class="uk-card-header">
                            <h3 class="uk-card-title">Sponsorships</h3>
                        </div>
                        <div class="uk-card-body">
                            <sponsorships :sponsorships="order.sponsorships"
                                          v-on:order-updated="updateOrder"></sponsorships>
                        </div>
                    </div>

                    <a href="#" class="uk-button uk-button-primary uk-button-small" @click="show('details')">Details <span uk-icon="icon: arrow-right"></span></a>
                </div>
            </div>

            <div v-if="panel === 'details'">

                <form id="details-form" class="uk-form">
                    <input type="hidden" name="unique" v-model="order.unique">

                    <h3 class="section-header">Customer Information</h3>
                    <div class="uk-card uk-card-default uk-card-small uk-margin-bottom">
                        <div class="uk-card-header">
                            <h3 class="uk-card-title">Basic</h3>
                        </div>
                        <div class="uk-card-body">
                            <p v-if="user === null">Already have an account? <a href="#" @click.prevent="login">Login</a></p>

                            <div v-if="errors != null" class="uk-alert-danger" uk-alert>
                                <a class="uk-alert-close" @click.prevent="closeAlert()" uk-close></a>
                                <p v-text="errors"></p>
                            </div>

                            <input type="email" name="email" v-model="fields.email" placeholder="Email" class="uk-input">

                            <div class="uk-margin uk-grid">
                                <div class="uk-width-1-2">
                                    <input type="text" name="first_name" v-model="fields.first_name" placeholder="First name" class="uk-input">
                                </div>
                                <div class="uk-width-1-2">
                                    <input type="text" name="last_name" v-model="fields.last_name" placeholder="Last name" class="uk-input">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="uk-card uk-card-default uk-card-small uk-margin-bottom" v-if="user === null">
                        <div class="uk-card-header">
                            <h3 class="uk-card-title"><label for="create-account-action"><input id="create-account-action" type="checkbox" name="create_account" v-model="customer.create_account" value="1" class="uk-checkbox"> Create Account</label></h3>
                        </div>
                        <div class="uk-card-body">
                            <div v-if="customer.create_account != '1'">
                                <p>Create an account and make it easier to checkout, save tournaments and more.</p>
                            </div>
                            <div class="uk-margin" v-if="customer.create_account == '1'">
                                <input type="password" name="password" v-model="customer.password" placeholder="Password" class="uk-input">
                            </div>
                        </div>
                    </div>
                </form>

                <a href="#" @click="processDetails()" class="uk-button uk-button-primary uk-button-small" :disabled="! detailsCompleted">Confirm & Pay <span uk-icon="icon: arrow-right"></span></a>
            </div>

            <div v-show="panel === 'pay'">
                <h3 class="section-header">Confirm and Pay</h3>
                <div class="uk-card uk-card-default uk-card-small uk-margin-bottom" v-if="order !== null">
                    <div class="uk-card-header">
                        <h2 class="uk-card-title">Customer</h2>
                    </div>
                    <div class="uk-card-body">
                        {{ order.first_name }} {{ order.last_name }} <br>
                        {{ order.email }}
                    </div>
                </div>

                <div class="uk-card uk-card-default uk-card-small uk-margin-bottom" v-if="order !== null">
                    <div class="uk-card-header">
                        <h2 class="uk-card-title">Total</h2>
                    </div>
                    <div class="uk-card-body">
                        ${{ order.total_in_dollars }}
                    </div>
                </div>

                <div class="uk-card uk-card-default uk-card-small uk-margin-bottom">
                    <div class="uk-card-header">
                        <h2 class="uk-card-title">Payment Information</h2>
                    </div>
                    <div class="uk-card-body">
                        <cards v-if="user !== null" v-on:payment-method-updated="updatePaymentMethod"></cards>

                        <label for="payment-method-option-cc"><input id="payment-method-option-cc" type="radio" name="payment_method" v-model="paymentMethod" value="new"> Credit or debit card</label>
                        <div v-show="paymentMethod === 'new'" class="uk-margin">
                            <div id="card-element">
                                <!-- a Stripe Element will be inserted here. -->
                            </div>
                            <!-- Used to display Element errors -->
                            <div id="card-errors" role="alert"></div>

                            <div v-if="showSavePaymentMethodOption" class="uk-margin">
                                <label for="save-payment-option"><input id="save-payment-option" type="checkbox" v-model="savePaymentMethod" value="1"> Save Payment Method</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="uk-margin uk-text-right">
                    <button type="button" @click="makePayment" class="uk-button uk-button-primary uk-button-small">Pay $<span v-text="totalInDollars"></span></button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import Cards from './Cards.vue';
    import Sponsorships from './Sponsorships.vue';

    export default {

        props: [
            'order',
            'user'
        ],

        components: {
            Cards,
            Sponsorships
        },

        data: function() {
            return {
                stripe: Stripe(stripePublishableKey),
                card: null,
                panel: 'order',
                errors: null,
                isPaid: false,
                loginUrl: SITE_URL + '/login',
                paymentMethod: null,
                savePaymentMethod: 0,
                customer: {
                    email: null,
                    name: {
                        first: null,
                        last: null
                    },
                    create_account: 0,
                    password: null
                },
                thinking: false,
                processingPayment: false
            }
        },

        methods: {
            login: function () {
                UIkit.modal('#modal-login').toggle();
            },
            updatePaymentMethod: function(element) {
                this.paymentMethod = element.id;
            },
            updateOrder: function (order) {
                this.$emit('order-updated', order);
            },
            show: function (panel) {
                this.panel = panel;
            },
            processDetails: function () {
                // Disable button show spinner

                // Validate fields
                axios.put('/order/checkout/details', $('form#details-form').serialize())
                .then(response => {
                    this.$emit('order-updated', response.data);
                    if(this.customer.create_account && response.data.user !== null) this.$emit('user-updated');
                    this.show('pay');
                })
                .catch(error => {
                    this.displayErrors(error.response.data.errors);
                });
            },
            userWantsToSavePayment() {
                return this.user !== null && this.savePaymentMethod && this.paymentMethod === 'new';
            },
            userHasStripeAccount() {
                return this.user.stripe_customer_id !== null
            },
            userSelectedExistingCard() {
                return this.user !== null && this.paymentMethod !== 'new';
            },
            makePayment: function () {
                this.processingPayment = true;

                window.scrollTo(0, 0);

                if(this.userWantsToSavePayment()) {

                    if(this.userHasStripeAccount()) {
                        this.getStripeToken(this.addCard);
                    } else {
                        this.getStripeToken(this.createCustomer);
                    }

                } else if (this.userSelectedExistingCard()) {
                    this.charge({source: this.paymentMethod});
                } else {
                    let vm = this;
                    this.getStripeToken(function(token) {
                        vm.charge({source: token});
                    });
                }
            },
            createCustomer: function (token) {
                axios.post('/user/stripe/customer', {token: token})
                .then(response => {
                    this.charge({customer: response.data.id, token: token});
                })
                .catch(error => {
                });
            },
            addCard: function (token) {
                axios.post('/user/stripe/card', {token: token})
                .then(response => {
                    this.charge({source: response.data.id});
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
            charge: function (source) {
                axios.post('/order/checkout/pay', {source: source, unique: this.order.unique})
                .then(response => {
                    if(response.data.paid === 1) {
                        this.$emit('order-updated', null);
                        this.processingPayment = false;
                        this.isPaid = true;
                    }
                })
                .catch(error => {
                });
            },
            displayErrors(errors) {

                let errorString = [];

                for(let field in errors) {
                    errorString.push(errors[field][0]);
                }

                this.errors = errorString.join(' ');
                this.thinking = false;
            },
            closeAlert: function () {
                this.errors = null;
            },
        },

        computed: {
            hasOrder: function () {
                return (this.order !== null && this.order.total_quantity > 0);
            },
            totalInDollars: function () {
                return this.order !== null ? this.order.total_in_dollars : 0;
            },
            detailsCompleted: function () {
                if(this.order === null) return false;

                return (this.customer.email !== null)
                    && (this.customer.name.first !== null)
                    && (this.customer.name.last !== null);
            },
            fields: function () {

                if(this.user !== null) {
                    return {
                        email: this.user.email,
                        first_name: this.user.first_name,
                        last_name: this.user.last_name
                    }
                }

//                if(this.user !== null && this.user.recent_order !== null) {
//                    return {
//                        email: this.user.recent_order.email,
//                        first_name: this.user.recent_order.first_name,
//                        last_name: this.user.recent_order.last_name
//                    }
//                }

                return {
                    email: this.order.email,
                    first_name: this.order.first_name,
                    last_name: this.order.last_name
                }
            },
            showSavePaymentMethodOption: function () {
                return this.user !== null && this.paymentMethod === 'new';
            }
        },

        mounted: function() {

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