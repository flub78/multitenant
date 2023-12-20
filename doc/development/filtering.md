# Filtering

Filtering is the capacity to select only subset of the data either in the table views or in the APIs.

The idea is not to support a full query language like SQL but to provide a simple mechanism to select data. If complex selection mechanisms are required, special controllers will be developed.

## Use cases

As a user I want to select rows with a field equal to a given value.

As a user I want to select on multiple fields.

The selection must be persistent at least for the session

The user must be able to reset the selection and see all the rows.

## REST API

The rest APIs support a filter parameter with the following syntax:

* The filter is a comma separated list of select criteria

* a filter criteria is a field name, a column, an optional operator and a value

Ex:

	?filter=black_and_white:1
	
	?filter='birthday:>' . $mid_date

### Implementation notes

On the rest API, filter criteria are passed as HTML parameter.

On previous project the filter data were stored in the session and the controller was fetching them from there.

A controller can easily analyze the content of a form to determine what rows should be selected. It is a little more complicated to find out if the "display all" button has been clicked.

One possible implementation is to just clear the selection form on "display all" click. However by doing that the selection is not persistent if another page is visited. Is it an issue ?

I like a lot the dynamic search of datatable where the selection is dynamic. 

## GUI filtering

Filtering on GUI views implies to generate a set of input fields to define the filter configuration.


## Initial implementation

I'd like to:
- have the WEB index method accepting the same filter parameter than the API REST controller (to be consistent).
- have a filter method analysing the filter form values and generating a parameter to the format above
- then this filter method should call the regular index method or redirect.

This approach is simple. I just need to check that there is no undesired side effects when clicking the navigator back button.

Questions: 

- How persistent should be the filter values? Should they only work for one call ? or be persistent for a whole session ?

Remarks:

- I am not sure that it is a good idea to share filter criteria across different views. For exemple, if I select the rows of a view to be the ones related to a specific user, customer, etc. When I navigate to another view, should I keep the selection on the same user, customer, etc.? In somes cases it may be convenient for the user of the application, but it could also be complex and error prone for the user.

### Code generation

The code generator:

* generate a list of filter input fields
* generate the filtering code to select data
* Should filter input be validated. Beware of code injection.

## Filtering and data types

* **string** we may want to filter on strict equality, or the fact that the provided string is contained into the database.

The like mysql operator can be used with the % wildchar to find values containing the filter string. Note that by default equality is not case sensitive in MySQL

Strings requere the generation of one select input filed in the GUI.

* **Numerical data or dates** can be filter on equality or belonging ot a range when two limit are provided.
  
Numeric and dates require two select inputs.

* **Bitfields** should be selected when the bits of the selector are set in the database value.

When bitfields are aditive criteria like a person who have several roles it may be enough to select on set bits. I want to select people who are teacher and admin and redactor.

Note that in some cases we may like to use "and" operator or "or" operator or to select only people who do not have a bit set.

FUll support of that would implies to support masks and binary operators on bitfields graphicaly complex on implying knowledge on the internal representation. Supporting these use cases is low priority.

* **Checkboxes** can be filter by value, but require an additional checkbox to know if this criteria is part of the selection. If only a checkbox is given, there is no way to find out if an unset value means filter the items with this field equal to false or do not filter on this field. For others types like varchar or integer an empty value means no selection on this field.
  
* **Dates**. We may like to filter on the curent date, current month, current year, and everything between a start and end date. Depending on the application default may be different (no filtering, today, this year).

* **images or files** no support for filtering.

## Metadata

It is part of the metadata to determine if a field is selectable in filter and if it is a scalar or range filter.

A filter attribute will be used in metadata with the following values

	"filter": "no"		no selection on this field (default when filter is not defined)
	"filter": "equal"	filter on equal for numeric and contain for string
	"filter": "range"   define a range for numeric, equivalent to equal when only one limit is set
	
This mechanism can be extended, but should remain simple.



# Development steps

## REST API data filtering

Already supported at least infrastructure and basic filtering.

The filtering unit tests are not yet supported by the code generator. 

## GUI data filtering

1. Do a manual implementation for one of the controllers like CalendarEvents or CodeGenTypes.
2. Update the unit tests
3. Update code generation

