'use strict';

var moment = require('../lib/moment-timezones');

module.exports = function () {

  var $html = $('html'),
      $overlay = $('section.video-overlay'),
      $playbackImg = $overlay.find('img#playback-img'),
      $playbackContainer = $overlay.find('div.playback-container'),
      $popover = $('div.popover'),
      $nowFilming = $('div.now-filming'),
      $document = $(document),

      $canvas = $('canvas#canvas'),
      ctx = $canvas[0].getContext('2d'),
      dWidth = $canvas.width(),
      dHeight = dWidth / (16 / 9),

      overlayClass = 'overlay-open',
      timeLapses = {},
      currentId,
      currentImg = false,
      overlayClosed = true,

      init = function () {

        moment.tz.setDefault('America/Chicago');

        setCanvasSize();
        bindings();
        setLiveSinceOnPopover();
      },

      setCanvasSize = function () {
        dWidth = $canvas.width();
        dHeight = dWidth / (16 / 9);
        $canvas.attr({ height: dHeight, width: dWidth });

        if (currentImg) {
          drawImage();
        }
      },

      buildTimeLapses = function () {

        // Find all thumbnails and get their info
        $html.find('div.thumbnail-wrapper').each(function (i, ele) {
          var $thumb = $(ele),
              id = parseInt($thumb.data('lapse-id'), 10),
              duration, seconds;

          // Create the lapse object
          timeLapses[id] = {
            images: [],
            startTime: moment($thumb.data('start-time'), 'MM/DD/YYYY HH:mm'),
            endTime: moment($thumb.data('end-time'), 'MM/DD/YYYY HH:mm'),
            interval: parseInt($thumb.data('playback-interval'), 10),
            camera: $thumb.data('camera'),
            fps: parseFloat($thumb.data('fps')),
            isLive: typeof $thumb.data('is-live') !== 'undefined',
            title: $thumb.data('title'),
            imagesLoading: false,
            imagesLoaded: false
          };

          // Build the image array for this time lapse
          // After it's finished, set duration data with a callback
          getPrepImages(id, function () {
            var imageCount = timeLapses[id].images.length,
                lapse = timeLapses[id];

            // Append the rest of the lapse images
            buildLapseImages(id);

            // Get the duration from dividing the number of images by the fps
            duration = moment.duration(imageCount / timeLapses[id].fps, 'seconds');

            // Convert the decimal portion of the minutes to seconds of a minute, padding if necessary
            seconds = ('0' + Math.round((duration.asMinutes() % 1) * 60)).slice(-2);

            // Set the 'video' duration
            lapse.duration = duration.minutes() + ':' + seconds;

            // Add the duration to the dom
            $thumb.find('div.duration').html(lapse.duration);

            // Set the preload batch size based on the amount of images and fps
            // Load 5 seconds at a time
            lapse.preloadBatchSize = (lapse.fps * 5 <= imageCount) ? imageCount : lapse.fps * 5;
            lapse.preloadIteration = 0;

          });

        });

      },

      getPrepImages = function (key, callback) {

        // Only get prep images for non-live lapses?
        if (!timeLapses[key].isLive) {
          // Retrieve the preparation videos if there are any
          $.ajax({
            url: window.config.ajaxUrl,
            async: true,
            cache: false,
            type: 'POST',
            data: {
              action: 'get_prep_images',
              id: key
            },
            error: function(jqXHR, textStatus, errorThrown) {
              console.error('Error getting prep vidoes - ' + textStatus);
            },
            success: function (data, textStatus, jqXHR) {

              if (data) {
                // Add each prep image
                for (var i = 0; i < data.length; i++) {
                  var obj = {
                      url: data[i].image.sizes['time-lapse'],
                      day: 0
                    };

                  // Add the next image onto the array
                  timeLapses[key].images.push(obj);
                }
              }

              // The callback
              if (callback)
                callback();
            }
          });
        } else {
          // The callback
          if (callback)
            callback();
        }

      },

      buildLapseImages = function (id) {
        var lapse = timeLapses[id],
            time = lapse.startTime.clone();

        // Start a loop to gather all the images based on this lapse's settings
        while (time.isBefore(lapse.endTime)) {
          // Create the image url
          var obj = {
              url: '//s3.amazonaws.com/food-saver-fridge/camera-' + lapse.camera + '/' + time.format('YYYY-MM-DD-HH-mm') + '.jpg',
              day: time.diff(lapse.startTime, 'days') + 1
            };

          // Add the next image onto the array
          timeLapses[id].images.push(obj);

          // Increment based on the interval
          time.add(lapse.interval, 'minutes');
        }
      },

      playbackLoader = function ($thumb) {
        var id = parseInt($thumb.data('lapse-id'), 10),
            lapse = timeLapses[id];

        // Set the current id of this playback
        currentId = id;

        // Start loading the images if they aren't already
        if (!lapse.imagesLoading)
          loadImages(id);

        // Set the thumbnail
        drawImage($thumb.find('img')[0]);

        // Set the since date or hide it
        if (lapse.isLive)
          $overlay.find('span.live-since').show().find('span.live-since-month-day').html(lapse.startTime.format('MMMM D'));
        else
          $overlay.find('span.live-since').hide();

        // Set the title
        $overlay.find('h2.save').html(lapse.title);
      },

      loadImages = function (id) {
        var id = typeof id === 'undefined' ? currentId : id,
            lapse = timeLapses[id],
            start = lapse.preloadBatchSize * lapse.preloadIteration,
            end = (start + lapse.preloadBatchSize) >= lapse.images.length ? lapse.images.length : start + lapse.preloadBatchSize;

        // Set the flag that we've already started to load images
        lapse.imagesLoading = true;

        for (var i = start; i < end; i++) {
          var image = new Image(),
              item = lapse.images[i];

          // Load dat image
          image.src = item.url;

          // Save it back to the object for safe keeping
          item.image = image;
        }

        // We've reached the last batch, therefore we're finished loading this lapse's images
        if (lapse.images.length === end)
          lapse.imagesLoaded = true;

      },

      drawImage = function (img) {
        if (img)
          currentImg = img;

        try {
          ctx.drawImage(currentImg, 0, 0, dWidth, dHeight);
        } catch (e) {
          console.error('There was a problem rendering ' + currentImg.src);
        }
      },

      batchFinishedLoading = function () {
        var lapse = timeLapses[currentId],
            start = lapse.preloadBatchSize * lapse.preloadIteration,
            end = (start + lapse.preloadBatchSize) >= lapse.images.length ? lapse.images.length : start + lapse.preloadBatchSize;

        for (var i = start; i < end; i++) {
          if (typeof lapse.images[i].image === 'undefined' || !lapse.images[i].image.complete)
            return false;
        };

        lapse.preloadIteration++;
        return true;
      },

      playback = function () {
        var lapse = timeLapses[currentId],
            isBuffering = false,
            timeout, preloadIntervalId,
            currentDay = 0,
            fps = 1000 / lapse.fps,

            play = function (i) {

              // Stop playback after the last image is shown
              if (i === lapse.images.length) {
                $playbackContainer.find('svg.rewatch-icon').show();
                i++;
              }
              // So long as the next image is defined and loaded, then set the new image source
              else if (typeof lapse.images[i].image !== 'undefined' && lapse.images[i].image.complete) {
                drawImage(lapse.images[i].image);

                // Swap out the day
                if (lapse.images[i].day !== currentDay) {
                  currentDay = lapse.images[i].day;
                  $playbackContainer.find('span.day').html(lapse.images[i].day);
                }

                i++;

                // Remove the loading icon
                $playbackContainer.removeClass('loading').find('svg.loading-icon').hide();

                // Reset isBuffering since this was a successful load
                isBuffering = false;
              } else {
                isBuffering = true;

                // Give the user a reason why the video stopped -- loading the image
                if (overlayClosed)
                  $playbackContainer.removeClass('loading').find('svg.loading-icon').hide();
                else
                  $playbackContainer.addClass('loading').find('svg.loading-icon').show();
              }

              // If the overlay isn't closed keep playing
              if (!overlayClosed) {
                // If we're playing and the overlay hasn't closed, continue attempting to play
                // If the images for this batch are still loading, wait 1 sec before attempting to play again
                if (i <= lapse.images.length) {
                  timeout = setTimeout(function () {
                    play(i);
                  }, isBuffering ? 1000 : fps);
                }
              } else {
                clearTimeout(timeout);
                clearInterval(preloadIntervalId);
              }
            },

            preload = function () {

              // Check every second to see if we're finished loading the current batch of images
              // Then start loading the next batch
              if (!lapse.imagesLoaded) {
                preloadIntervalId = setInterval(function () {
                  if (batchFinishedLoading()) {
                    if (lapse.imagesLoaded) {
                      clearInterval(preloadIntervalId);
                    } else {
                      loadImages();
                    }
                  }
                }, 500);
              }

            };

          // Hide the icons
          $playbackContainer.find('svg').hide();

          // Start the playback
          play(0);

          // Preload
          preload();
      },

      closeOverlay = function () {
        $html.removeClass(overlayClass);
        overlayClosed = true;

        // Reset stuff after the overlay has faded away - 350ms
        setTimeout(function () {
          $playbackContainer.removeClass('loading').find('svg.loading-icon').hide();
          $playbackContainer.find('svg.play-icon').show();
          $playbackContainer.find('svg.rewatch-icon').hide();
          $playbackContainer.find('span.day').html(0);
        }, 350);
      },

      // There are three hover areas that correspond to three live playbacks
      // Instead of having to move the php around, just set it with JS
      setLiveSinceOnPopover = function () {
        var hoverAreas = ['div.left-hover', 'div.center-hover', 'div.right-hover'];

        $nowFilming.find('div.thumbnail-wrapper').each(function (i, ele) {
          var startTime = moment($(ele).data('start-time'), 'MM/DD/YYYY HH:mm');

          if (hoverAreas[i]) {
            $(hoverAreas[i]).find('span.live-since-label').html('Live since ' + startTime.format('MMM D'));
          }
        });
      },

      bindings = function () {

        // When a thumbnail is clicked, launch dat overlay
        $html.on('click', 'div.thumbnail-wrapper', function (e) {
          $html.addClass(overlayClass);
          playbackLoader($(e.currentTarget));

          overlayClosed = false;
        });

        // Close icon clicky
        $overlay.on('click', 'div.close-container', function () {
          closeOverlay();
        })
        .on('click', 'svg.play-icon, svg.rewatch-icon', playback);

        // Listen for the esc key
        $document.keyup(function (e) {
          if (e.keyCode == 27) {
            closeOverlay();
          }
        });

        // Resize the canvas in the overlay
        $(window).resize(setCanvasSize);

        // Once the loader is finished, build the image arrays for the time lapse
        // Save this processing for after the more important things are loaded????
        // $html.on('finished-loading', buildTimeLapses);
        buildTimeLapses();

        // When a popover gets clicked, launch the overlay
        $popover.click(function (e) {
          var camera = parseInt($(e.currentTarget).data('camera'), 10);

          $nowFilming.find('div.thumbnail-wrapper').slice(camera, camera + 1).trigger('click');
        });

      };

  init();

};