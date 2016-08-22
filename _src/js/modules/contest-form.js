module.exports = function () {

  var $formContainer = $('div.contest-form'),
      firstKey = true,

      invalid = function (message) {
        $formContainer.find('input[type=email]').addClass('error');
        firstKey = true;
        $formContainer.removeClass('is-loading');

        if (message)
          alert(message);
      };

  $formContainer.submit(function (e) {
    e.preventDefault();
    e.stopPropagation();

    $formContainer.addClass('is-loading');

    var email = $formContainer.find('input[type=email]').val().replace(' ', '');

    if (email.length > 0) {
      $.ajax({
        url: window.config.ajaxUrl,
        async: true,
        type: 'POST',
        data: {
          action: 'contest_submission',
          email: email
        },
        complete: function () {
          $formContainer.removeClass('is-loading');
        },
        error: function(jqXHR, textStatus, errorThrown) {
          alert(textStatus);
        },
        success: function (data, textStatus, jqXHR) {
          if (data.is_valid) {
            $formContainer.find('form').remove();
            $formContainer.append('<p class="submission-success">' + window.config.thankYou + '</p>');
          } else {
            invalid(data.validation_messages[1]);
          }
        }
      });
    } else {
      invalid();
    }
  }).on('keypress', 'input[type=email]', function (e) {
    if (firstKey) {
      $(e.currentTarget).removeClass('error');
      firstKey = false;
    }
  });

};