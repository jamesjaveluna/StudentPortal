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
        //var token = localStorage.getItem('token');
        //if (token) {
        //    // User is already logged in, redirect to home page
        //    window.location.href = './..' + returnUrl;
        //}


        // Attach a submit handler to the login form
        $('#login').submit(function (event) {
            // Stop the form from submitting normally
            event.preventDefault();

            var form = $('form.needs-validation')[0];

            // Get the email and password values
            var emailInput = $('#email').val();
            var passwordInput = $('#password').val();

            //Reset inputs
            $('#email').removeClass('is-valid');
            $('#email').removeClass('is-invalid');
            $('#password').removeClass('is-valid');
            $('#password').removeClass('is-invalid');

            if (emailInput.trim() === '' || passwordInput.trim() === '') {
                var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                    .text('Please fill in all the fields.')
                    .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                $('#response').empty().append(alertDiv);

                // Add 'is-invalid' class to the input fields
                $('#email').addClass('is-invalid');
                $('#password').addClass('is-invalid');

                // Re-validate the form to display error messages
                form.reportValidity();

                return; // Exit the function early
            }

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
                    email: emailInput,
                    password: passwordInput,
                    recaptcha_response: recaptchaResponse
                },
                success: function (response) {
                    // Store the token in local storage
                    localStorage.setItem('token', response.token);

                    // If the login was successful, redirect to the home page
                    window.location.href = './..' + returnUrl;
                },
                error: function (xhr) {
                    var errorMessage = xhr.responseJSON.message;
                    var alertHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                        '<i class="bi bi-exclamation-octagon me-1"></i>' + errorMessage +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>';
                    $('#response').html(alertHtml);

                    if (xhr.responseJSON.code === 10001) { // Recaptcha error
                        $('#login').removeClass('was-validated');

                        // Make recaptcha invalid
                        $('#g-recaptcha').removeClass('is-valid').addClass('is-invalid');

                        // Display error messages using invalid-feedback
                        $('#g-recaptcha').siblings('.invalid-feedback').text('Recaptcha is invalid.');
                    } else if (xhr.responseJSON.code === 10002) { // Email and/or password error
                        $('#login').removeClass('was-validated');

                        // Set the input as invalid
                        $('#email').get(0).setCustomValidity('');
                        $('#password').get(0).setCustomValidity('');

                        // Make inputs invalid
                        $('#email').removeClass('is-valid').addClass('is-invalid');
                        $('#password').removeClass('is-valid').addClass('is-invalid');

                        // Display error messages using invalid-feedback
                        $('#email').siblings('.invalid-feedback').text('Email is incorrect.');
                        $('#password').siblings('.invalid-feedback').text('Password is incorrect');

                        // Re-validate the form to display error messages
                        form.reportValidity();
                    } else {

                    }
                    

                    // Enable the submit button again
                    //submitButton.prop('disabled', false);
                    grecaptcha.reset()
                }
            });

            
        });

        $('#email, #password').on('input', function () {
            var form = $('form.needs-validation')[0];

            // Clear any existing error messages
            $(this).siblings('.invalid-feedback').text('');

            // Add 'is-invalid' class to the input fields
            $(this).removeClass('is-invalid').addClass('is-valid');

            // Re-validate the form to remove error messages
            //form.reportValidity();
        });

        
      
    });

})();
