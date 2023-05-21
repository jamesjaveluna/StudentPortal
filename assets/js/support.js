(function () {
    "use strict";

    // Wait for the DOM to be ready
    $(function () {

        function createTicket() {
            // Retrieve form input values
            var issueType = $('#createTicketForm #issueType').val();
            var title = $('#createTicketForm #r_title').val();
            var message = $('#createTicketForm #r_message').val();

            // Create a data object to send in the AJAX request
            var data = {
                issue_type: issueType,
                title: title,
                message: message
            };

            // Send AJAX request to create the ticket
            $.ajax({
                url: './../ajax/support.php?op=create_ticket',
                method: 'POST',
                data: data,
                success: function (response) {
                    console.log('Ticket created successfully');
                    console.log(response);

                    // Reset form fields
                    $('#createTicketForm #issueType').val('');
                    $('#createTicketForm #r_title').val('');
                    $('#createTicketForm #r_message').val('');

                    // Display success message or perform other actions as needed
                    var ticketId = response.ticket_id;
                    window.location.href = './support/' + ticketId;
                },
                error: function (xhr, status, error) {
                    console.log('An error occurred while creating the ticket');
                    console.log(error);

                    // Display error message or perform other actions as needed
                }
            });
        }

        // Call the createTicket function when the form is submitted
        $('#createTicketBtn').click(function () {
            event.preventDefault(); // Prevent form submission

            createTicket(); // Call the createTicket function
        });

        // Function to send a message
        function sendMessage() {
            var message = $('#messageContent').val();
            var ticketID = $('#ticket_id').val();
            var timestamp = new Date().toLocaleString();

            // Create the message HTML
            var messageHtml = '<div class="row mt-2">' +
                '<div class="col-lg-4"></div>' +
                '<div class="col-lg-8 col-sm-12">' +
                '<div class="alert alert-info alert-dismissible fade show text-dark" role="alert">' +
                message +
                '<small><p class="mb-0 text-end text-secondary">' + timestamp + '</p></small>' +
                '</div>' +
                '</div>' +
                '</div>';

            // Append the message to the messages container
            $('#messages').append(messageHtml);

            // Send AJAX request to support.php
            $.ajax({
                url: './../ajax/support.php?op=send_message',
                method: 'POST',
                data: {
                    ticket_id: ticketID,
                    message: message
                },
                success: function (response) {
                    console.log('Message sent successfully');

                    var messagesContainer = $('#messages');
                    var scrollTo = messagesContainer.prop('scrollHeight') - messagesContainer.height();
                    messagesContainer.scrollTop(scrollTo);
                },
                error: function (xhr, status, error) {
                    console.log('An error occurred while sending the message');
                    console.log(error);

                    var messagesContainer = $('#messages');
                    var scrollTo = messagesContainer.prop('scrollHeight') - messagesContainer.height();
                    messagesContainer.scrollTop(scrollTo);
                }
            });

            $('#messageContent').val('');
            $('#messageContent').focus();
        }

        // Call the sendMessage function when the send button is clicked
        $('#sendMessageBtn').click(function () {
            sendMessage();
        });

        // Call the sendMessage function when Enter key is pressed
        $('#messageContent').keypress(function (event) {
            if (event.which === 13) { // 13 is the keycode for Enter
                event.preventDefault(); // Prevent the default Enter key behavior
                sendMessage();
            }
        });
    });

})();
