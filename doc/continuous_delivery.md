# Continuous delivery Pipeline

A continuous delivery pipeline is an automated process from commits to validation of releases suitable for deployment.

They should contain at least the following steps

* Development, test execution in the development environment
* Commit.
* Static analysis
* Unit and features tests (no failure)
* Installation
    - Smoke tests
    - End to End testing
* Upgrade 
    - End to End testing
    - performance tests
  

## Commit

Not all commits trigger the release pipeline. The release pipeline must be executed at least once a day when commits have happened. The could be started during the night and maybe once in the middle of the work day.

## Marking and artifact generation

When the release pipeline is started, the first thing is to mark the release candidate under git.

It can be done with a build number for example build_657.

Note that in this case installation will be done directly from git. So there is not really artifacts to build. 

## Static analysis

Then static analysis is triggered. I still need to decide if it makes sense to declare the release invalid if some quality threshold is not reached. It is usually rather arbitrary but it may be helpful to define a limit just in case the quality slowly decrease.

## Unit and features tests (no failure)

All test should pass, no exception.

## Installation - End to end testing

Then an installation is done from scratch, exactly the same way than an installation for a customer.

Installation should be fully automated, and test first the prerequisite. In some cases, for example, the databases could already exist. If possible limit the prerequisite to a shell access. Ansible is likely the tool of choice for installation.

* Both central and tenant database should be reinitialized.
* Central database should be refreshed and migrated
* The test tenant is created
* Central end to end test
* Tenant end to end test

## Upgrade - End to End testing

This environment is never reset. It emulates a customer who would upgrade and keep data from one version to the next.

* After migration end to end tests are run.
* There are some tests to check that data has persisted across the update
* It is typically the environment to use to handle big data set
* And to test performance

If all these steps have passed successfully, the release candidate is upgraded as an official release.
