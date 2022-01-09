/**
 * Callback when a day is clicked (create a new event)
 */
function day_clicked(info) {
    // alert('a day has been clicked: ' + info.dateStr);
    
    var url = '/calendar/create?start=' + info.dateStr + '&action=fullcalendar';    
    window.location = url;
 }

/*
 * Callback when an event is clicked
 */
function event_clicked(info) {
    // alert('event ' + info.event.url + ' has been clicked!');
    // Nothing to do to be redirected to the url specified in the event
}

/*
 * Callback when an event is dragged
 */
function event_dragged(info) {
    var str = 'event ' + info.event.title + ' has been dragged!' + "\n";
    // alert(str)  ; 

    // get information from the event
    var title = info.event.title;
    var id = info.event.id;
    var start = info.event.startStr;
    var end = info.event.endStr;
    var allDay = info.event.allDay;    
    
    // Build the URL to call
    var url = '/calendar/dragged?id=' + id + '&title=' + title + '&start=' + start;   
    url += '&end=' + end;
    url += '&allDay=' + allDay;
    
    // Call the URL 
    $.ajax({
        url : url,
        type : 'GET',
        success : function(code_html, statut) {
            var reply = JSON.parse(code_html);
            if (reply.status != "OK") {
                // Error returned by the PHP layer (the server has replied)
                alert("Error returned by PHP function dragged" + reply.error.message);
                info.revert();
            } else {
                // alert("PHP dragged success");
            }
        },

        error : function(resultat, statut, erreur) {
            // Error returned by the Ajax call, the server has not been reached
            // alert("Ajax error");
        },

        complete : function(resultat, statut) {
            //alert('Ajax dragged complete');
        }
    });
}

/**
 * Callback when an event is resized
 */
function event_resized(info) {
    // alert('event ' + info.event.title + ' has been resized!');
    
    // get the event information
    var title = info.event.title;
    var id = info.event.id;
    var start = info.event.startStr;
    var end = info.event.endStr;
    var allDay = info.event.allDay;
    
    // prepare the URL to call    
    var url = '/calendar/resized?id=' + id + '&title=' + title + '&start=' + start;
    url += '&end=' + end;
    url += '&allDay=' + allDay;
    
    // call the URL and analyze the return
    $.ajax({
        url : url,
        type : 'GET',
        success : function(code_html, statut) {
            var reply = JSON.parse(code_html);
            if (reply.status != "OK") {
                // Error returned by the PHP layer (the server has replied)
                alert("Error returned by PHP function resized" + reply.error.message);
                info.revert();
            } else {
                // alert("PHP resized success");
            }
        },

        error : function(resultat, statut, erreur) {
            alert("Ajax error");
        },

        complete : function(resultat, statut) {
            // alert('Ajax resized complete');
        }
    });
}

/*
 * Callback when allDay checkbox is checked
 */ 
function all_day_set() {
    // alert('all_day_set');
    $( "#start_time" ).prop( "disabled", true );
    $( "#end_time" ).prop( "disabled", true );
}

/*
 * Callback when allDay checkbox is unchecked
 */ 
function all_day_unset() {
    // alert('all_day_unset');
    $( "#start_time" ).prop( "disabled", false );
    $( "#end_time" ).prop( "disabled", false );
}

/*
 * Callback when allDay checkbox is made visible. edit, 
 * form redisplay after validation failure, etc.
 */ 
function all_day_visible() {
    if($("#allDay").is(":checked")) {
        all_day_set();
    } else {
        all_day_unset();
    }
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
        // timeZone: 'UTC',
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
