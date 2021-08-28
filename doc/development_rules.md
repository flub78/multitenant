# Mandatory Development Rules

Here are the development rules that I consider mandatory for a succesful project in 2021


1. CI/CD pipeline first
1. TDD

## CI/CD pipeline first

The continuous integration pipeline must be put in place before anything else. Even with an "Hello world" application. Then the pipeline will be maintained and will evolve along development.

Each team member must be able to trigger the CI/CD pipeline on his/her own code, preferably before checkin.

This rules implies several subrules:

* the project must be managed under a source management system (git)
* Unit tests, feature tests and end to end tests must be developed. 

## TDD

The project must be TDD (Test driven development). Tests must be developed before the code to run it.

Fixing a failing test has a highest priority than anything else. Disabling a failing test is not considered as a fix. When a failing test happens especially in the CI/CD pipeline, fixing it becomes the highest priority task. To some extend if may be considered acceptable to revert the commit  which has triggered the failure, but certainly not to disable the associated test.

No story can be considered done without an acceptable level of automated tests. High percentage of unit test coverage. Good feature test coverage (the feature must be tested in its more current nominal use cases and also against incorrect usage.

Efficiency of the test strategy:

Testing is often a combinatorial issue. When you have a significant number of parameters or input data in any non trivial piece of software testing all the combination is not possible.

It is recommended to test each parameter at least of its nominal values, plus limits and out of bound values. The global set of the test suite must insure that globally each parameter has been tested for these significant values.

About test redundancy, be careful to not test the same thing at several levels.

 

## Frequent refactoring

The technical debt is the difference between the current state of the code and the state in which it should be. "It should be" does not define an ideal state in an ideal world, but the state in which the developers would rewrite the code knowing what they have learned during the initial development. A significant level of resources should be affected to refactoring to reduce the technical debt. It is difficult to fix a level, but maybe that 10% is a good start. Of course the 10 % are only sufficient if the first two rules are enforced and everything is correctly checked by automated tests. 10 % is far than enough as percentage of resources allocated to testing.

For testing, if we consider that every line of code should be covered, that every feature should be tested. That we need to have End to End tests, I would not be surprise to see that more of 50% of the global time should be allocated to test. It may looks huge but it is the only way to keep the code deliverable at any time and with good quality.

## Code and design reviews

Code should be reviewed frequently.

* Either during before feature branch merges (but branches are considered harmful in CD contexts)
* Through pair programming
* Through special sessions in which the goal is to determine if the code has a sufficient level of quality for delivery (even self review is possible but it should be avoided).


# Nice to have practices
 

