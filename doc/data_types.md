# Data Types

This project is data driven. It means that as far as possible, information from data types will be fetched from the database schema or others meta data sources and used to determine the treatments to apply to these data.

This approach is efficient, instead of writing code for every data, code is only written once for every data type. So you get slightly more complicated code but you get return on investment as soon as the pallication needs to handle several data of the same type.

Basically for every datatype we must have code for:

* display it in an array
* display it inside one element
* having an input method for the user to enter or select it.
* generate a validation rule, etc.

## User data types

Here is a list of the most common data types handled by a WEB application. This list will be completed along the way.

* element ids, experience has demonstrated that it is often a by idea to use an application field as primary key, it is usually a nightmare when you need to change it.

* date and date time: birth date, validity date, departure time
* foreign keys
* integer: quantity, year, hours
* price and currency
* strings, names, descriptions, comments, phone numbers, email addresses,
* booleans
* boolean bitfields
* attachments: file, image, pictures
