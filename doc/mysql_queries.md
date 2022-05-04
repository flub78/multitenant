# MySql Queries

This files contains some SQL queries used for experimentaion during development.

## Simple select

    SELECT * FROM `code_gen_types` WHERE 1

## Select with selection of the columns
    
    SELECT `name`,`description`,`tea_time` FROM `code_gen_types` WHERE 1

## Simple join between two tables
    
    select name, email, role_id from users, user_roles where user_id=users.id

## Simple join between three tables
    
    select users.name, users.email, roles.name from users, user_roles, roles where user_id=users.id and role_id=roles.id
    
## Simple select on a registered view

    SELECT * FROM `code_gen_types_view1` WHERE 1
    
## Create a view

    CREATE VIEW user_roles_view1 AS
    select users.name AS user_name, users.email as user_email, roles.name as role_name from users, user_roles, roles where user_id=users.id and role_id=roles.id
    
The stored definition is:

    select `tenantabbeville`.`users`.`name` AS `user_name`,`tenantabbeville`.`users`.`email` AS `user_email`,`tenantabbeville`.`roles`.`name` AS `role_name` from `tenantabbeville`.`users` join `tenantabbeville`.`user_roles` join `tenantabbeville`.`roles` where `tenantabbeville`.`user_roles`.`user_id` = `tenantabbeville`.`users`.`id` and `tenantabbeville`.`user_roles`.`role_id` = `tenantabbeville`.`roles`.`id`
    
And it can be retrieved with the following request:
    
    SELECT  VIEW_DEFINITION 
    FROM    INFORMATION_SCHEMA.VIEWS
    WHERE   TABLE_SCHEMA    = 'tenantabbeville' 
    AND     TABLE_NAME      = 'user_roles_view1';
       
Which returns an empty result when a table is not a view.

## Information on constraints

    SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS;
    
    SELECT CONSTRAINT_SCHEMA, CONSTRAINT_NAME, UPDATE_RULE, DELETE_RULE, TABLE_NAME, REFERENCED_TABLE_NAME FROM         information_schema.REFERENTIAL_CONSTRAINTS;

I do not know why but the following request does not work ...

    SELECT CONSTRAINT_SCHEMA, CONSTRAINT_NAME, UPDATE_RULE, DELETE_RULE, TABLE_NAME, REFERENCED_TABLE_NAME FROM         information_schema.REFERENTIAL_CONSTRAINTS WHERE TABLE_NAME = 'profiles'
    
neither this one

    SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS 
    where information_schema.REFERENTIAL_CONSTRAINTS.CONSTRAINT_NAME = 'profiles_user_id_foreign';
       