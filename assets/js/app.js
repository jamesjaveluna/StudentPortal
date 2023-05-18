(function () {
    "use strict";

    // Wait for the DOM to be ready
    $(function () {


        $('#logout').click(function () {
            $.ajax({
                type: 'POST',
                url: './../ajax/login?op=logout',
                success: function (data) {
                    localStorage.removeItem('token');
                    window.location.href = './../account/login';
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
                    window.location.href = './../blank';
                    //}, 2000);
                },
                error: function (xhr, status, error) {
                    // handle error response here
                }
            });
        });


    });

})();
