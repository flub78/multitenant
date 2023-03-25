# Continuous integration

The test infrastructure is deployed according to the "infrastructure as code" principles. It is deployed on AWS and is composed of a jenkins server and a jenkins agent to run the jobs. 
    
    https://github.com/flub78/aws_cicd

The jenkins pipeline is triggered on code changes.

## Job execution prerequisites:

The job is executed on a machine with:

* Apache
* PHP at the correct version
* The PHP static analysis tools

Once the workspace fetched, an ansible playbook is run to configure the agent node to run the phpunit and dusk tests.
The playbook is deploy_from_jenkins.yml

## Server

    https://flub78.ddns.net:8443/
    http://jenkins.flub78.net:8080

    
## Pipeline stages

* Static analysis
* phpunit
* Central end to end dusk tests
* Tenant end to end dusk tests


## Deployment tests

### Installation from scratch

* provision a fresh EC2 instance
* Automated deployment
* Execution of the End to End tests

### Upgrade and Migrations tests

* Use specific machine which is upgraded from version to version
* Run the End to End tests
* Check that the existing data are preserved and stil available after upgrade
