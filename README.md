# Multi-tenant Laravel framework

## Introduction

This project is a framework for Web applications. It provides a starting point for projects with a lot of common features and DevOps settings. Specific business logic should be fast and easy to add (to the extend that software development can be fast and easy). At least it should be faster and easier. 

## Basic existing features

* It is a resource management system on top of a relational database with REST APIs for machines and WEB interfaces for humans. It is a CRUD scaffolding system with support for complex relationship between tables (one to one, one to many and many to many). 

* It is written in PHP, integrated with Laravel and MySql or MariaDb. It can be adapted to other frameworks, databases or languages. In 2022 these initial technical choices make sense.

* The project is multi-tenant with one database per tenant. By default tenants are identified by their sub-domain. It can be adapted for a shared multi-tenant database as the underlying system supports it. To backup and restore database per tenant this model is the only suitable one.

* Localization and GUI in several languages. Automated generation of language files with Google translate. It requires human reviewing, but sometimes it is not so bad and it is fast to generate.

* The central application provides a tenant management interface. It is possible to create, modify or delete tenants. Once a tenant is created, the tenant application can be accessed from the sub-domain.

* User management and admin role. Both central application and tenant applications can register, create, modify and delete users. The first user to register is automatically admin. Then this initial admin can delegate or suppress the admin role to other users. Users can be active or inactive and only active users can login.

* Backup and restore. There is a php artisan set of command to create, list, delete and restore backups, both for the central database or for the tenant databases. The feature is also available through a web interface and every tenant can backup or restore his/her own database without interfering with other tenants data.

* Use of datatable for flexible and convenient views for the database tables. (pagination, ordering, filtering, CSV and pdf export.

* REST API to access the tenant databases.

* Shared calendar

* Code generation - Models, views, controllers, requests, validation rules and tests can be automatically generated. The code generator scans the database schema and uses additional metadata stored in database fields comments to feed a template engine. The template engine is mustache. It is possible to generate the code, to compare it with or replace the current code.

## Future features

* Email and short text message notifications

* Documents and picture attachments.

* Numerical data display

* Message of the day

* Data filtering

* Online shopping, payment interface


## Development principles

### Development priorities

1. Bug fixing
2. Test
3. Refactoring
4. New features

These are the priorities to keep a high level of quality. This project is a hobby and I am curious to see these rules applied strictly. In professional environment it is difficult to follow them due to the pressure of the business.
Their strict application is an experiment and I will observe the impact on velocity.

Fix of known bugs is the highest priority activity. Even the fix of minor bugs. Bugs are the demonstration that something wrong happened in the development process and we want to get it fixed as soon as possible. Of course in implies fix of the build jobs.

Tests are part of the development process. Every line of code, every feature should have their own tests developed before the code. 

Refactoring, keeping the technical debt minimal is considered a higher priority than developing new features. It is less important than completing the tests. 

New feature, they should only be developed on a working, well tested and minimal base of code. This ideal situation should make development of new features fast and easy and so compensate for the testing and refactoring efforts. 

### Documentation

Short design notes are kept in the doc directory as a set of md files. Plantuml is used to support the specification and design documentation. Most of the technical notes are under the doc directory as md files. 

#### Development documentation

- [Admin middleware](doc/development/admin-middleware.md)
- [APIs](doc/development/apis.md)
- [Blade templates](doc/development/blade_templates.md)
- [Checklist for code reviews](doc/development/checklist_code_review.md)
- [Checklist for metadata update](doc/development/checklist_metadata_update.md)
- [Code generation](doc/development/code_generation.md)
- [Code generation data types](doc/development/code_generation_data_types.md)
- [Code generation progress](doc/development/code_generation_progress.md)
- [Current status](doc/development/current_status.md)
- [Database relationships](doc/development/database_relationships.md)
- [Dates](doc/development/dates.md)
- [Debugging](doc/development/debugging.md)
- [Deployment](doc/development/deployment.md)
- [Derived applications](doc/development/derived_applications.md)
- [Design notes](doc/development/design_notes.md)
- [Development](doc/development/development.md)
- [Development introspection](doc/development/development_introspection.md)
- [Development rules](doc/development/development_rules.md)
- [Dusk Chrome version issue](doc/development/dusk_chrome_version_issue.md)
- [Eclipse](doc/development/eclipse.md)
- [Fields validation](doc/development/Fields_validation.md)
- [Filtering](doc/development/filtering.md)
- [Graphical user interfaces](doc/development/gui.md)
- [Laravel](doc/development/laravel.md)
- [Logging](doc/development/logging.md)
- [Migrations](doc/development/migrations.md)
- [Models](doc/development/models.md)
- [MySQL queries](doc/development/mysql_queries.md)
- [MySQL views](doc/development/mysql_views.md)
- [Naming conventions](doc/development/naming_conventions.md)
- [PlantUML](doc/development/plantuml.md)
- [Routes](doc/development/routes.txt)
- [Selectors](doc/development/selectors.md)
- [Testing with Postman](doc/development/testing_with_postman.md)
- [Translation](doc/development/translation.md)
- [Windows development environment](doc/development/windows_development_environment.md)
 

#### Devops documentation

- [Continuous delivery](doc/devops/continuous_delivery.md)
- [Installation](doc/devops/installation.md)
- [Jenkins](doc/devops/jenkins.md)

#### Features documentation

- [Attachments](doc/features/attachements.md)
- [Backup](doc/features/backup.md)
- [Calendar](doc/features/calendar.md)
- [Chat](doc/features/chat.md)
- [Configuration](doc/features/configuration.md)
- [Localization](doc/features/localization.md)
- [Message of the day](doc/features/motd.md)
- [Multi tenancy](doc/features/multi-tenancy.md)
- [Reservations](doc/features/reservations.md)


#### Testing documentation

- [Test data generation](doc/testing/test_data_generation.md)
- [Running tests](doc/testing/running_tests.md)
- [Testing](doc/testing/testing.md)

### TDD

The project is test driven with several level of tests. The goal is to provide a good test coverage for the the core features, so it should be easy to develop the business logic with the same spirit and add tests along the development.


### Continuous integration

A jenkins server is in charge of the static analysis of the code and provide quality indicators.


### Continuous deployment

Not yet, but it is on the agenda.

## Sources, references and inspiration

[Laravel 8 CRUD tutorial](https://appdividend.com/2020/10/13/laravel-8-crud-tutorial-example-step-by-step-from-scratch/)
    
[Markdown cheat sheet](https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet)


