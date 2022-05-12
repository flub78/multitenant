# Requirements and Features discussion

## Features

### Support for emails.

There are several level of email support.

1. Capacity to use email to send notifications or password forgotten links.
1. Capacity to send predefined forms per email.
1. Capacity to send mails to different categories of users
1. Capacity to keep track of email activities and to retrieve previously sent email with their list of recipients

1. Capacity to receive email with ordering and research
1. Dynamic email edition.

Note that the capacity to receive or dynamically define a complete emai to send are equivalent to the implementation of a full email client. They are big and complex software and there is no reason to do anything better or even close to a client like Thuderbird or Micosoft Outlook. 

Note that it is easy to let the user choose a reply email address to receive the answer to the automatic emails into a classical client.

So in this context email support will be limited to notifications

## Technical requirements

This is not a full list of requirements, but a set of constraints to remember.

* Do not use business related information as primary key. For example it may be convenient to rename a user id or a registration id (to fix a spelling mistake, make them homogenous or make them compatible with another program).

At minimum renaming of these primary keys should be propagated in the whole databse.

* Use email addresses for authentication. And the user should be able to change it.

If the user has private data, they should be identified with the user id as foreign key. (not the email address).


