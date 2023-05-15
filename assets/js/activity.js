(function () {
    "use strict";

    // Wait for the DOM to be ready
    $(function () {
        var calendarEl = document.getElementById('calendar');
        var bgColor = null;
        var calendar = new FullCalendar.Calendar(calendarEl, {
            expandRows: true,
            slotMinTime: '08:00',
            slotMaxTime: '20:00',
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            initialView: 'dayGridMonth',
            initialDate: '2023-05-10',
            navLinks: true, // can click day/week names to navigate views
            editable: true,
            selectable: true,
            selectMirror: true,
            select: function (arg) {
                var modal = $('#eventModal');
                modal.find('#titleInput').val('');
                modal.find('#startDateInput').val(arg.startStr);
                modal.find('#endDateInput').val(arg.endStr);
                modal.modal('show');

                modal.find('#saveEventBtn').off('click').on('click', function () {
                    var title = modal.find('#titleInput').val();
                    if (title) {
                        calendar.addEvent({
                            title: title,
                            start: arg.start,
                            end: arg.end,
                            allDay: arg.allDay
                        });
                        modal.modal('hide');
                    }
                });

                calendar.unselect();
            },
            eventClick: function (arg) {
                if (confirm('Are you sure you want to delete this event?')) {
                    arg.event.remove()
                }
            },
            nowIndicator: true,
            dayMaxEvents: true, // allow "more" link when too many events
            events: function (fetchInfo, successCallback, failureCallback) {
                $.ajax({
                    url: '/ajax/calendar.php?op=get_events',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.code === 10000) {
                            var rawEvents = response.data;
                            if (Array.isArray(rawEvents)) {
                                var events = rawEvents.map(function (event) {
                                    if (event.noClass === "true") {
                                        bgColor = 'd-grid badge bg-danger';
                                    } else {
                                        bgColor = 'd-grid badge bg-primary';
                                    }
                                    return {
                                        title: event.title,
                                        start: event.start,
                                        classNames: bgColor,
                                        end: event.end
                                    };
                                });
                                successCallback(events);
                            }
                        } else {
                            failureCallback(response.message);
                        }
                    },
                    error: function () {
                        failureCallback('Error fetching events');
                    }
                });
            }
        });

        calendar.render();

        $('#eventModal #allDayInput').change(function () {
            if ($(this).is(':checked')) {
                $('#eventModal #endDateForm').hide();
            } else {
                $('#eventModal #endDateForm').show();
            }
        });

    });
})();
