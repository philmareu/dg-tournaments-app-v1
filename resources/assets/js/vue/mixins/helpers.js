module.exports = {
    methods: {
        url: function(path) {
            if(typeof path === 'undefined') return SITE_URL;

            return SITE_URL + '/' + path;
        },

        image: function (filename, filter) {
            return SITE_URL + '/images/' + filter + '/' + filename;
        }
    }
};