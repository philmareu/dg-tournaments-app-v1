<template>
    <div>
        <h1 v-text="tournament.name"></h1>
        <div class="meta">
            Directed by Rob Martin
            <!--<div title="Add to Calendar" class="addeventatc">-->
                <!--{{ tournament.dates }}-->
                <!--<span class="start">{{ moment(tournament.start, "YYYY-MM-DD").format('MM/DD/YYYY') }}</span>-->
                <!--<span class="end">{{ moment(tournament.end, "YYYY-MM-DD").format('MM/DD/YYYY') }}</span>-->
                <!--<span class="timezone">America/Chicago</span>-->
                <!--<span class="title">{{ tournament.name }}</span>-->
                <!--<span class="description">A disc golf tournament.</span>-->
                <!--<span class="location">{{ tournament.city }}, {{ tournament.state_province }}</span>-->
                <!--<span class="all_day_event">true</span>-->
                <!--<span class="date_format">MM/DD/YYYY</span>-->
                <!--<span class="client">aiVQoCMTszzoVeDnqmiV24742</span>-->
            <!--</div>-->
        </div>
    </div>
</template>

<script>
    export default {

        data: function() {
            return {
                tournament: tournament,
                moment: moment
            }
        },

        computed: {

        },

        methods: {

        }
    }
</script>