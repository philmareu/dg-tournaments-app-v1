<template>
    <div id="modal-login" uk-modal>
        <div class="uk-modal-dialog">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h2 class="uk-modal-title">Login</h2>
            </div>
            <div class="uk-modal-body">

                <div v-if="status.errors != null" class="uk-alert-danger" uk-alert>
                    <a class="uk-alert-close" @click.prevent="closeAlert('errors')" uk-close></a>
                    <p v-text="status.errors"></p>
                </div>

                <form class="uk-form" @submit.prevent="login" id="login">
                    <div class="uk-margin">
                        <div class="uk-inline uk-width-1-1">
                            <span class="uk-form-icon" uk-icon="icon: mail"></span>
                            <input type="email" name="email" class="uk-input" placeholder="Email">
                        </div>
                    </div>

                    <div class="uk-margin">
                        <div class="uk-inline uk-width-1-1">
                            <span class="uk-form-icon" uk-icon="icon: lock"></span>
                            <input type="password" name="password" class="uk-input uk-width-1-1" placeholder="Password">
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label for="remember"><input type="checkbox" class="uk-checkbox uk-margin-small-right" id="remember" name="remember" checked>Remember Me</label>
                        <a href="/password/reset" class="uk-align-right">Forgot Password?</a>
                    </div>

                    <div v-show="status.thinking" class="uk-flex uk-flex-center uk-width-1-1">
                        <div uk-spinner></div>
                    </div>

                    <button class="uk-button uk-button-primary uk-width-1-1" type="submit" v-if="! status.thinking">Login</button>
                </form>
            </div>
            <div class="uk-modal-footer">
                <p class="uk-text-center">Need an account? <a href="/register">Sign Up</a></p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        props: ['show'],

        data: function() {
            return {
                thinking: false,
                status: {
                    thinking: false,
                    errors: null
                },
            }
        },

        methods: {
            login: function() {

                this.status.thinking = true;

                axios.post('/login', $('form#login').serialize())
                    .then(response => {
                        this.$emit('user-authenticated');
                        UIkit.modal('#modal-login').toggle();
                        UIkit.notification({
                            message: 'Login successful',
                            timeout: 1000
                        });
                    })
                    .catch(error => {
                        this.displayErrors(error.response.data);
                    });
            },
            displayErrors(errors) {
                let errorString = [];

                for(let field in errors) {
                    if(Array.isArray(errors[field])) {
                        errorString.push(errors[field][0]);
                    } else {
                        errorString.push(errors[field]);
                    }
                }

                this.status.errors = errorString.join(' ');
                this.status.thinking = false;
            },
            closeAlert: function () {
                this.status.errors = null;
            },
        },

        computed: {

        },

        mounted: function() {
        }
    }
</script>