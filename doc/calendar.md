# Calendar

A calendar is a basic feature for Web applications. 

* Calendar views per month/week/day
* events CRUD, callable from the calendar view.

It is base for extensions with the possibility to add new types of events and business logic.
For example it will be possible to create a reservation system with several type of resources (for example a classroom, a teacher and a limited number of student, etc.).

## Database ERD (Entity Relation Diagram)

    Event
        title           String
        description     String - Multiline
        all_deay_event  Boolean
        start           datetime
        end             datetime
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
        
### Discussion on Database Schema

There are a lot of ways to represent calendar events in database

* Timestamps for start and end of event
* Timestamp for start of event and integer for duration  
* Separate fields for date and time

When the event represents some period of activity, the option timestamp plus duration may be convenient. The choice likely depends on how often events are created or changed, compared to the number of time addition of duration has to be done. Start time plus duration are more convenient for appointments, meetings, etc. When you record a flight duration, start and end time are more convenient as you do not know the duration without calculation. 

Note that whatever the choice for database storage it may be convenient to develop the accessors to get the other points of view.

Also note that the form to create or edit an event may be different from the internal format. At least the dates and timestamp will be stored in some kind of universal format (UTC time MySQL timestamp format) and the user interface with local timezone and format.

In the forms to handle events, it may be convenient to let the user input either the end of event or the duration whatever is the most convenient for her.

In MySQL the dates have a range from '1000-01-01' to '9999-12-31', while the timestamps  has a range of '1970-01-01 00:00:01' UTC to '2038-01-19 03:14:07' UTC.  (limit of a 32 bit integer which handles the time in second since january 1970)

[Interresting discussion on timestamps versus datetime](https://stackoverflow.com/questions/409286/should-i-use-the-datetime-or-timestamp-data-type-in-mysql)

In 2021, 2038 is almost tomorrow (17 years away), let's avoid a new year 2000 bug :-).
The project that this one is supposed to replaced has already been deployed 11 years ago.

### Validation

Invalid input: 
- event where end < start
- allDay and specified times

### Timezones

Dates and datetimes are stored in the database in UTC. Initially the conversion was done in PHP on the server according the the locale.timezone configuration value. It could be a better idea to let each user set its own time zone through the web browser localization.

It could also be better to send times in UTC to the client and do all the conversions in javascript.

https://stackoverflow.com/questions/39555702/how-to-autodetect-timezone-with-php-or-use-external-website

     
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

This version will use the database as default data source. If it becomes available the synchronization with Google calendar will only be optional.

Note also that Google Calendar handles very generic events. It could be convenient to develop a specific data model to handle more specific cases. The main reason of using Google calendar with the previous versions was to provide a public access to the calendar to unregistered users. That also can easily be handled with a specific read-only route and view.


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
  
## Datetime picker

    https://www.solodev.com/blog/web-design/adding-a-datetime-picker-to-your-forms.stml
    
## Fullcalendar view

    inside resources/views/tenants/calendar.blade.php
    extends resources/views/layouts/app.blade.php
    
    In the view
        <div id='calendar'></div>
    
    javascript in
    public/tenancy/assets/js/calendar_setting.js
    
    included by views/layouts/header.blade.php
    
    the data source is 
        http://abbeville.tenants.com/api/calendar
        http://abbeville.tenants.com/api/calendar/fullcalendar
        
    Note API routes for tenants are defined in routes/tenants.php, not api.php
    
### Fullcalendar actions

* Click on a date   -> open the create event form
* Click on an event -> open the edit form (with a delete button)
* Drag an event -> call drag_event function of the controller
* Resize an event -> call the resize_event function of the controller

start parameter is handled by the javascript layer as an English date in local time (all user facing date and time are in local time and are converted in UTC time before to be stored).



    