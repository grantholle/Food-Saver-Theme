var sassqwatch = require('sassqwatch');

require('../lib/ustream');
require('../lib/jquery.idle');

module.exports = function () {

  var $iframe = $('iframe#live-stream-iframe'),
      $window = $(window),

      videoStream = false,
      isLive = $iframe.length > 0,

      init = function () {

        // Don't play the video if it's below large
        if (sassqwatch.isAbove('mq-large') && isLive) {
          streamInit();
        }

        bindings();
      },

      streamInit = function () {

        // Set the iframe source
        $iframe.attr('src', $iframe.data('src'));

        // Create the ustream api object
        videoStream = UstreamEmbed('live-stream-iframe');

        // Kill the volume
        videoStream.callMethod('volume', 0);

        // Trigger for the loader
        videoStream.addListener('playing', function (playing) {
          $iframe.trigger('playback');

          // We only need to know the first time it has been played
          // Remove it after the first callback
          videoStream.removeListener('playing');
        });

        // Hopefully this never happens, but...
        // If the video is offline, then go ahead trigger a playback event to remove the loader
        // They opted for a manual "live" option in the WP backend
        videoStream.addListener('offline', function () {
          $iframe.trigger('playback');
        });
      },

      checkViewers = function () {
        videoStream.getProperty('viewers', function (viewerNumber) {
          console.log('Current viewers: ' + viewerNumber);
        });
      },

      playVideo = function () {
        if (videoStream) {
          videoStream.callMethod('play');
        }
      },

      stopVideo = function () {
        if (videoStream) {
          videoStream.callMethod('stop');
        }
      },

      bindings = function() {

        // Resize the live stream viewport to be the right aspect ratio
        $window.resize(resize).trigger('resize');

        // Pause or play the video when the viewport goes between large and medium
        sassqwatch.onChange(function (newMq, oldMq) {
          // We went down
          if (newMq === 'mq-medium' && oldMq === 'mq-large' && isLive) {
            videoStream.callMethod('pause');
          } else if (newMq === 'mq-large' && oldMq === 'mq-medium') { // we went up

            // Resume playing or initialize the player
            if (!videoStream && isLive) {
              streamInit();
            }

            playVideo();
          }
        });

        // Check for idle browser, pause/stop if idle for too long
        $(document).idle({
          onIdle: stopVideo,
          onActive: playVideo,
          onHide: stopVideo,
          onShow: playVideo,
          idle: 120000
        });
      },

      resize = function () {
        if (sassqwatch.isAbove('mq-large')) {
          var height = $window.width() / (16 / 9);

          $iframe.height(height);
        }
      };

  init();

};