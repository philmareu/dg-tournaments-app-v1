<template>
    <div>
        <ul class="uk-child-width-expand uk-margin-remove-bottom uk-tab">
            <li :class="{ 'uk-active' : panel === 'info' }">
                <a :href="tournament.path"><span uk-icon="icon: info" class="uk-margin-small-right"></span>Info</a>
            </li>
            <li :class="{ 'uk-active' : panel === 'sponsors' }">
                <a :href="sponsorUrl">Sponsors</a>
            </li>
        </ul>
    </div>
</template>

<script>

    export default {

        props: [
            'panel',
            'tournament'
        ],

        data: function() {
            return {
            }
        },

        computed: {
            sponsorUrl: function() {
                return this.tournament.path + '/sponsors';
            }
        }
    }

</script>