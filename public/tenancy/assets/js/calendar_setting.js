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

    var locale = document.documentElement.lang;
    
    // TODO organize localization with one file per language
    var buttonText = {
        today:    'today',
        month:    'month',
        week:     'week',
        day:      'day',
        list:     'list'};
        
    if (locale == 'fr') {
        buttonText = {
            today:    'Aujourdhui',
            month:    'Mois',
            week:     'Semaine',
            day:      'Jour',
            list:     'Liste'};       
    }
    
    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: locale,             // change day and month names, but not the buttons (but it should)
        buttonText: buttonText,
        editable: true,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        eventSources: [
            // your event source
            {
                url: '/api/calendar', // use the `url` property
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
