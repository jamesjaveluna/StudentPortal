(function () {
    "use strict";

    // Wait for the DOM to be ready
    $(function () {


        $('#logout').click(function () {
            $.ajax({
                type: 'POST',
                url: './../ajax/login.php?op=logout',
                success: function (data) {
                    localStorage.removeItem('token');
                    window.location.href = './../account/login.php';
                },
                error: function (xhr, status, error) {
                    // handle error response here
                }
            });
        });

        $('.switchPanels').click(function () {
            $.ajax({
                type: 'POST',
                url: './../ajax/switch.php?op=process',
                success: function (data) {
                    //setTimeout(function () {
                    window.location.href = './../blank.php';
                    //}, 2000);
                },
                error: function (xhr, status, error) {
                    // handle error response here
                }
            });
        });


    });

})();
