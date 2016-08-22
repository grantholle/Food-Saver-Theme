var sassqwatch = require('sassqwatch');

require('../lib/imagesLoaded');

module.exports = function () {
  var $loader = $('div#site-image-loader'),
      $liveContainer = $('section.live-video-container'),
      $contenders = $('section.contenders'),
      $iframe = $('iframe#live-stream-iframe'),
      $thumbnails = $('div.thumbnail-wrapper').find('img'),

      thumbnailCount = 0,
      delay = parseInt($loader.data('delay'), 10), // In milliseconds
      iframeLoaded = $iframe.length > 0 ? false : true,
      gifLoaded = false,
      thumbnailsLoaded = false,

      init = function () {
        bindings();
      },

      removeLoader = function () {
        if (typeof Waypoint !== 'undefined') {
          Waypoint.refreshAll();
        }

        $('html').trigger('finished-loading').removeClass('loading');
      },

      iframeFinished = function () {
        iframeLoaded = true;

        $loader.trigger('completed');
      },

      imageLoadedCheck = function () {
        thumbnailsLoaded = true;

        $loader.trigger('completed');
      },

      bindings = function () {

        $loader.on('completed', function () {
          if (iframeLoaded && gifLoaded && thumbnailsLoaded) {
            setTimeout(removeLoader, delay);

            // Remove this listener
            $loader.off('completed');
          }

        });

        // The faux loader
        if (sassqwatch.isAbove('mq-large')) {
          var imgUrl = $loader.data('src');

          // Preload the gif before?
          $('<img>').attr('src', imgUrl)
            .load(function () {
              gifLoaded = true;

              $loader.css('background-image', 'url(' + imgUrl + ')');
              $loader.trigger('completed');
            });

          // If the video is live, otherwise watch for the placeholder image
          if ($iframe.length > 0) {
            $iframe.on('playback', iframeFinished);
          } else {
            $liveContainer.find('img').load(iframeFinished);
          }

          // Flag check for if all the thumbnails are loaded
          $thumbnails.imagesLoaded(imageLoadedCheck);

        } else {
          removeLoader();
        }

        // Set the background of the iframe container to the loader for
        // A better medium-going-to-large user experience
        sassqwatch.only('mq-medium', function () {
          $iframe.parent().css('background-image', 'url(' + $loader.data('src') + ')');
        }, true);
      };

  init();

};