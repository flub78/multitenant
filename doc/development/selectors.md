# Selectors

It is a common situation to have to display or select an item from a table. The id may be inconvenient for a human being especially if it is just an integer. And we have seen that business information are usually not recommended as primary keys. They require the definition of a canonical form, upper and lower case and separators could make things complicated. Except for some kind of reference already know by the system which is modeled it is frequent to want to change them.

I have in mind a system in which call signs have been used to identify aircrafts in two separate system, but with no convention on casing or dashes. It has been complicated to match the information in the two systems. At least when this kind of information is not used as primary key, it is easier to change it.

So for each element, the model should provide:

* a full name, non ambiguous string human readable able to identify any item from the table.
* a short name, usable in context where the element is already know

* a list associating the full name with the corresponding id. This list will be user to build different kind of selectors

* Several kind of selectors for unique or multiple selection. Depending on the number or item to select, it may be easier to select them using a simple dropdown menu, a small set of checkboxes, of a menu which can be restricted by typing some characters of the selection.

Note that in case of big database, the selectors may have to be dynamic (ajax or other to not fetch all the keys from the table)

## Model

All models will provide the following methods:

* full_name : non ambiguous human readable string
* short_name : human readable string, potentially ambiguous

* name_ids 



