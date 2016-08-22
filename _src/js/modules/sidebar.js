module.exports = function () {

  var $sidebar = $('aside.starter-kit-slideout'),
      $foodWants = $('div#sidebar-toggle').find('div.food-wants-heading'),
      $html = $('html');

  $foodWants.click(function (e) {
    $sidebar.addClass('out');
    $html.addClass('freeze');
  });

  $sidebar.on('click', 'svg.close-icon', function (e) {
    $sidebar.removeClass('out');
    $html.removeClass('freeze');
  });

};