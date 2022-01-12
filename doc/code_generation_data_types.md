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

* element ids, experience has demonstrated that it is often a by idea to use an application field as primary key, it is usually a nightmare when you need to change it.

* date and date time: birth date, validity date, departure time
* foreign keys
* integer: quantity, years, hours
* price and currency
* strings, names, descriptions, comments, phone numbers, email addresses,
* booleans
* boolean bitfields
* attachments: file, image, pictures
* enumerates

When enumerates need to be dynamic and can potentially be modified by the user or by an admin user they should be kept in a separate table. When they are static and their modification is liked to code modifications they can be stored as database enumerates inside the schema.

First case: calendar event types.
Second case, days of the week.
