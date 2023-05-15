(function () {
    "use strict";

    // Wait for the DOM to be ready
    $(function () {
        // Check if user is already logged in
        //var token = localStorage.getItem('token');
        //if (token) {
        //    // User is already logged in, redirect to home page
        //    window.location.href = '/';
        //}

        // Get the email and submit button elements
        var studentIDInput = $('#studentID');
        var studentIDInput1 = $('#studentID')[0];
        var emailInput = $('#email');
        var emailInput1 = $('#email')[0];
        var birthdateInput = $('#birthdate');
        var birthdateInput1 = $('#birthdate')[0];
        var usernameInput = $('#username');
        var usernameInput1 = $('#username')[0];
        var submitButton = $('#register button[type="submit"]');
        var emailInput1 = $('#email')[0];
        var acceptTermsInput = $('#acceptTerms');
        var acceptTermsInput1 = $('#acceptTerms')[0];

        

        // Attach a submit handler to the login form
        $('#register').submit(function (event) {
            // Stop the form from submitting normally
            event.preventDefault();

            // Check if terms checkbox is checked
            if (!acceptTermsInput.prop('checked')) {
                acceptTermsInput1.setCustomValidity('Please accept the terms and conditions');
                return;
            } else {
                acceptTermsInput1.setCustomValidity('');
            }

            // Get the email and password values
            var studentId = studentIDInput.val();
            var email = emailInput.val();
            var birthdate = birthdateInput.val();
            var username = usernameInput.val();

            // Get the reCAPTCHA response only if the site key is defined
            if ($('#g-recaptcha').data('sitekey') != 'NULL') {
                var recaptchaResponse = grecaptcha.getResponse();
                // Process the reCAPTCHA response here
            } else {
                var recaptchaResponse = null;
            }

            // Perform an AJAX request to the login endpoint
            $.ajax({
                url: '../ajax/login.php?op=register',
                method: 'POST',
                data: {
                    studentID: studentId,
                    email: email,
                    birthdate: birthdate,
                    username: username,
                    recaptcha_response: recaptchaResponse
                },
                success: function (xhr) {
                    // If there was an error, display an error message
                    var errorMessage = xhr.message;
                    var alertHtml = '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                        '<i class="bi bi-exclamation-octagon me-1"></i>' + errorMessage +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>';
                    $('#response').html(alertHtml);

                    setTimeout(function () {
                        window.location.href = "login.php";
                    }, 5000); 

                },
                error: function (xhr) {
                    var errorMessage = xhr.responseJSON.message;
                    console.log(errorMessage);
                    emailInput1.setCustomValidity('');
                    usernameInput1.setCustomValidity('');
                    studentIDInput1.setCustomValidity('');
                    birthdateInput1.setCustomValidity('');

                    $('#email-error').text('Please enter a valid Email adddress.');
                    $('#username-error').text('Please enter a valid username!');
                    $('#studentID-error').text('Please enter a valid Student ID.');
                    $('#birthdate-error').text('Please enter a valid Birthdate.');

                    var alertHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                        '<i class="bi bi-exclamation-octagon me-1"></i>' + errorMessage +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>';

                    switch (xhr.responseJSON.code) {
                        case 0:
                            studentIDInput1.setCustomValidity('Invalid Student ID');
                            birthdateInput1.setCustomValidity('Invalid Birthdate');
                            $('#studentID-error').text('Invalid Student ID');
                            $('#birthdate-error').text('Invalid Birthdate');
                            break;
                        
                        case 1:
                            emailInput1.setCustomValidity('Email already exists');
                            $('#email-error').text('Email already taken.');
                            break;

                        case 3:
                            usernameInput1.setCustomValidity('Username already taken');
                            $('#username-error').text('Username already taken');
                            break;

                        case 4:
                            var alertHtml = '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                '<i class="bi bi-exclamation-octagon me-1"></i>' + errorMessage +
                                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                                '</div>';
                            break;
                    }

                    grecaptcha.reset()
                    $('#response').html(alertHtml);
                }
            });

        });
    });

})();
