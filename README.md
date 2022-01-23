# Multi-tenant Laravel framework

## Introduction

This project is a framework for Web applications. It provides several core features, common to a lot of web applications. Specific business logic should be easy and fast to add.

## Basic features

* The project is multi-tenant with one database per tenant. By default tenants are identified by their sub-domain.

* Localization and GUI in several languages

* The central application provide a tenant management interface. It is possible to create, modify or delete tenants.

* Once a tenant is created it is immediately possible to access its application from the sub-domain.

* User management and admin role. Both central application and tenant application can register, create, modify and delete users. The first user to register is automatically admin. Then this initial admin can delegate or suppress the admin role to other users. Users can be active and inactive and only active users can login.

* Backup and restore. There is a php artisan set of command to create, list, delete and restore the backups, both for the central database or for the tenant database. The feature is also available through a web interface and every tenant can backup or restore his/her own database without interfering with other tenants data.

* Use of datatable for flexible and convenient views for the database tables. (pagination, ordering, filtering, CSV and pdf export.

* REST API to access the database (at least for the tenant databases)

* Shared calendar

* Code generation - Models, views, controllers, requests, validation rules and tests can be automatically generated. The code generator scans the database schema and uses additional metadata stored in database fields comments to feed a template engine. The template engine is mustache. It is possible to generate the code, to compare it with or replace the current code.

## Future features

* Email and sort text message notifications

* Documents and picture attachments.

* Numerical data display


## Development principles

### Development priorities

1. Bug fixing
2. Test
3. Refactoring
4. New features

Without this list being an absolute rules and in order to keep a high level of quality these are the general priorities of development activities. Note that this project is a hobby and I am curious to see the effect of these rules applied quite strictly. In professional environment, a lot of people agree with them until the pressure of the business convinces them to do something else.
I consider their strict application as an experiment. Is the highest overall velocity achieved with a more strict application of these rules ? Or is the highest velocity achieved by applying them most of the time but with flexibility. I honestly do not know the answer.

Fix of known bugs is the highest priority activity. Even the fix of minor bugs. Bugs are the demonstration that something wrong happened in the development process and we want to get it fixed as soon as possible. Of course in implies fix of the build jobs.

Tests are part of the development process. Every line of code, every feature should have their own tests developed before they are written or modified. If for some reason something is checked with missing tests, the next highest priority situation is to fix it.

Refactoring, keeping the technical debt as minimal is considered a higher priority than developing new features. However it is considered less important than completing the testing because the code is already working. 

New feature, they should only be developed on a working, well tested and minimal base of code. This ideal situation should make development of new features fast and easy and so compensate for the testing and refactoring efforts. 

### Documentation

Short design notes are kept in the doc directory as a set of md files. Plantuml is used to support the specification and design documentation.

### TDD

The project is test driven with several level of tests. The goal is to provide a good test coverage for the the core features, so it should be easy to develop the business logic with the same spirit and add tests along the development.


### Continuous integration

A jenkins server is in charge of the static analysis of the code and provide quality indicators.


### Continuous deployment

Not yet, but it is on the agenda.

## Sources, references and inspiration

[Laravel 8 CRUD tutorial](https://appdividend.com/2020/10/13/laravel-8-crud-tutorial-example-step-by-step-from-scratch/)
    
[Markdown cheat sheet](https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet)


