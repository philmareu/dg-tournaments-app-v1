<template>
    <div>
        <button type="button"
                class="uk-button uk-button-small uk-width-1-1"
                v-if="isFollowing"
                @click="follow"><span v-if="! thinking"><span uk-icon="icon: check;" class="uk-margin-small-right"></span>Following</span><div v-if="thinking" uk-spinner></div></button>

        <button type="button" class="uk-button uk-button-small uk-button-primary uk-width-1-1"
                v-if="! isFollowing"
                @click="follow"><span v-if="! thinking">Follow</span><div v-if="thinking" uk-spinner></div></button>
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
                thinking: false
            }
        },

        methods: {
            follow: function() {
                if(! this.authenticated()) {
                    UIkit.modal('#modal-login').toggle();
                } else {
                    this.thinking = true;
                    axios.put('/user/follow/tournament/' + this.tournament.id)
                        .then(response => {
                            this.$emit('user-updated');
                        })
                        .catch(error => {

                        });
                }
            }
        }
    }
</script>