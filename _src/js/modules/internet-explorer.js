var bowser = require('../lib/bowser');

module.exports = function () {

  if (bowser.browser.msie) {
    $('html').addClass('internet-explorer');
  }

}