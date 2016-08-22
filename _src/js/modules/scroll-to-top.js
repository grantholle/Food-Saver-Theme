module.exports = function () {

  var $scroller = $('div.scroll-to-top');

  $scroller.click(function () {
    $('html, body').animate({
      scrollTop: 0
    }, 500);
  });

};