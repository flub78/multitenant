# Message of the day

This features shows short information messages when a user login.

## Use cases

As a user.

* I want to read the latest information with most recent first. If there are several of them I want to scroll.
* I want to be able to close the window
* I want to be able to say that I not interested any more and do not see the messages until a new one is published
* I want to be able to change my mind and see the messages again

As an admin

* I want to be able to add new messages and delete older ones.
* I want to be able to specify during which period a message will be displayed with a start and end date 

## Design

A single table with

    - id
    - title
    - message
    - publication date
    - end date
    
The user choice about new presentation will be store into a cookie with the date of the latest cookie that the user do not want.

## May be later

It could be interesting to include some kind of formating, HTML or Markdown

    https://laracasts.com/discuss/channels/laravel/best-way-to-render-markdown-in-views

# Design and implementation

## Steps

* Define the motds table and generate the associated code. Globally it is more or less the admin view.
* Define a controller and a view to see the more recent messages

* On this page there is a button proposing to not show the messages any more. A Cookie will be use to store this information.

Let's start basic but this feature is a good opportunity to learn how to create a component that can be displayed on many pages.