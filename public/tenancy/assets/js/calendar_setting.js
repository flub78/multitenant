function day_clicked() {
    alert('a day has been clicked!');
}

function event_clicked(info) {
    alert('event ' + info.event.title + ' has been clicked!');
}

function event_dragged() {
    alert('an event has been dragged!');    
}

function event_resized(info) {
    alert('event ' + info.event.title + ' has been resized!');
    alert('end = ' + info.event.end);    
}

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',             // change day and month names, but not the buttons
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        eventSources: [
            // your event source
            {
                url: '/json', // use the `url` property
                color: 'yellow',    // an option!
                textColor: 'black'  // an option!
            }
            // any other sources...
        ],

        eventClick: event_clicked,
        eventDrop: event_dragged,
        eventResize: event_resized,
        dateClick: function(info) {
            day_clicked(info);
        }
        });
        calendar.render();
});
