(function () {
    "use strict";

    // Wait for the DOM to be ready
    $(function () {

        // Get the email and submit button elements
        var codeInput = $('#code');
        var passwordInput = $('#password');
        var repasswordInput = $('#repassword');

        // Attach a submit handler to the login form
        $('#changepass').submit(function (event) {
            // Stop the form from submitting normally
            event.preventDefault();

            // Get the email and password values
            var code = codeInput.val();
            var password = passwordInput.val();
            var repassword = repasswordInput.val();

            // Get the reCAPTCHA response only if the site key is defined
            if ($('#g-recaptcha').data('sitekey') != 'NULL') {
                var recaptchaResponse = grecaptcha.getResponse();
                // Process the reCAPTCHA response here
            } else {
                var recaptchaResponse = null;
            }

            if (password !== repassword) {
                // If the passwords don't match, display an error message
                var alertHtml =
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                    '<i class="bi bi-exclamation-octagon me-1"></i>' +
                    "Passwords do not match." +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                    "</div>";
                $("#response").html(alertHtml);

                // Focus on the password field
                passwordInput.focus();
            } else {

                // Perform an AJAX request to the login endpoint
                $.ajax({
                    url: '../ajax/login.php?op=reset-password',
                    method: 'POST',
                    data: {
                        code: code,
                        password: password,
                        repassword: repassword,
                        recaptcha_response: recaptchaResponse
                    },
                    success: function (xhr) {
                        var errorMessage = xhr.message;
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
                        grecaptcha.reset()
                        $('#response').html(alertHtml);

                    }
                });

            }
        });
    });

})();