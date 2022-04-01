# Calendar

A calendar is a basic feature for Web applications. 

* There are calendar views per month/week/day
* It is possible to interact with the full calendar view to create, read, update and delete events.

It is a base for extensions with the possibility to add new types of events and business logic.
For example it will be possible to create a reservation system with several type of resources (a classroom, a teacher and a limited number of student, etc.).

## Database ERD (Entity Relation Diagram)

    Event
        title           String
        description     String - Multiline
        all_deay_event  Boolean
        start           datetime
        end             datetime
        color           string
                
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

It is also possible to send times in UTC to the client and do all the conversions in javascript. (not implemented)

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

The previous version of this project used Google calendar as their only data source.

It as deterred several potential users because of the complexity of obtaining an API key and configuring full calendar to use it. Even a good initial documentation did not help as the concpts are not simple and Google tends to change the way to get certificates. I had no time to monitor is and update the documentation.

As this application is multitenant, every tenant should have his own calendar. 

This version will use the database as default data source. If it becomes available the synchronization with Google calendar will only an option, not a requirement.

Google Calendar handles very generic events. It is possible to develop a specific data model to handle more specific cases. The main reason of using Google calendar with the previous versions was to provide a public access to the calendar to unregistered users and it has never been used. An alternative is to provide a specific read-only route and view.


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


## Events repetitions

Let's have a look to Google calendar, Google supports:

    - Choose time (button)
        - allday (checkbox)
            - allday not set
            timezone (button)
            same timezone for start and end (checkbox)
            timezone
            end timezone (greyed out when same timezone is set)
            
        - repeat menu
            - only once
            - every day
            - every week day
            - every week on Wednesday
            - every month the second Wednesday of the month
            - every year
            - user defined
                repeat every (exclusive)
                    n days
                    n weeks
                        on Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday (checkboxes)
                    n months
                        - every 12
                        - every second Wednesday of the month
                    n years on the day and month
                    
            - ends (exclusive)
                never
                after end date
                after n occurrences
                
### Event repetition use cases

    As a calendar user I want to be able to
    repeat an event
    in order to see it several times n the calendar
    
    As a calendar user I want to be able to
    change only one event or change all of them or change from this one
    in order to see it several times n the calendar
    
    change a repetition from the calendar event form.
    
    As a user I want to delete
    one event from a serie, all remaining events, all events
                                        
### Design:

* Repetition occurrences - calendar event one to many relationship

* timezone settings belong to repetition occurrences

* I need to be able to generate all (others) calendar events from a repetition. What when there is no end ???
Should they be computed dynamically when events are selected between two dates ? It would implies to look at all existing repetitions.

Or the end date of a repetition should be computed at creation time and used for event select.

Repetition should contains the date until when events have been generated

Simplification: no end = end in 10 years. Or no support of the no end option... Maybe it is the best option. It would force the user to make a choice. Without that I have to implement the most complicated case or take the risk of a poor implementation. Only the user knows if no ends means until next year, all my life, etc. In most of the cases, no end means I do net exactly know but I can find an upper boundary. 

* Calendar events having the same repetition look to share several attributes
    - start time
    - end time
    - all day
In fact they do not share them, they are only copied when events are generated and they can all be updated at the same time or individually.  

On modification, it is usual to ask if the modification only applies to the current occurrence or all the repetitions, or only one events and all its followers.
What about previous exception ? On Google calendar they are overwritten.


### Database schema (ERD)

        Repetition
            
            same_timezone   boolean
            timezone        string start or general timezone
            end_timezone    string 
            
            repetition_type enumerate none, every_day, every_week, every_month, every_year
            
            every_n         integer
            
            days_of_the_week: monday, tuesday, wednesday, thursday, friday, saturday, sunday        booleans
            day_in_month    for every_month
            week_of_the_month: first, second,third and fourth boolean
            month_of_the_year: january, february, .....

            end_after_n     integer
            end_after_date  date
            
            (no need for start_date or start event, they will be extarted from the ordered list of events attached to this repetition)

                         
            
            
                

    