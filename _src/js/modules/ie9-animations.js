require('../lib/jquery.animate-rotate-scale');

module.exports = function () {

  var $icon = $('div.playback-container').find('svg.loading-icon'),

      init = function () {
        setInterval(function () {
          $icon.animate({rotate: '+=10deg'}, 0);
        }, 200);
      };

  // init();

}