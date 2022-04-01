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

* id, integer table primary key. Experience has demonstrated that it is often a bad idea to use an application field as primary key, it is usually a nightmare when you need to change it.

* date and date time: birth date, validity date, departure time
* foreign keys
* integer: quantity, years, hours
* prices and currencies
* strings, names, descriptions, comments, phone numbers, email addresses,
* booleans
* boolean bitfields
* attachments: file, image, pictures
* enumerates

When enumerates need to be dynamic and can potentially be modified by the user or by an admin user they should be kept in a separate table. When they are static and their modification implies code modifications they can be stored as database enumerates inside the schema.


## Multiple fields

The current approach works well when one column in the database has only one field in form for input and only one field for display. It does not work as well when a unique field in database generates several fields in the GUI. 

For example passwords have a password and password confirm field for input and no display mechanism. Datetime in database may have a date and a time in the GUI (or not).

It is something to take into account. The field list to generate GUI must return not only the fields with a direct matching GUI field, but also the derived fields. In the same spirit it should be possible to query the metadata layer with the  name of real database fields but also with the ones of dummy or virtual fields.

This mechanism could be based on a naming convention. for example a password field in database would generate a password and password confirm fields in the GUI. And a datetime would generate fields with _date and _time suffixes.


## Datetime

Datetime are stored as a unique element in database. However they are often shows as separate fields in the form, one for the date with the date picker and one for the time.

To be coherent, I should support 4 types date, time and datetime and periods.

https://xdsoft.net/jqplugins/datetimepicker/


## Files and pictures

Database columns can contains files or pictures. They are just links to files saved in the tenant storage area.

For security reasons, the links to fetch an uploaded resource should not depend on the filename of the stored resource.
It should only depend on context. Context being, the logged in user, a database table, a database column, and a database primary key. It is the responsibility of the application to check that the active user is entitled for the resource that he fetches.

There is also the issue of file names conflicts. If two users upload the same file named document.pdf, the system must be able to retrieve the two documents without confusion.

Implementation
    * when a document is uploaded a random string is added to the filename
    * some context may also be added for house keeping
    * this filename is stored in the database and used to retrieve the file
    * actual filename shoud never be seen by the user

## Enumerates

Enumerates are strings with a set of acceptable values. Input of enumerates can be done with a select or a set of radio boxes. Enumerates can be tranlsated into the current language. 

Example:

    {
        "values":["app.locale","app.timezone","browser.locale"],
        "rules_to_add":["'regex:\/\\w+\\.\\w+(\\.\\w+)*\/'"]
    }

## Float and currencies

2 possibilities:
        
1. Generate Getters and setters
    It implies to handle the external format in the forms, for example to be able to input
    a value with the currency symbol and to transform before storing or updating
                
2. Just modify the display_field in the code generator helper. (simpler).
            
I also have to deal with decimal separator ...

The first approach is certainly the more complete. It make sense for date and datetime where the internal format can never be used externally and where all the views have to be specially designed for the external format. The second is much simpler, likely good enough for a lot of cases.

## Bitfields

Bitfields are integers or big integers for which every bit can be set or reset. Every bit has a label which can be localized and translated in the current language.
Display values can be a comma separated list of values. Input and create are either a set of labels and check-boxes or a non exclusive select.

When values are provided as a sequential list, the first index set the bit 0, etc.
When they are hash the encoding is specified and keys must be power of 2.

Example:

    code_gen_types.qualifications   
        {"subtype": "bitfield", 
         "values": ["redactor","student","pilot", "instructor", "winch_man", "tow_pilot", "president", "accounter", "secretary", "mechanic"]}

Should generate something like:

    <legend>Responsabilités club</legend>
    <table>
    <tr>
        <td align="right">Président     </td>
        <td align="left"><input type="checkbox" name="mniveau[]" value="2"  />      </td>
        <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;       </td>
        
        <td align="left">Vice-Président     </td>
        <td align="left"><input type="checkbox" name="mniveau[]" value="4"  />      </td>
        <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;       </td>
        <td align="left">Membre du CA       </td>
        <td align="left"><input type="checkbox" name="mniveau[]" value="64" checked="checked"  />       </td>
    </tr>
    ...
    
note that it may be convenient to support input edit and create into a table when there are a lot of values.


