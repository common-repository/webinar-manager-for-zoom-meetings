(function ($) {
    var vczAPIListUserMeetings = {
        init: function ($) {
            this.cacheDOM();
            if (this.$wrapper === undefined || this.$wrapper.length < 1) {
                return false;
            }
            this.defaultActions();
        },
        cacheDOM: function () {
            this.$wrapper = $('.rzwm-user-meeting-list');
            if (this.$wrapper === undefined || this.$wrapper.length < 1) {
                return false;
            }
        },
        defaultActions: function () {
            this.$wrapper.DataTable({
                responsive: true
            });
        }
    };

    $(function () {
        vczAPIListUserMeetings.init();
    });

})(jQuery);