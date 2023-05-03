(function () {
    "use strict";

    // Wait for the DOM to be ready
    $(function () {
        // Check if user is already logged in
        var token = localStorage.getItem('token');
        if (token) {
            // User is already logged in, redirect to home page
            window.location.href = '/';
        }

        // Get the email and submit button elements
        var codeInput = $('#code');
        var passwordInput = $('#password');
        var passwordInput1 = $('#password')[0];
        var repasswordInput = $('#repassword');
        var repasswordInput1 = $('#repassword')[0];
        var submitButton = $('#verify button[type="submit"]');

        // Attach a submit handler to the login form
        $('#verify').submit(function (event) {
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
                passwordInput[0].setCustomValidity("");
                repasswordInput[0].setCustomValidity("Passwords do not match.");

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
                    url: '../ajax/login.php?op=verify',
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

                        // Store the token in local storage
                        localStorage.setItem('token', response.token);

                        // If the login was successful, redirect to the home page
                        setTimeout(function () {
                            window.location.href = "/";
                        }, 5000); 
                    },
                    error: function (xhr) {
                        // If there was an error, display an error message
                        var errorMessage = xhr.responseJSON.message;
                        var alertHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                            '<i class="bi bi-exclamation-octagon me-1"></i>' + errorMessage +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                            '</div>';
                        $('#response').html(alertHtml);

                    }
                });

            }
        });
    });

})();
