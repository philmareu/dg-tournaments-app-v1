<template>
    <li>
        <a href="#modal-cart" uk-toggle>
            <span v-if="hasOrder" v-text="order.total_quantity" class="uk-badge"></span>
            <span uk-icon="icon: cart"></span>
        </a>

        <div id="modal-cart" class="uk-flex-top" uk-modal>
            <div class="uk-modal-dialog uk-margin-auto-vertical">
                <button class="uk-modal-close-default" type="button" uk-close></button>
                <div class="uk-modal-header">
                    <h2 class="uk-modal-title">Order</h2>
                </div>

                <div class="uk-modal-body" uk-overflow-auto>
                    <div v-if="hasOrder">
                        <sponsorships :sponsorships="order.sponsorships" v-on:order-updated="updateOrder"></sponsorships>
                    </div>
                    <div v-else>
                        <p>Order is empty.</p>
                    </div>
                </div>

                <div v-if="hasOrder" class="uk-modal-footer uk-text-right">
                    <a :href="url('checkout')" class="uk-button uk-button-primary uk-button-small">Checkout</a>
                </div>
            </div>
        </div>
    </li>
</template>

<script>

    import Sponsorships from '../checkout/Sponsorships.vue';

    import Helpers from '../mixins/helpers';

    export default {

        props: [
            'order'
        ],

        mixins: [
            Helpers
        ],

        components: {
            Sponsorships
        },

        data: function() {
            return {

            }
        },

        methods: {
            updateOrder: function (order) {
                this.$emit('order-updated', order);
            }
        },

        computed: {
            hasOrder: function () {
                return this.order !== null && this.order.total_quantity > 0;
            }
        }

    }
</script>