(function () {
    "use strict";

    // Wait for the DOM to be ready
    $(function () {

        function getReturnUrlFromLink() {
            // Get the URL of the current page
            var currentUrl = window.location.href;

            // Split the URL into an array of its parts
            var urlParts = currentUrl.split("?");

            // If the URL contains a query string
            if (urlParts.length > 1) {
                // Get the query string and split it into an array of key-value pairs
                var queryString = urlParts[1];
                var queryParams = queryString.split("&");

                // Loop through the key-value pairs and look for the 'return_url' parameter
                for (var i = 0; i < queryParams.length; i++) {
                    var param = queryParams[i].split("=");
                    if (param[0] == "return_url") {
                        // If the 'return_url' parameter is found, return its value
                        return decodeURIComponent(param[1]);
                    }
                }
            }

            // If the 'return_url' parameter is not found, return null
            return '';
        }

        var returnUrl = getReturnUrlFromLink();
        console.log(returnUrl);

        // Check if user is already logged in
        var token = localStorage.getItem('token');
        if (token) {
            // User is already logged in, redirect to home page
            window.location.href = './..' + returnUrl;
        }

        // Get the email and submit button elements
        var emailInput = $('#email');
        var submitButton = $('#login button[type="submit"]');

        // Attach a submit handler to the login form
        $('#login').submit(function (event) {
            // Stop the form from submitting normally
            event.preventDefault();

            // Get the email and password values
            var email = emailInput.val();
            var password = $('#password').val();

            // Get the reCAPTCHA response only if the site key is defined
            if ($('#g-recaptcha').data('sitekey') != 'NULL') {
                var recaptchaResponse = grecaptcha.getResponse();
                // Process the reCAPTCHA response here
            } else {
                var recaptchaResponse = null;
            }


            // Perform an AJAX request to the login endpoint
            $.ajax({
                url: '../ajax/login.php?op=process',
                method: 'POST',
                data: {
                    email: email,
                    password: password,
                    recaptcha_response: recaptchaResponse
                },
                success: function (response) {
                    // Store the token in local storage
                    localStorage.setItem('token', response.token);

                    // If the login was successful, redirect to the home page
                    window.location.href = './..' + returnUrl;
                },
                error: function (xhr) {
                    // If there was an error, display an error message
                    console.log(xhr);
                    var errorMessage = xhr.responseJSON.message;
                    var alertHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                        '<i class="bi bi-exclamation-octagon me-1"></i>' + errorMessage +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>';
                    $('#response').html(alertHtml);

                    // Enable the submit button again
                    //submitButton.prop('disabled', false);
                }
            });

            
        });

        
      
    });

})();
