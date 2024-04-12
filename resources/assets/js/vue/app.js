const app = new Vue({
    el: '#app',
    data: {
        siteUrl: SITE_URL,
        user: null,
        hasNewNotifications: false,
        order: null,
        showLogin: false
    },
    methods: {
        updateUser: function () {
            axios.get('/user/current')
            .then(response => {
                this.user = response.data;
                if(this.user === "") this.user = null;

                // if(this.user !== null) {
                //     Echo.private('DGTournaments.Models.User.' . this.user.id)
                //     .notification((notification) => {
                //         app.hasNewNotifications = true;
                //     });
                // }
            });
        },
        updateOrder: function(order) {
            this.order = order;
        },
        markNotificationsAsViewed: function () {
            this.updateUser();
            this.hasNewNotifications = false;
        }
    },
    beforeCreate: function () {
        axios.get('/user/current')
        .then(response => {
            this.user = response.data;
            if(this.user === "") this.user = null;

            // if(this.user !== null) {
            //     Echo.private('DGTournaments.Models.User.' + this.user.id)
            //     .notification((notification) => {
            //         app.hasNewNotifications = true;
            //     });
            // }
        });

        axios.get('/order/current')
            .then(response => {
                this.order = response.data;
                if (this.order === "") this.order = null;
            });
    },
    mounted: function () {

    }
});
