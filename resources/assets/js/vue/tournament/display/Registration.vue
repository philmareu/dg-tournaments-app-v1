<template>
    <div>
        <div v-if="registrationStatus == 'notPosted'">
            <p>Registration hasn't been posted yet.</p>
        </div>

        <div v-if="registrationStatus == 'notOpen'">
            <p>Registration opens {{ opensAt.format('MMM Do') }}</p>
        </div>

        <div v-if="registrationStatus == 'open'">
            <p>Registration is open and closes {{ closesAt.format('MMM Do') }}</p>
            <a :href="registration.url" target="_blank" class="uk-button uk-button-primary uk-button-small" v-if="registration.url !== null">Register</a>
        </div>

        <div v-if="registrationStatus == 'closed'">
            <p>Registration is closed</p>
        </div>
    </div>
</template>

<script>
    
    export default {

        props: [
            'registration'
        ],

        data: function() {
            return {
                moment: moment,
            }
        },

        computed: {
            registrationStatus: function() {

                let now = moment();

                if(this.registration.opens_at === null) return 'notPosted';

                if(this.opensAt.isAfter(now)) return 'notOpen';

                if(this.registration.closes_at === null) {
                    return 'open';
                } else if (this.closesAt.isBefore(now)) {
                    return 'closed';
                } else {
                    return 'open';
                }
            },
            opensAt: function() {
                return moment(this.registration.opens_at, "YYYY-MM-DD HH:mm:ss");
            },
            closesAt: function() {
                return moment(this.registration.closes_at, "YYYY-MM-DD HH:mm:ss");
            }
        },

        methods: {
        }
    }
</script>