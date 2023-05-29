(function () {
    "use strict";

    // Wait for the DOM to be ready
    $(function () {
        $('form.needs-validation').on('submit', function (event) {
            event.preventDefault();
            event.stopPropagation();
        });

        $('#logout').click(function () {
            $.ajax({
                type: 'POST',
                url: './../../ajax/login?op=logout',
                success: function (data) {
                    localStorage.removeItem('token');
                    window.location.href = './../../account/login';
                },
                error: function (xhr, status, error) {
                    // handle error response here
                }
            });
        });

        $('.switchPanels').click(function () {
            $.ajax({
                type: 'POST',
                url: './../../ajax/switch.php?op=process',
                success: function (data) {
                    //setTimeout(function () {
                    window.location.href = './../../index.php';
                    //}, 2000);
                },
                error: function (xhr, status, error) {
                    // handle error response here
                }
            });
        });

        if ($('#changepass-justified').length) {
            // Attach a click event handler to the "Save Changes" button
            $('#saveChanges').click(function (event) {
                event.preventDefault(); // Prevent form submission

                var form = $('form.needs-validation')[0];

                //// Retrieve the input values
                var oldPassword = $('#inputOldPassword').val();
                var newPassword = $('#inputNewPassword').val();
                var retypePassword = $('#inputReNewPassword').val();
                
                //// Check for empty inputs
                if (oldPassword.trim() === '' || newPassword.trim() === '' || retypePassword.trim() === '') {
                    var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                        .text('Please fill in all the fields.')
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
                
                    $('#changepass-justified #response').empty().append(alertDiv);
                
                    // Add 'is-invalid' class to the input fields
                    $('#inputOldPassword').addClass('is-invalid');
                    $('#inputNewPassword').addClass('is-invalid');
                    $('#inputReNewPassword').addClass('is-invalid');
                
                    // Re-validate the form to display error messages
                    form.reportValidity();
                
                    return; // Exit the function early
                }
                
                // Create an object with the data to send via AJAX
                var data = {
                    oldPassword: oldPassword,
                    newPassword: newPassword,
                    retypePassword: retypePassword
                };
                //
                // Perform the AJAX request
                $.ajax({
                    url: './../../ajax/settings.php?op=changepass', // Replace with the actual server endpoint
                    type: 'POST', // or 'GET' based on your server implementation
                    data: data,
                    success: function (response) {
                        var alertDiv = $('<div class="alert alert-success alert-dismissible fade show" role="alert">')
                            .text(response.message)
                            .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                        $('#changepass-justified #response').empty().append(alertDiv);
                    },
                    error: function (error) {
                        // Handle any errors that occur during the AJAX request
                        if (error.responseJSON.code === 10001) {
                            var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                                .text(error.responseJSON.message)
                                .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                            $('#changepass-justified #response').empty().append(alertDiv);

                            // Reload the page after 5 seconds
                            setTimeout(function () {
                                location.reload();
                            }, 5000);
                        } else if (error.responseJSON.code === 10003) {
                            var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                                .text(error.responseJSON.message)
                                .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                            $('#changepass-justified #response').empty().append(alertDiv);

                            // Passwords do not match
                            $('#inputNewPassword').removeClass('is-valid').addClass('is-invalid');
                            $('#inputReNewPassword').removeClass('is-valid').addClass('is-invalid');

                            // Display error messages using invalid-feedback
                            $('#inputNewPassword').siblings('.invalid-feedback').text('Passwords do not match.');
                            $('#inputReNewPassword').siblings('.invalid-feedback').text('Passwords do not match.');

                            // Re-validate the form to display error messages
                            form.reportValidity();
                        } else if (error.responseJSON.code === 10004) {
                            var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                                .text(error.responseJSON.message)
                                .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                            $('#changepass-justified #response').empty().append(alertDiv);

                            // Old password is wrong
                            $('#inputOldPassword').removeClass('is-valid').addClass('is-invalid');
                            
                            // Display error message using invalid-feedback
                            $('#inputOldPassword').siblings('.invalid-feedback').text('Old password is incorrect.');

                            // Re-validate the form to display error messages
                            form.reportValidity();
                        } else {
                            var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                                .text(error.responseJSON.message)
                                .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                            $('#changepass-justified #response').empty().append(alertDiv);
                        }
                    }
                });
            });

            // Attach input event handlers to the input fields
            $('#inputOldPassword, #inputNewPassword, #inputReNewPassword').on('input', function () {
                var form = $('form.needs-validation')[0];
                
                // Reset custom validity on input change
                //$(this).get(0).setCustomValidity('');
                
                // Clear any existing error messages
                $(this).siblings('.invalid-feedback').text('');
                
                // Add 'is-invalid' class to the input fields
                $(this).removeClass('is-invalid').addClass('is-valid');
                
                // Re-validate the form to remove error messages
                form.reportValidity();
            });
        }

    });

})();
