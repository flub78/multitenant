function day_clicked() {
    alert('a day has been clicked!');
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

          dateClick: function() {
            day_clicked();
          }
        });
        calendar.render();
      });
