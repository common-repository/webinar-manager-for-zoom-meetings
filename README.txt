=== Webinar Manager for Zoom Meetings ===
Contributors: rajthemes
Donate link: https://rajthemes.com/
Tags: zoom video conference, video conference, zoom, zoom video conferencing, web conferencing, online meetings
Requires at least: 4.9
Tested up to: 5.5
Stable tag: 1.0.1
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Webinar Manager for Zoom Meetings provide you the power to manage Zoom Meetings, Webinars, Recordings, Reports and create users directly from your WordPress dashboard.

== Description ==

Simple plugin which gives you the extensive functionality to manage Zoom Meetings, Webinars, Recordings, Users, Reports from your WordPress Dashboard.
Remote type of work requires powerful tools to make sure all the business processes correspond to the level and are done without any loss.

Now, with capability to add your own post as a meeting. Create posts as meetings directly from your WordPress dashboard to show in the frontend as a meeting page. Allow users to directly join via that page with click of a button.

ZOOM WEBINARS
Zoom Webinars are an ideal solution for virtual lectures. It is a perfect way to conduct big online events and distribute them to large audiences.

Webinars make a valuable addition to the Webinar Manager for Zoom Meetings plugin and reflect the best practice of a one-to-many communication approach.

Webinars will be perfect for you if you:

* offer virtual lectures;
* distribute to a large audience;
* use the listen-only mode;
* want to diversify your content;
* want to manage webinars directly from your dashboard.

With flexible Zoom plans, the number of webinar participants can be up to 10,000.

Click here for its [Documentation](https://rajthemes.com/documentation/webinar-manager-for-zoom-meetings-documentation/)

**FEATURES:**
* Compatible with WordPress
* Provides integration of Zoom on WordPress
* Compatible with Zoom API
* Manage your Live Zoom meetings and Zoom Webinars.
* Has admins area to manage the meetings
* Display your Zoom meeting & link them on WordPress posts & page.
* Override single and archive page templates via your theme.
* JOIN DIRECTLY VIA WEB BROWSER FROM FRONTEND !
* Start Links for post authors.
* Enables Zoom video conferencing features
* Provides shortcode to conduct the Meeting & Display Webinars on any WordPress page
* CountDown timer to Meeting start shows in individual meeting page.
* Start time and join links are shown according to local time.
* Show user recordings based on Zoom Account.
* Daily and Account Reports
* Shortcode Template Customize
* Import your Zoom Meetings into your WordPress Dashboard in one click.
* Display Webinars via Shortcode
* Allows to add and manage users

**OVERRIDDING TEMPLATES:**

If you use Zoom Meetings > Add new section i.e Post Type meetings then you might need to override the template. Currently this plugin supports default templates.

**COMPATIBILITY:**

* Enables direct integration of Zoom into WordPress.
* Compatible with LearnPress, LearnDash 3.
* Enables most of the settings from zoom via admin panel.
* Provides Shortcode to conduct the meeting via any WordPress page/post or custom post type pages
* Separate Admin area to manage all meetings.
* Can add meeting links via shortcode to your WooCommerce product pages as well.

**Zoom Web SDK Notice from Zoom Itself**

The Web SDK enables the development of video applications powered by Zoomâ€™s core framework inside an HTML5 web client through a highly optimized WebAssembly module.

As an extension of the Zoom browser client, this SDK is intended for implementations where the end user has a low-bandwidth environment, is behind a network firewall, or has restrictions on their machine which would prevent them from installing the Zoom Desktop or Mobile Clients.

**SHORTCODE:**

**You can get your shorcodes from individual meetings after creating certain meeting.**

* [rzwm_zoom_api_link meeting_id="123456789" link_only="no"] - Just enter your meeting ID and you are good to show your meeting in any page. Adding link_only="yes" would show join link only.

* [rzwm_zoom_api_webinar webinar_id="YOUR_WEBINAR_ID" link_only="no"] - Show webinar details based on webinar ID.

* [rzwm_zoom_list_meetings per_page="5" category="test,test2,test3" order="DESC"] - Show list of meetings in frontend via category, Edit shortcode template for table view.

* [rzwm_zoom_list_host_meetings host="your_host_id"] - Show list of meetings in frontend for specific HOST ID.

* [rzwm_zoom_recordings host_id="YOUR_HOST_ID" downloadable="yes"] - Show list of recordings based on HOST ID. By default downloadable is set to false.

* [rzwm_zoom_recordings_by_meeting meeting_id="MEETING_ID" downloadable="yes"] - which shows recordings based on meeting ID.

**CONTRIBUTING**

Please consider giving a 5 star thumbs up if you found this useful.

**Our Other Plugins**
-[BeautyPlus](https://wordpress.org/plugins/beautyplus/)

== Installation ==
Search for the plugin -> add new dialog and click install, or download and extract the plugin, and copy the the Zoom plugin folder into your wp-content/plugins directory and activate.

== Frequently Asked Questions ==

= Add users not working for me =

The plugin settings allow you to add and manage users. But, you should remember that you can add users in accordance with the Zoom Plans, so they will be active for the chosen plan. More information about Zoom pricing plans you can find here: https://zoom.us/pricing

= Join via Browser not working, Camera and Audio not detected =

This issue is because of HTTPS protocol. You need to use HTTPS to be able to allow browser to send audio and video.

= Countdown not showing/ guess is undefined error in my console log =

If countdown is not working for you then the first thing you'll nweed to verify is whether your meeting got created successfully or not. You can do so by going to wp-admin > Zoom Meetings > Select your created meeting and on top right check if there are "Start Meeting", "join Meeting links". If there are those links then, you are good on meeting.

However, even though meeting is created and you are not seeing countdown timer then, you might want to check your browser console and see if there is any "guess is undefined" error. If so, there might be a plugin conflict using the same moment.js library. **Report to me in this case**

= How to show Zoom Meetings on Front =

* By using shortcode like [rzwm_zoom_api_link meeting_id="123456789"] you can show the link of your meeting in front.

= How to override plugin template to your theme =

1. Goto **wp-content/plugins/webinar-manager-for-zoom-meetings/templates**
2. Goto your active theme folder to create new folder. Create a folder such as **yourtheme/webinar-manager-for-zoom-meetings/{template-file.php}**
3. Replace **template-file.php** with the file you need to override.
4. Overriding shortcode template is also the same process inside folder **templates/shortcode**

= Do i need a Zoom Account ? =

Yes, you should be registered in Zoom. Also, on the plan you are using there depends the number of hosts and users you can add.

== Screenshots ==

== Changelog ==