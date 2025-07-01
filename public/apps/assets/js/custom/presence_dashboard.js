$(document).ready(function () {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        // plugins: ['list', 'dayGrid', 'timeGrid'],
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'title',
            center: '',
            right: 'today,prev,next'
        },
        slotLabelFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        events: function (fetchInfo, successCallback, failureCallback) {
            $.ajax({
                method: 'POST',
                url: '/fetch-agenda-calendar',
                dataType: 'JSON',
                success: function (response) {
                    successCallback(response);
                },
                error: function () {
                    failureCallback();
                }
            });
        },
        eventClick: function (info) {
            // viewAgenda(info.event._def.publicId)
        }
    });


    function loadData() {
        $.ajax({
            url: '/fetchDashboard',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                $('.c').html(response.leave + " <span class='text-soft'>Hari</span>");
                $('.d').html(response.resume.total_presensi + " <span class='text-soft'>Hari</span>");
                $('.w').html(response.resume.total_hours + " <span class='text-soft'>Jam</span>");

                if (response.eventL) {
                    var events = response.eventL.map(function (colevent) {
                        return {
                            id: colevent.id,
                            title: colevent.flag_mesg,
                            start: colevent.presence_date,
                            className: colevent.flag_color,
                            description: colevent.desc_attend,
                        };
                    });

                    if (calendar) {
                        calendar.removeAllEvents();
                        calendar.addEventSource(events);
                    } else {
                        console.error("Calendar is not initialized yet!");
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching calendar data: " + error);
            }
        });
    }

    calendar.render();
    loadData();

});