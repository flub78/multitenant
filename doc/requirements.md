# Requirements

This is not a full list of requirements, but a set of constraints to remember.

* Do not use business related information as primary key. For example it may be convenient to rename a user id or a registration id (to fix a spelling mistake, make them homogenous or make them compatible with another program).

At minimum renaming of these primary keys should be propagated in the whole databse.

* Use email addresses for authentication. And the user should be able to change it.

If the user has private data, they should be identified with the user id as foreign key. (not the email address).
