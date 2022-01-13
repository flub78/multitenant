# Code Generation Data Types

This project is data driven. It means that information from data types is fetched from the database schema or others meta data sources and used to determine the treatments to apply to these data.

This approach is efficient. Instead of writing code for every data, code is only written once for every data type. So you get slightly more complicated code but you get return on investment as soon as the application needs to handle several data of the same type.

Basically for every datatype we must have code to:

* display it in a list of element
* display it inside a single element
* input it within a form.
* generate a validation rule, etc.

## User data types

Here is a list of the most common data types handled by a WEB application. This list will be completed along the way.

* id, integer table primary key. Experience has demonstrated that it is often a by idea to use an application field as primary key, it is usually a nightmare when you need to change it.

* date and date time: birth date, validity date, departure time
* foreign keys
* integer: quantity, years, hours
* prices and currencies
* strings, names, descriptions, comments, phone numbers, email addresses,
* booleans
* boolean bitfields
* attachments: file, image, pictures
* enumerates

When enumerates need to be dynamic and can potentially be modified by the user or by an admin user they should be kept in a separate table. When they are static and their modification is liked to code modifications they can be stored as database enumerates inside the schema.

First case: calendar event types.
Second case, days of the week.
 
## Multiple fields

The current approach works well when one column in the database has only one field in form for input and only one field for display. It does not work as well when a unique field in database generates several fields in the GUI. 

For example passwords have a password and password confirm field for input and no display mechanism. Datetime in database may have a date and a time in the GUI (or not).

It is something to take into account. The field list to generate GUI must return not only the fields with a direct matching GUI field, but also the derived fields. In the same spirit it should be possible to query the metadata layer with the  name of real database fields but also with the ones of dummy or virtual fields.

This mechanism could be based on a naming convention. for example a password field in database would generate a password and password confirm fields in the GUI. And a datetime would generate fields with _date and _time suffixes.


## Datetime

Datetime are stored as a unique element in database. However they are often shows as separate fields in the form, one for the date with the date picker and one for the time.

To be coherent, I should support 4 types date, time and datetime and periods.

https://xdsoft.net/jqplugins/datetimepicker/



