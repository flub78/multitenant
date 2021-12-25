# Tenant Deployment End to End Production Tests

These tests are black box tests for running tenant deployment. These tests require a domain name on which the application is deployed and the name and password of an admin user. The tests clean after themselves and avoid to create conflicting data. It is prudent however to only run them on pre-production and not on production.

The should run whatever the pre-existing data and the load of the system.