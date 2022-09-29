# Filtering

It is convenient to be able to select (filter) only some elements in the resource table view.

The idea is not to support a sophisticated query language like SQL but to provide a simple mechanism to select data. If complex selection mechanisms are required, special controllers will be developed.

## Use cases

As a user I want to be able to select rows with a field equal to a given value.

As a user I want to be able to select on multiple fields.

The selection must be persistent at least for the session

The user must be able to reset the selection and see all the rows.

## REST API

The rest APIs support a filter parameter with the following syntax:

The filter is a comma separated list of select criteria

a filter criteria is a filed name, a column, an optional operator and a value

Ex:

	?filter=black_and_white:1
	
	?filter=birthday:>' . $mid_date

## Implementation notes

On the rest API, filter criteria are passed as HTML parameter.

On previous project the filter data were stored in the session and the controller was fetching them from there.

A controller can easily analyze the content of a form to determine what rows should be selected. It is a little more complicated to find out if the "display all" button has been clicked.

One possible implementation is to just clear the selection form on "display all" click. However by doing that the selection is not persistent if another page is visited. Is it an issue ?

## Initial implementation

I'd like to:
- have the WEB index method accepting the same filter parameter than the API REST controller (to be consistent).
- have a filter method analysing the filter form values and generating a parameter to the format above
- then this filter method should call the regular index method or redirect.

This approach is simple. I just need to check that there is no undesired side effects when clicking the navigator back button.

Questions: 

- How persistent should be the filter values? Should they only work for one call ? or be persistent for a whole session ?

Remarks:

- I am not sure that it is a good idea to share filter criteria across different views. For exemple, if I select the rows of a view to be the ones related to a specific user, cutomer, etc. When I navigate to another view, should I keep the selection on the same user, customer, etc.? In somes cases it may be convenient for the user of the application, but it could also be complex and surprising or error prone for the user.




## Types of filtering

* On string we may want to filter on strict equality, or the fact that the provided string is contained into the database.

The like mysql operator can be used with the % wildchar to find values containing the filter string. Note that by default equality is not case sensitive in MySQL

* Numerical data or dates can be filter on equality or belonging ot a range when two limit are provided.

* Bitfields should be selected when the bits of the selector are set in the database value.

* Checkboxes can be filter by value, but require an additional checkbox to know if this criteria is part of the selection. If only a checkbox is given, there is no way to find out if an unset value means filter the items with this field equal to false or do not filter on this field. For others types like varchar or integer an empty value means no selection on this field.

* There is no support for filtering for images or files.

## Metadata

It is part of the metadata to determine if a field is selectable in filter and if it is a scalar or range filter.

A filter attribute will be used in metadata with the following values

	"filter": "no"		no selection on this field (default when filter is not defined)
	"filter": "equal"	filter on equal for numeric and contain for string
	"filter": "range"   define a range for numeric, equivalent to equal when only one limit is set
	
This mechanism can be extended, but should remain simple.




