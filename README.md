# Multi-tenant Laravel framework

## Introduction

This project is a framework for Web applications. It provides several core features, common to a lot of web applications. Spefific business logic should be easy and fast to add.

## Basic features

* The project is multi-tenant with one database per tenant. By default tenants are identified by their sub-domain.

* Localization and interface in several languages

* The central application provide a tenant management interface. It is possible to create, modify or delete the tenants.

* Once a tenant is created it is immediately possible to access the tenant application from the sub-domain.

* User management and admin role. Both central application and tenant application can register, create, modify and delete users. The first user to register is automatically admin. Then this initial admin can delegate or suppress the admin role to other users. Users can be active and inactive and only active users can login.

* Backup and restore. There is a php artisan set of command to create, list, delete and restore the backups, both for the central database or for the tenant database. The feature is also available through a web interface and every tenant can backup or restore his/her own database without interfering with other tenants data.

* Use of datatable for flexible and convenient views for the database tables. (pagination, ordering, filtering, CSV and pdf export.

* REST API to access the database (at least for the tenant databases)

* Shared calendar

## Future features

* Email and sort text message notifications

* Documents and picture attachments.

* Numerical data display


## Development principles

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


