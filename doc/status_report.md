# Status report for the project

## 09/08/2024

## Development environment

- [x] github.com/flub78/multitenant
- [X] Development environment

    http://tenants.com/
    http://abbeville.tenants.com/

## Features

### Central

- [x] User management / authentication
- [x] Tenant management
- [x] Backups and restores
- [x] CSV and PDF reports
- [x] Filter
- [x] Pagination

### Tenant

- [x] Registration
- [x] User management / authentication
- [x] Backups and restores
- [x] CSV and PDF reports
- [x] roles
- [x] configuration
- [x] Calendar
- [x] Localization
- [ ] personal_access_token WIP
  *  route not defined...

## Tests

### Local

- [x] Unit tests
  
  Tests: 424, Assertions: 1963, Errors: 20, Failures: 3.

  

### Jenkins / CI
    
    https://jenkins2.flub78.net:8443/

- [x] Static analysis
- [ ] phpunit 


## 10/08/2024

* unit tests: OK (403 tests, 1988 assertions)

* code coverage: Warning:       No code coverage driver available

* dusk tests: 

  * Localization: 7, Assertions: 13, Errors: 1, Failures: 6.
  * Central
  * Tenants