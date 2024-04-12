<template>
    <div class="card">
        <h2 class="card-header">Weather Forecast by <a href="https://darksky.net/poweredby">Dark Sky</a></h2>

        <div class="uk-text-center" uk-grid>
            <div v-for="w in weather">
                <span class="uk-text-muted">{{ moment(w.time, 'X').format('Do') }}</span>
                <br>
                <span v-text="adjTemp(w.temperatureMax)"></span>
            </div>
        </div>

        <!--<div>No Weather</div>-->
    </div>
</template>

<script>
    export default {

        data: function() {
            return {
                weather: null,
                moment: moment
            }
        },

        methods: {
            adjTemp: function (temp) {
                if(tournament.country === 'United States') return Math.round(temp) + ' F';

                return Math.round(this.convertToCelcius(temp)) + ' C';
            },
            convertToCelcius: function (tempInF) {
                return (parseInt(tempInF) - 32) * (5/9);
            }
        },

        computed: {
        },

        beforeCreate: function() {
            axios.get('/api/weather/' + tournament.id)
                .then(response => {
                    this.weather = response.data;
                });
        },

        mounted: function() {

        }
    }
</script>