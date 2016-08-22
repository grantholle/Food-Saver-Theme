module.exports = function () {

  var $input = $('form#contest-form').find('input');

  $input.val('EMAIL');

  $input.blur(function () {
    if ($input.val().trim() === '')
      $input.val('EMAIL');
  })
  .focus(function () {
    if ($input.val().trim() === 'EMAIL')
      $input.val('');
  });

};