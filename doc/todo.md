# Todo list
General tasks to do whicare not attached to a specifc file.

## Devops
- [ ] migrate code generation to use ddd-gen
- [ ] Work on project derivation
- [ ] Unit test for tokens
- [ ] Check Dusk tests
- [ ] Tests on jenkins

## Features
- [ ] Global Search Mechanism
- [x] Test API tokens
- [ ] Markdown rendered for messages of the day
- [ ] Metadata select availables tables and columns and subtypes

## Development

* Calendar Event
    * Find a way to test drag and extend events
    
    * Update calendar controller to prefill the form and return to fullcalendar view
    
    FullCalendar Test plan (Currently I do not know yet how to automate)
    
        * Create an event by clicking a date
            check that it exists in database
            check that we are returned to fullcalendar view
        
        * Modify an event by clicking on it
            check that the event has been updated in database
            check that we are returned to fullcalendar view
        
        * Delete an event by clicking on it
            check that it has been deleted in database
            
        * Drag an all day event
            check that both start date has been updated
            
        * Drag an event with an end time
             check that both start date and end date have been updated
             
        * Extend an all day event
            check that the start date has not moved
            check that the end date has been updated
            
        * Extend an event with an end time
            check that the start date has not moved
            check that the end date has been updated
            
        * Test dragging from allDay to specific time and vice versa
        
        * Tests on week view
            - dragging
            - resizing
            
        * Test on day view
            - dragging
            - resizing
                
        * Negative tests by calling the controller with bad parameters
            - non existing id
            - incorrectly formatted start, end
             