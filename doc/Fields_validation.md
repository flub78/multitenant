# Fields validation

    https://laravel.com/docs/8.x/validation
    
As much as possible it is recommended to create a request for validation.

    class CalendarEventRequest extends FormRequest {
    
see examples in app/Http/Requests

## Validation with several fields

Sometimes a field can only be validated in relation with others ones. FOr example an end date must be after a start date.

In this case ...