<template>
    <div v-if="canClaim">
        <button type="button"
                class="uk-button uk-button-default uk-button-small uk-width-1-1"
                @click="claim"><span v-if="! thinking">Claim</span><div v-if="thinking" uk-spinner></div></button>

        <div id="modal-claim" uk-modal>
            <div class="uk-modal-dialog">
                <button class="uk-modal-close-default" type="button" uk-close></button>
                <div class="uk-modal-header">
                    <h2 class="uk-modal-title">Claim {{ tournament.name }}</h2>
                </div>
                <div class="uk-modal-body">
                    <p>Claiming tournaments will allow you to manage the tournament page. You can update information, take credit card payments for sponsorships and more.</p>
                </div>
                <div class="uk-modal-footer">
                    <label for="accept-claim">
                        <input type="checkbox" id="accept-claim" v-model="accepted"> I have authority to manage this tournament.
                    </label>
                    <button class="uk-button uk-button-primary uk-button-small uk-align-right" type="button" @click="submit">Submit Claim</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import User from '../mixins/tournament/user';

    export default {

        props: [
            'user',
            'tournament'
        ],

        mixins: [
            User
        ],

        data: function() {
            return {
                thinking: false,
                submitted: false,
                accepted: false
            }
        },

        methods: {
            claim: function() {
                if(! this.authenticated()) {
                    UIkit.modal('#modal-login').toggle();
                } else {

                    UIkit.modal('#modal-claim').toggle();
                }
            },
            submit: function () {

                if(this.accepted) {
                    UIkit.modal('#modal-claim').toggle();
                    this.thinking = true;
                    axios.post('/tournament/claim/' + this.tournament.id)
                        .then(response => {
                            UIkit.notification({
                                message: 'Your request to manage this tournament has been sent to the tournament\'s contact information for confirmation. It will expire in 12 hours if not confirmed.',
                                pos: 'bottom-center'
                            });
                            this.submitted = true;
                        })
                        .catch(error => {

                        });
                }
            }
        },

        computed: {
            canClaim: function() {

                if(this.submitted) return false;

                if(this.tournament.managers.length > 0) return false;

                if(this.tournament.claim_request === null) return true;

                if(typeof this.tournament.claim_request !== "undefined") return false;

                return true;
            }
        },

        mounted: function() {
        }
    }
</script>