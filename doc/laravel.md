# Laravel

Some hints on Laravel usage

## Laravel naming conventions

Models should be singular and “studly case” (i.e. BillingAccount) and table names should be plural and snake case (i.e. billing_accounts).

[Laravel best practices](https://github.com/alexeymezenin/laravel-best-practices#follow-laravel-naming-conventions)

## Attributes

Laravel automatically generates attributes from the database
table column names. However the accessors, getters and setters must be manually generated. 