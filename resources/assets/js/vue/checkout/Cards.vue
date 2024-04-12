<template>
    <div>
        <div v-for="card in cards">
            <input type="radio" name="payment_method" @click="paymentMethodSelected(card)"> **** **** **** {{ card.last4 }} - {{ card.brand }}
        </div>
    </div>
</template>

<script>
    export default {

        data: function() {
            return {
                cards: []
            }
        },

        methods: {
            paymentMethodSelected: function(element) {
                this.$emit('payment-method-updated', element);
            }
        },

        computed: {

        },

        mounted: function() {
            axios.get('/user/credit-cards')
            .then(response => {
                this.cards = response.data.data;
            })
        }
    }
</script>