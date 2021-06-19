# Calendar

(still a project)

A calendar is a basic feature for a lot of Web application. 

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

# Maybe later

* connection with Google Calendar
