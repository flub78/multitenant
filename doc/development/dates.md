# Dates

Problem with date and times management, is that they can be used for so many purposes that it is difficult to desing a one size fits all support.

It is why most of the libraries, languages and databases come witheir own design and set of limitations.

The basic support for multitenant handle dats from 1900-1-1 00:00 to 2099-12-31 59:59 (or more but at least this range is guarantee) and a time accuracy of one second, format are locallized and there is support for timezones.

It is possible to set a better accuracy or to create new times and dates types.

* Dates are stored in database in YYYY-MM-DD (2022-09-15) format
* Datetimes are stored in YYYY-MM-DD hh:mm:ss (2022-09-04 08:30:13) format 
* in database date times and times are in UTC

Initial id was to have special getters and setters to change the dates to local format (DD/MM/YYYY in France).


## Extract from unit test

	$another_long_time_ago = Carbon::createFromFormat('Y-m-d H:i:s',  '1975-05-21 12:34:56');
	
## Extract from lang/en/general.php

	'database_date_format' => 'Y-m-d',
	'time_format' => 'H:i',
	'datetime_format' => 'm-d-Y H:i',				// code not compatible with m-d-Y H:i:s

	
## Times

Times represents a moment inside a day. They can be expressed in hour, minutes, seconds, milliseconds with different accuracy.

In MySql times range from -838:59:59 to +838:59:59 so an interval or more than a month.

As the project is switching to Bootstrap 5 which do not use JQuery any more, is it a good idea to use JQuery time pickers ?

### Time format

Most current time format both in English and French are H:i and H:i:s

I want tu support keyboard input as it is quite faste to type 4 digits and a column. But also to support mouse input.

There is a way to specify a step parameter in second to have a third field.
and a datalist 





