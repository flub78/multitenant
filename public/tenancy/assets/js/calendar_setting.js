function day_clicked(info) {
    // alert('a day has been clicked: ' + info.dateStr);
    
    var url = '/calendar/create?start=' + info.dateStr + '&action=fullcalendar';    
    window.location = url;
 }

function event_clicked(calEvent) {
    // alert('event ' + info.event.url + ' has been clicked!');
    // Nothing to do to be redirected to the url specified in the event
}

function event_dragged(calEvent) {
    var str = 'event ' + calEvent.event.title + ' has been dragged!' + "\n";
    for (var i in calEvent) {
        str += i + ": " + calEvent[i] + "\n";
    }
    alert(str)  ; 
    alert('delta = ' + calEvent.delta);   
    // alert('start after dragging = ' + calEvent.event.start);    
    // alert('end = ' + calEvent.event.end);    

    var title = calEvent.event.title;
    var id = calEvent.event.id;
    var start = calEvent.event.start;
    
    var url = '/calendar/dragged?id=' + id + '&title=' + title + '&start=' + start;
    
        $.ajax({
            url : url,
            type : 'GET',
            success : function(code_html, statut) {
                // alert('Ajax dragged success');
            },

            error : function(resultat, statut, erreur) {
                alert("Ajax dragged error");
            },

            complete : function(resultat, statut) {
                // alert('Ajax dragged complete');
            }
        });
}

function event_resized(calEvent) {
    alert('event ' + calEvent.event.title + ' has been resized!');
    alert('id = ' + calEvent.event.id);
    alert('end = ' + calEvent.event.end);    
    
    var url = '/calendar/resized';
    
        $.ajax({
            url : url,
            type : 'GET',
            success : function(code_html, statut) {
                alert('Ajax resized success');
            },

            error : function(resultat, statut, erreur) {
                alert("Ajax resized error");
            },

            complete : function(resultat, statut) {
                alert('Ajax resized complete');
            }
        });
    
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
                url: '/api/calendar/fullcalendar', // use the `url` property
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
