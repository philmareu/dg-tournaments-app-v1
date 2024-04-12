<template>
    <div>
        <button type="button"
                class="uk-button uk-button-primary uk-width-1-1"
                v-if="! submitted"
                @click="approve"><span v-if="! thinking">Approve</span><div v-if="thinking" uk-spinner></div></button>

        <p class="uk-alert" v-if="submitted">Claim has been approved.</p>
    </div>
</template>

<script>
    export default {

        data: function() {
            return {
                thinking: false,
                submitted: false
            }
        },

        methods: {
            approve: function() {
                this.thinking = true;
                axios.post('/tournament/claim/confirm/' + claim.token)
                    .then(response => {
                        this.submitted = true;
                    })
                    .catch(error => {

                    });
            }
        }
    }
</script>