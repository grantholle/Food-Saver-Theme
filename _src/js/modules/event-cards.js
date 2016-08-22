var sassqwatch = require('sassqwatch'),
    Hammer = require('../lib/hammer');

module.exports = function () {
  var $cardContainer = $('div.events'),
      totalCards = $cardContainer.find('article').length,
      currentCard = 0,

      scroller = function () {
        var hammertime = new Hammer($cardContainer[0]);

        hammertime.on('panend', function(ev) {
          if (sassqwatch.isBelow('mq-large')) {
            // Add offset
            if (ev.direction === 2 && currentCard < totalCards - 1) {
              $cardContainer.find('article').removeClass('offset-' + currentCard++).addClass('offset-' + currentCard);
            }
            // Subtract offset
            else if (ev.direction === 4 && currentCard > 0) {
              $cardContainer.find('article').removeClass('offset-' + currentCard--).addClass('offset-' + currentCard);
            }
          }
        });
      };

    scroller();
};