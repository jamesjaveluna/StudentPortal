(function () {
    "use strict";

    // Wait for the DOM to be ready
    $(function () {
        $('#importButton').click(function () {
            var fileInput = $('#fileInput');
            var file = fileInput.prop('files')[0];
            var inputValue = $('#operator').val(); // Get the value of the input field with id "test"

            // Disable the file input
            $('#fileInput').prop('disabled', true);
            $('#cancelButton').prop('disabled', true);
            $('#closeButton').prop('disabled', true);

            // Show the spinner
            $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Import');

            if (file) {
                var formData = new FormData();
                formData.append('xlsFile', file);

                $.ajax({
                    url: '/ajax/import.php?op=' + encodeURIComponent(inputValue), // Append the input value to the URL
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        //console.log(response);
                    },
                    error: function (xhr, status, error) {
                        //console.log(xhr.responseText);
                    },
                    complete: function () {
                        // Re-enable the file input and hide the spinner
                        $('#fileInput').prop('disabled', false);
                        $('#cancelButton').prop('disabled', false);
                        $('#closeButton').prop('disabled', false);
                        $('#importButton').html('Import');
                    }
                });
            } else {
                console.log('Please select a file.');
            }
        });

        $('.resendEmail').click(function () {
            // Get the data-id value from the button's data attribute
            var dataId = $(this).data('id');

            // Disable the button to prevent redundancy
            $(this).prop('disabled', true);

            // Send the AJAX request
            $.ajax({
                url: 'ajax/login.php?op=admin_resend',
                method: 'POST', // or 'GET' depending on your server-side implementation
                data: { id: dataId },
                success: function (response) {
                    var alertDiv = $('<div class="alert alert-success alert-dismissible fade show" role="alert">')
                        .text(response.message)
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                    $('#response').empty().append(alertDiv);
                },
                error: function (xhr, status, error) {
                    var response = xhr.responseJSON;
                    var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                        .text(response.message)
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                    $('#response').empty().append(alertDiv);
                }
            });
        });

        $('.deregisterBtn').click(function () {
            var id = $(this).data('id');
            var fullName = $(this).data('name');
            $('#full-name-placeholder').text(fullName);
            $('#confirmDeleteBtn').attr('data-id', id);
        });

        $('#addFirstPage #searchQuery').click(function () {
            var inputValue = $('#addFirstPage #query').val();

            // Send the AJAX request
            $.ajax({
                url: 'ajax/login.php?op=admin_nameid_query',
                method: 'POST',
                data: { query: inputValue },
                success: function (response) {
                    $('#addFirstPage #s_id').val(response.student_id).attr('class', 'form-control valid');
                    $('#addFirstPage #f_name').val(response.student_fname).attr('class', 'form-control valid');

                    $('#addModal #nextBtn').prop('disabled', false);
                },
                error: function (xhr, status, error) {
                    $('#addFirstPage #s_id').val('Cannot be found.').attr('class', 'form-control');
                    $('#addFirstPage #f_name').val('Cannot be found.').attr('class', 'form-control');

                    $('#addModal #nextBtn').prop('disabled', true);
                }
            });

        });

        $('#addBtnExecuter').click(function () {
            // Switch Page
            $('#addModal #addFirstPage').css('display', 'block');
            $('#addModal #addSecondPage').css('display', 'none');

            // Buttons
            $('#addModal #cancelButton').css('display', 'block');
            $('#addModal #prevBtn').css('display', 'none');
            $('#addModal #nextBtn').css('display', 'block');
            $('#addModal #submitBtn').css('display', 'none');
        });

        $('#addModal #submitBtn').click(function () {
            var studentID = $('#addFirstPage #s_id').val();
            var fullName = $('#addFirstPage #f_name').val();
            var username = $('#addSecondPage #u_name').val();
            var email = $('#addSecondPage #e_mail').val();
            var password = $('#addSecondPage #u_pass').val();
            var type = $('#addSecondPage #u_type').val();

            $.ajax({
                url: 'ajax/login.php?op=admin_create',
                method: 'POST',
                data: {
                    std_id: studentID,
                    f_name: fullName,
                    username: username,
                    email: email,
                    password: password,
                    type: type
                },
                success: function(xhr, status, error) {
                    var response = xhr.responseJSON;
                    var alertDiv = $('<div class="alert alert-success alert-dismissible fade show" role="alert">')
                        .text(xhr.message)
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                    $('#response').empty().append(alertDiv);
                    $('#addModal').modal('hide');
                    setTimeout(function () {
                        location.reload();
                    }, 5000); 
                },
                error: function (xhr, status, error) {
                    var response = xhr.responseJSON;
                    var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                        .text(response.message)
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                    $('#addModal #response').empty().append(alertDiv);
                }
            });
        });


        $('#addModal #nextBtn').click(function () {
            var isSIdValid = $('#addFirstPage #s_id').hasClass('valid');
            var isFNameValid = $('#addFirstPage #f_name').hasClass('valid');

            if (isSIdValid && isFNameValid) {
                // Switch Page
                $('#addModal #addFirstPage').css('display', 'none');
                $('#addModal #addSecondPage').css('display', 'block');

                // Buttons
                $('#addModal #cancelButton').css('display', 'none');
                $('#addModal #prevBtn').css('display', 'block');
                $('#addModal #nextBtn').css('display', 'none');
                $('#addModal #submitBtn').css('display', 'block');

            } else {
                $('#addModal #nextBtn').prop('disabled', true);
            }

        });

        $('#addModal #prevBtn').click(function () {

            // Switch Page
            $('#addModal #addFirstPage').css('display', 'block');
            $('#addModal #addSecondPage').css('display', 'none');

            // Buttons
            $('#addModal #cancelButton').css('display', 'block');
            $('#addModal #prevBtn').css('display', 'none');
            $('#addModal #nextBtn').text('Next Page');

        });

        $('#confirmationModal #confirmDeleteBtn').click(function () {
            var id = $(this).data('id');
            $.ajax({
                url: '/ajax/login.php?op=admin_delete',
                type: 'POST',
                data: { 'user_id': id },
                success: function (response) {
                    var alertDiv = $('<div class="alert alert-success alert-dismissible fade show" role="alert">')
                        .text(response.message)
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                    $('#response').empty().append(alertDiv);
                    setTimeout(function () {
                        location.reload();
                    }, 5000); 
                },
                error: function (xhr, status, error) {
                    var response = xhr.responseJSON;
                    var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                        .text(response.message)
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                    $('#response').empty().append(alertDiv);
                },
                complete: function () {
                    $('#confirmation-modal').modal('hide');
                }
            });
        });

        $('.editBtn').click(function () {
            var id = $(this).data('id');
            var row = $(this).closest('tr');
            var rowData = row.find('td').map(function () {
                return $(this).text();
            }).get();

            // Populate the editModal with the data from the selected row
            $('#editModal #std_id').val(rowData[0]);
            $('#editModal #f_name').val(rowData[1]);
            $('#editModal #u_name').val(rowData[2]);
            $('#editModal #e_mail').val(rowData[3]);
            $('#editModal #u_type').val(rowData[4]);

            $('#saveBtn').attr('data-id', id);
        });

        $('#saveBtn').click(function () {
            var id = $(this).data('id'); // Get the data-id value from the button's data attribute

            // Retrieve the updated values from the form
            var username = $('#u_name').val();
            var userEmail = $('#e_mail').val();
            var userType = $('#u_type').val();

            // Create an object with the updated data
            var updatedData = {
                id: id,
                username: username,
                email: userEmail,
                type: userType
            };

            // Send the AJAX request to update the user
            $.ajax({
                url: '/ajax/login.php?op=admin_edit',
                type: 'POST',
                data: updatedData,
                success: function (response) {
                    var alertDiv = $('<div class="alert alert-success alert-dismissible fade show" role="alert">')
                        .text(response.message)
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                    $('#response').empty().append(alertDiv);
                    setTimeout(function () {
                        location.reload();
                    }, 5000); 
                },
                error: function (xhr, status, error) {
                    var response = xhr.responseJSON;
                    var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
                        .text(response.message)
                        .append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');

                    $('#response').empty().append(alertDiv);
                }
            });
        });
    });
})();
