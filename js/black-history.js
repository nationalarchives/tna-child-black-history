/**
 * Black History JS
 */

// Eventbrite API
$(document).ready(function () {
    //number of featured events displayed
    "use strict";
    var n = 3;
    var $events = $("#event");
    // Get data from API
    $.get("https://www.eventbriteapi.com/v3/events/search/?q=events+relating+to+black+british+history&sort_by=date&organizer.id=2226699547&token=5VVFLKAPZUXJSKQ3QTBG", function (res) {
        // displays upcoming feature event
        if (res.events.length) {
            var s = "";
            if (n <= res.events.length) {
                // Do nothing
            } else {
                n = res.events.length;
            }
            for (var i = 0; i < n; i++) {
                var event = res.events[i];
                var eventTime = moment(event.start.local).format('dddd D MMMM YYYY, h:mm a');
                if ( i == 0 ) {
                    var hTagOpen = "<h3>";
                    var hTageClose ="</h3>";
                } else {
                    var hTagOpen = "<h4>";
                    var hTageClose ="</h4>";
                }
                if ( event.logo && i == 0 ) {
                    s += "<div class='entry-thumbnail' style='background: url(" + event.logo.url + ") no-repeat center center;background-size: cover;'>"
                    s += "<a href='" + event.url + "' title='External website - link opens in a new window - " + event.name.text + "' target='_blank'></a></div>";
                    s += "<div class='entry-content'><div class='type'><small>What&prime;s on</small></div>";
                }
                s += hTagOpen;
                s += "<a href='" + event.url + "' title='External website - link opens in a new window - " + event.name.text + "' target='_blank'>" + event.name.text + "</a>";
                s += hTageClose;
                s += "<small>" + eventTime + "</small>";
            }
            s += "<ul class='child'><li><a href='http://nationalarchives.eventbrite.co.uk/' title='The National Archives events - opens in a new window' target='_blank'>More events</a></li></ul>";
            s += "</div>";
            $events.html(s);
        } else {
            $events.html("<div class='entry-thumbnail'><a href='http://nationalarchives.eventbrite.co.uk/' title='The National Archives events - opens in a new window' target='_blank'><img src='/wp-content/themes/tna-child-black-history/img/event.jpg' alt='Black British History Event'></a></div><div class='entry-content'><div class='type'><small>What′s on</small></div><h3>The National Archives’ events</h3><p><a href='http://nationalarchives.eventbrite.co.uk/' title='The National Archives events - opens in a new window' target='_blank'>Click here</a> for the complete list of The National Archives’ events.</p></div>");
        }
    });
});
