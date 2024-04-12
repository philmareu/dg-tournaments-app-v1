    <template>
    <table class="uk-table uk-table-small uk-table-striped uk-table-justify">
        <thead>
            <tr>
                <th>Tournament</th>
                <th>Sponsorship</th>
                <th>Cost</th>
                <th class="uk-text-right" v-if="! paid">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="sponsorship in sponsorships">
                <td>
                    <a :href="getTournament(sponsorship).path" v-text="getTournament(sponsorship).name"></a>
                </td>
                <td>
                    {{ sponsorship.sponsorship.title }}
                </td>
                <td>
                    ${{ sponsorship.sponsorship.cost_in_dollars }}
                </td>
                <td class="uk-text-right" v-if="! paid">
                    <div v-show="status.thinking" class="uk-flex uk-flex-center">
                        <div uk-spinner></div>
                    </div>
                    <a @click.prevent="remove(sponsorship)" v-if="! status.thinking"><span uk-icon="icon: trash;"></span></a>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>

    import Helpers from '../mixins/helpers';

    export default {

        props: [
            'sponsorships',
            'paid'
        ],

        mixins: [
            Helpers
        ],

        data: function() {
            return {
                status: {
                    thinking: false
                }
            }
        },

        methods: {
            remove: function (sponsorship) {
                this.status.thinking = true;
                axios.delete('/order/sponsorships/' + sponsorship.id)
                    .then(response => {
                        this.$emit('order-updated', response.data);
                        this.status.thinking = false;
                    });
            },
            getTournament: function (sponsorship) {
                return sponsorship.sponsorship.tournament;
            },
            getTournamentDate: function (sponsorship) {
                return moment(this.getTournament(sponsorship).start, 'YYYY-MM-DD').format('MMM Do, YYYY');
            }
        },

        computed: {

        }
    }
</script>