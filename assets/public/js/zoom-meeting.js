jQuery(function ($) {
    var zoom_browser_integration = {

        init: function () {
            var browseinfo = ZoomMtg.checkSystemRequirements();
            var page_html = '<ul><li><strong>Browser Info:</strong> ' + browseinfo.browserInfo + '</li>';
            page_html += '<li><strong>Browser Name:</strong> ' + browseinfo.browserName + '</li>';
            page_html += '<li><strong>Browser Version:</strong> ' + browseinfo.browserVersion + '</li></ul>';
            // page_html += '<li><strong>Available:</strong> ' + browseinfo.features + '</li></ul>';
            $('.dpen-zoom-browser-meeting--info__browser').html(page_html);

            ZoomMtg.preLoadWasm();
            ZoomMtg.prepareJssdk();

            this.eventHandlers();
        },

        eventHandlers: function () {
            $('#dpen-zoom-browser-meeting-join-mtg').on('click', this.loadMeeting.bind(this));
        },

        loadMeeting: function (e) {
            e.preventDefault();

            var meeting_id = rzwm_ajx.meeting_id;
            var API_KEY = false;
            var SIGNATURE = false;
            var REDIRECTION = rzwm_ajx.redirect_page;
            var PASSWD = rzwm_ajx.meeting_pwd;
            $('body').append('<span id="rzwm-cover"></span>');
            if (meeting_id) {
                $.post(rzwm_ajx.ajaxurl, {
                    action: 'get_auth',
                    noncce: rzwm_ajx.rzwm_security,
                    meeting_id: meeting_id,
                }).done(function (response) {
                    if (response.success) {
                        $("#rzwm-cover").remove();
                        $('#dpen-zoom-browser-meeting').hide();

                        API_KEY = response.data.key;
                        SIGNATURE = response.data.sig;

                        if (API_KEY && SIGNATURE) {
                            var display_name = $('#display_name');
                            if (!display_name.val()) {
                                alert("Name is required to enter the meeting !");
                                $("#rzwm-cover").remove();
                                return false;
                            }

                            if (!PASSWD && $('#meeting_password').length > 0) {
                                PASSWD = $('#meeting_password').val();
                            }

                            var lang = $('#meeting_lang');
                            var meetConfig = {
                                apiKey: API_KEY,
                                meetingNumber: parseInt(meeting_id, 10),
                                userName: document.getElementById('display_name').value,
                                passWord: PASSWD,
                                lang: lang.length > 0 ? lang.val() : 'en-US',
                                leaveUrl: REDIRECTION,
                                signaure: SIGNATURE,
                            };

                            ZoomMtg.init({
                                leaveUrl: REDIRECTION,
                                isSupportAV: true,
                                success: function () {
                                    $.i18n.reload(meetConfig.lang);
                                    ZoomMtg.join({
                                        meetingNumber: meetConfig.meetingNumber,
                                        userName: meetConfig.userName,
                                        signature: meetConfig.signaure,
                                        apiKey: meetConfig.apiKey,
                                        // userEmail: 'email@gmail.com',
                                        passWord: meetConfig.passWord,
                                        success: function (res) {
                                            console.log('Join Meeting Success');
                                        },
                                        error: function (res) {
                                            console.log(res);
                                        }
                                    });
                                },
                                error: function (res) {
                                    console.log(res);
                                }
                            });
                        } else {
                            $("#rzwm-cover").remove();
                            alert("NOT AUTHORIZED");
                        }
                    }
                });
            } else {
                $("#rzwm-cover").remove();
                alert("Incorrect Meeting ID");
            }
        }
    };

    zoom_browser_integration.init();
});