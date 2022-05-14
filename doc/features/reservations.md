# Reservations

Reservation is an extension of the calendar events.

This feature can have a lot of applications, reservations of rental place, rental car, classroom registration, etc.

A reservation can involve several resources of several categories. Each resource has availability or unavailability periods. For example a student can register for a course if there is a classroom and an instructor available. A reservation may have constraints, for example at most 10 students can register to a course.

And several creation policy are possible:

## Strict order

* a teacher creates the session
* she reserves a classroom
* then student can register

## Loose order

* Whoever student or teacher creates a lesson
* then somebody allocates a classroom
* and an instructor registers
* the session is valid only if all the constraints are met 