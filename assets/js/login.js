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
        var emailInput = $('#email');
        var submitButton = $('#login button[type="submit"]');

        // Attach a submit handler to the login form
        $('#login').submit(function (event) {
            // Stop the form from submitting normally
            event.preventDefault();

            // Get the email and password values
            var email = emailInput.val();
            var password = $('#password').val();

            // Check if the email input is valid
            if (emailInput[0].checkValidity()) {
                // Disable the submit button while the AJAX request is being performed
                submitButton.prop('disabled', true);

                // Perform an AJAX request to the login endpoint
                $.ajax({
                    url: '../ajax/login.php?op=process',
                    method: 'POST',
                    data: {
                        email: email,
                        password: password
                    },
                    success: function (response) {
                        // Store the token in local storage
                        localStorage.setItem('token', response.token);

                        // If the login was successful, redirect to the home page
                        window.location.href = '/';
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
                        submitButton.prop('disabled', false);
                    }
                });
            } else {
                // Show the validation feedback message for the email input
                emailInput.addClass('is-invalid');
                emailInput.removeClass('is-valid');
            }
        });

        // Listen for changes to the email input value
        emailInput.on('input', function () {
            // Check if the email input is valid
            if (emailInput[0].checkValidity()) {
                // Show the validation feedback message for the email input
                emailInput.removeClass('is-invalid');
                emailInput.addClass('is-valid');

                // Enable the submit button if all inputs are valid
                if ($('#login')[0].checkValidity()) {
                    submitButton.prop('disabled', false);
                }
            } else {
                // Show the validation feedback message for the email input
                emailInput.addClass('is-invalid');
                emailInput.removeClass('is-valid');

                // Disable the submit button if any input is invalid
                submitButton.prop('disabled', true);
            }
        });
    });

})();
