var sassqwatch = require('sassqwatch');

require('../lib/jquery.waypoints');

module.exports = function () {

  var $waypoint = $('section.food-content'),
      $scroller = $('div.scroll-to-top'),
      $header = $('header');

  $waypoint.waypoint({
    handler: function (direction) {
      if (sassqwatch.isAbove('mq-small')) {
        $header.toggleClass('show');
        $scroller.toggleClass('show');
      }
    }
  });

}