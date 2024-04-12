<template>
    <div>
        <a href="#modal-refund" uk-toggle>Request Refund</a>

        <div id="modal-refund" class="uk-flex-top" uk-modal>

            <div class="uk-modal-dialog">
                <button class="uk-modal-close-default" type="button" uk-close></button>
                <div class="uk-modal-header">
                    <h2 class="uk-modal-title">Refund</h2>
                </div>
                <form class="uk-form uk-form-stacked" @submit.prevent="refund($event)">
                    <div class="uk-modal-body">

                            <label for="amount-to-refund" class="uk-form-label">Amount to Refund</label>
                            <input id="amount-to-refund" type="text" name="amount" v-model="amount" class="uk-form-small uk-input">
                    </div>
                    <div class="uk-modal-footer uk-text-right">
                        <button class="uk-button uk-button-primary uk-button-small" type="submit">Refund</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'transfer'
        ],

        data: function () {
            return {
                amount: 0
            }
        },

        methods: {
            refund: function (event) {
                let form = $(event.target);

                // send refund for amount with transfer id
                axios.post('/order/refund/' + this.transfer.id, form.serialize())
                    .then(response => {
                        UIkit.modal('#modal-refund').toggle();
                        UIkit.notification({
                            message: 'Refund request submitted. Please allow 24-48 hours to process.',
//                            timeout: 1000
                        });
                    })
            }
        }
    }
</script>