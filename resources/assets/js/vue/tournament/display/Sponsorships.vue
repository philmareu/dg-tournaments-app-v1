<template>
    <div>
        <div>

            <button class="uk-button uk-button-primary uk-button-small uk-width-1-1" type="button" uk-toggle="target: #modal-sponsorships">Be A Sponsor</button>

            <div id="modal-sponsorships" uk-modal>
                <div class="uk-modal-dialog">
                    <button class="uk-modal-close-default" type="button" uk-close></button>
                    <div class="uk-modal-header">
                        <h2 class="uk-modal-title">Sponsorships</h2>
                    </div>
                    <div class="uk-modal-body">
                        <p v-if="! stripeConnected && ! this.paypal()">If interested in any of these sponsorships, please contact the director.</p>

                        <div v-for="sponsorship in sponsorships" class="uk-margin" v-if="sponsorship.quantity > 0">
                            <h3>{{ sponsorship.title }} Sponsorship (${{ sponsorship.cost_in_dollars }} / {{ sponsorship.quantity }} remaining)</h3>
                            <p>{{ sponsorship.description }}</p>
                            <div v-show="status.thinking" class="uk-flex uk-flex-center">
                                <div uk-spinner></div>
                            </div>
                            <button v-if="stripeConnected && ! status.thinking" @click="addToCart(sponsorship)" class="uk-button uk-button-primary uk-button-small">Add to Order</button>
                            <div v-if="paypal()">
                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post">

                                    <!-- Identify your business so that you can collect the payments. -->
                                    <input type="hidden" name="business" :value="tournament.paypal">

                                    <!-- Specify a Buy Now button. -->
                                    <input type="hidden" name="cmd" value="_xclick">

                                    <!-- Specify details about the item that buyers will purchase. -->
                                    <input type="hidden" name="item_name" :value="sponsorship.title">
                                    <input type="hidden" name="amount" :value="sponsorship.cost_in_dollars">
                                    <input type="hidden" name="currency_code" value="USD">

                                    <!-- Display the payment button. -->
                                    <input type="image" name="submit" border="0"
                                           src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_buynow_107x26.png"
                                           alt="Buy Now">
                                    <img alt="" border="0" width="1" height="1"
                                         src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {

        props: [
            'tournament'
        ],

        data: function() {
            return {
                sponsorships: this.tournament.sponsorships,
                stripeConnected: null,
                status: {
                    thinking: false
                }
            }
        },

        methods: {
            addToCart: function(sponsorship) {
                this.status.thinking = true;
                axios.put('/order/sponsorships/' + sponsorship.id)
                    .then(response => {
                        this.$emit('order-updated', response.data);
                        UIkit.notification({
                            message: 'Added to cart!',
                            pos: 'bottom-center'
                        });
                        this.status.thinking = false;
                    })
                    .catch(error => {

                    });
            },
            showAddSponsorForm: function (sponsorship) {
                UIkit.modal('#modal-sponsorship-sponsors-' + sponsorship.id).toggle();
            },
            paypal: function () {
                return this.tournament.paypal !== null;
            }
        },

        mounted: function() {
            axios.get('/tournament/' + this.tournament.id)
                .then(response => {
                    this.stripeConnected = response.data.can_except_online_payments;
                });
        }
    }
</script>