module.exports = {
    computed: {
        isFollowing: function () {

            if(this.user !== null) {
                this.thinking = false;
                let ids = this.user.following_tournaments.map(function(t) {
                    return parseInt(t.resource_id);
                });

                return ids.indexOf(this.tournament.id) !== -1;
            }

            return false;
        },
        canEdit: function() {

            if(! this.authenticated()) return false;

            let userIds = this.tournament.managers.map(function(user) {
                return parseInt(user.id);
            });

            return userIds.indexOf(this.user.id) !== -1;
        }
    },
    methods: {
        authenticated: function() {
            return this.user !== null;
        }
    }
};