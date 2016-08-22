var sassqwatch = require('sassqwatch');

require('../lib/jquery.waypoints');

module.exports = function () {

  var $waypoint = $('section.food-content'),
      $body = $('body'),
      $clockBar = $('div.clock-bar-wrapper');

  $waypoint.waypoint({
    offset: '100%',
    handler: function (direction) {
      if (sassqwatch.isAbove('mq-small')) {
        $body.toggleClass('unstick');
      }
    }
  });

};