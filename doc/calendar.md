# Calendar

A calendar is a basic feature for Web applications. 

* Calendar views per month/week/day
* events CRUD, callable from the calendar view.

It is base for extensions with the possibility to add new types of events and business logic.
For example it will be possible to create a reservation system with several type of resources (for example a classroom, a teacher and a limited number of student, etc.).

## Database ERD

    Event
        title           String
        description     String - Multiline
        all_deay_event  Boolean
        start_date      date
        start_time      time
        end_date        date
        end_time        time
        color           string
        
    Repetition
        event
        every day|week|month|year
        repeat_on Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday
        End_Type    never|at_date|after_number
        end_date
        number_of
        
    Notification
        event
        type    application|email|SMS
        number
        unit    minute|hours|days|weeks
        
    
## Rendering

The calendar rendering is done using the FullCalendar javascript module (https://fullcalendar.io/).

The CDN is here : https://www.jsdelivr.com/package/npm/fullcalendar?tab=collection

    <script src="https://cdn.jsdelivr.net/combine/npm/fullcalendar@5.8.0/locales-all.js,npm/fullcalendar@5.8.0/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.8.0/locales-all.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.8.0/main.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.8.0/main.css">
    
## Inspiration

An example of full calendar can be found in GVV

The view, including an event create form and an even update/delete form. The two forms are initially hidden.
    
    'id' => 'calendar_form', 'style' => "display:none" 
in
    views/calendar.php    

The javascript is handled in

    javascript/calendar.js
    
## Data sources
    
### Google calendar

Previous version of this project have used Google calendar as their only data source.

It as deterred several potential users due to the complexity of obtaining an API key and configuring full calendar to use it. As this application is multitenant at least one calendar per tenant is needed. 

This version will use the database as default data source. If proposed the synchronization with Google calendar will only be optional.

Note also that Google Calendar handles very generic events. It could be more convenient to develop a specific data model to handle more specific cases. The main reason of using Google calendar with the previous versions was to provide a public access to the calendar to unregistered users. That also can easily be handled with a specific read-only route and view.


### JSON feeds

The calendar can get the events from an URL providing a json events description.

    [
        {
          "title": "Event 1",
          "start": "2021-06-22T09:00:00",
          "end": "2021-06-22T18:00:00"
        },
        {
          "title": "Event 2",
          "start": "2021-06-22",
          "end": "2021-06-22"
        }
    ]
  
