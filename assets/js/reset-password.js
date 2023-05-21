(function () {
    "use strict";

    // Wait for the DOM to be ready
    $(function () {

        var emailInput = $('#email');

        // Attach a submit handler to the login form
        $('#reset').submit(function (event) {
            // Stop the form from submitting normally
            event.preventDefault();

            // Get the Student ID and Email
            var email = emailInput.val();

            // Get the reCAPTCHA response only if the site key is defined
            if ($('#g-recaptcha').data('sitekey') != 'NULL') {
                var recaptchaResponse = grecaptcha.getResponse();
                // Process the reCAPTCHA response here
            } else {
                var recaptchaResponse = null;
            }

            // Perform an AJAX request to the login endpoint
            $.ajax({
                url: '../ajax/login.php?op=forgot-password',
                method: 'POST',
                data: {
                    email: email,
                    recaptcha_response: recaptchaResponse
                },
                success: function (response) {
                    var errorMessage = response.message;
                    var alertHtml = '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                        '<i class="bi bi-exclamation-octagon me-1"></i>' + errorMessage +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>';
                    $('#response').html(alertHtml);
                },
                error: function (xhr) {
                    // If there was an error, display an error message
                    var errorMessage = xhr.responseJSON.message;
                    var alertHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                        '<i class="bi bi-exclamation-octagon me-1"></i>' + errorMessage +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>';
                    $('#response').html(alertHtml);

                    // Enable the submit button again
                    //submitButton.prop('disabled', false);
                    grecaptcha.reset()
                }
            });
        });
    });
})();
