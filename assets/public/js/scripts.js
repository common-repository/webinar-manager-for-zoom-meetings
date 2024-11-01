jQuery(function ($) {

    var rzwmzoom_manager_public = {

        init: function () {
            this.cacheVariables();
            this.countDownTimerMoment();
            this.evntLoaders();
        },

        cacheVariables: function () {
            this.$timer = $('#rjtm-rzwm-timer');
            this.changeMeetingState = $('.rzwm-meeting-state-change');
        },

        evntLoaders: function () {
            $(window).on('load', this.setTimezone.bind(this));
            //End and Resume Meetings
            $(this.changeMeetingState).on('click', this.meetingStateChange.bind(this));
        },

        countDownTimerMoment: function () {
            var clock = this.$timer;
            if (clock.length > 0) {
                var valueDate = clock.data('date');
                var mtgTimezone = clock.data('tz');
                var mtgState = clock.data('state');

                // var dateFormat = moment(valueDate).format('MMM D, YYYY HH:mm:ss');

                var user_timezone = moment.tz.guess();
                if (user_timezone === 'Asia/Katmandu') {
                    user_timezone = 'Asia/Kathmandu';
                }

                console.log(user_timezone);

                //Converting Timezones to locals
                var source_timezone = moment.tz(valueDate, mtgTimezone).format();
                var converted_timezone = moment.tz(source_timezone, user_timezone).format('MMM D, YYYY HH:mm:ss');
                var convertedTimezonewithoutFormat = moment.tz(source_timezone, user_timezone).format();

                //Check Time Difference for Validations
                var currentTime = moment().unix();
                var eventTime = moment(convertedTimezonewithoutFormat).unix();
                var diffTime = eventTime - currentTime;

                var lang = document.documentElement.lang;
                var dateFormat = rzwm_strings.date_format !== "" ? rzwm_strings.date_format : 'LLLL';
                $('.sidebar-start-time').html(moment.parseZone(convertedTimezonewithoutFormat).locale(lang).format(dateFormat));

                var second = 1000,
                    minute = second * 60,
                    hour = minute * 60,
                    day = hour * 24;

                if (mtgState === "ended") {
                    $(clock).html("<div class='rjtm-rzwm-meeting-ended'><h3>" + rzwm_strings.meeting_ended + "</h3></div>");
                } else {
                    // if time to countdown
                    if (diffTime > 0) {
                        var countDown = new Date(converted_timezone).getTime();
                        var x = setInterval(function () {
                            var now = new Date().getTime();
                            var distance = countDown - now;

                            document.getElementById('rjtm-rzwm-timer-days').innerText = Math.floor(distance / (day));
                            document.getElementById('rjtm-rzwm-timer-hours').innerText = Math.floor((distance % (day)) / (hour));
                            document.getElementById('rjtm-rzwm-timer-minutes').innerText = Math.floor((distance % (hour)) / (minute));
                            document.getElementById('rjtm-rzwm-timer-seconds').innerText = Math.floor((distance % (minute)) / second);

                            if (distance < 0) {
                                clearInterval(x);
                                $(clock).html("<div class='rjtm-rzwm-meeting-ended'><h3>" + rzwm_strings.meeting_starting + "</h3></div>");
                            }
                        }, second);
                    } else {
                        $(clock).html("<div class='rjtm-rzwm-meeting-ended'><h3>" + rzwm_strings.meeting_started + "</h3></div>");
                    }
                }
            }
        },

        /**
         * Set timezone and get links accordingly
         */
        setTimezone: function () {
            var timezone = moment.tz.guess();
            if (timezone === 'Asia/Katmandu') {
                timezone = 'Asia/Kathmandu';
            }

            try {
                if (typeof mtg_data !== undefined && mtg_data.page === "single-meeting") {
                    $('.rjtm-rzwm-sidebar-content').after('<div class="rjtm-rzwm-sidebar-box remove-sidebar-loder-text"><p>Loading..Please wait..</p></div>');
                    var pageData = {
                        action: 'set_timezone',
                        user_timezone: timezone,
                        post_id: mtg_data.post_id,
                        mtg_timezone: mtg_data.timezone,
                        start_date: mtg_data.start_date,
                        type: 'page'
                    };

                    $.post(mtg_data.ajaxurl, pageData).done(function (response) {
                        if (response.success) {
                            $('.rjtm-rzwm-sidebar-content').after(response.data);
                        } else {
                            $('.rjtm-rzwm-sidebar-content').after('<div class="rjtm-rzwm-sidebar-box">' + response.data + '</div>');
                        }

                        $('.remove-sidebar-loder-text').remove();
                    });
                }

                /**
                 * For shortcode
                 * @deprecated 3.3.1
                 */
                if (typeof mtg_data !== undefined && mtg_data.type === "shortcode") {
                    var shortcodeData = {
                        action: 'set_timezone',
                        user_timezone: timezone,
                        mtg_timezone: mtg_data.timezone,
                        join_uri: mtg_data.join_uri,
                        browser_url: mtg_data.browser_url,
                        start_date: mtg_data.start_date,
                        type: 'shortcode'
                    };

                    $('.rzwm-table-shortcode-duration').after('<tr class="remove-shortcode-loder-text"><td colspan="2">Loading.. Please wait..</td></tr>');
                    $.post(mtg_data.ajaxurl, shortcodeData).done(function (response) {
                        if (response.success) {
                            $('.rzwm-table-shortcode-duration').after(response.data);
                        } else {
                            $('.rzwm-table-shortcode-duration').after('<tr><td colspan="2">' + response.data + '</td></tr>');
                        }

                        $('.remove-shortcode-loder-text').remove();
                    });
                }
            } catch (e) {
                //leave blank
            }
        },

        /**
         * Change Meeting State
         * @param e
         */
        meetingStateChange: function (e) {
            e.preventDefault();
            var state = $(e.currentTarget).data('state');
            var post_id = $(e.currentTarget).data('postid');
            var postData = {
                id: $(e.currentTarget).data('id'),
                state: state,
                type: $(e.currentTarget).data('type'),
                post_id: post_id ? post_id : false,
                action: 'state_change',
                accss: rzwm_state.rzwm_security
            };

            if (state === "resume") {
                this.changeState(postData);
            } else if (state === "end") {
                var c = confirm(rzwm_state.lang.confirm_end);
                if (c) {
                    this.changeState(postData);
                } else {
                    return;
                }
            }
        },

        /**
         * Change the state triggere now
         * @param postData
         */
        changeState: function (postData) {
            $.post(rzwm_state.ajaxurl, postData).done(function (response) {
                location.reload();
            });
        }
    };

    rzwmzoom_manager_public.init();
});