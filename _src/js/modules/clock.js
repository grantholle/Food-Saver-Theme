var moment = require('../lib/moment-timezones');

require('../lib/moment-duration-format');

module.exports = function() {

  var $clock = $('div.clock'),
      then,
      now,

      getLapse = function () {
        var duration = moment.duration(now.diff(then), 'milliseconds').format('D|H|mm', { trim: false }).split('|');

        $clock.find('span.day-diff').html(duration[0]);
        $clock.find('span.hour-diff').html(duration[1]);
        $clock.find('span.min-diff').html(duration[2]);

        // Increment the time
        now.add(1, 'minutes');

        // Keep calling getLapse every minute to keep the clock updated
        setTimeout(getLapse, 60000);
      };

  // Set the timezone to be all CST-based
  moment.tz.setDefault('America/Chicago');
  then = moment($clock.data('date-then'), 'MM/DD/YYYY HH:mm');
  now = moment($clock.data('date-now'), 'MM/DD/YYYY HH:mm');

  getLapse();

};