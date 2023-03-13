# Multitenant Ansible directory

This directory contains ansible scripts to deploy Multitenant in different contexts/

- deploy.yml wa used by jenkins jobs to complete the installation (deprecated)

- install.yml (work in progress) install of multitenant on an empty lamp server (Apache, PHP and MySQL are already installed and setup)

## Usage

    source target.sh
    ansible-playbook --inventory hosts --key-file $TF_VAR_PRIVATE_KEY install.yml

or

    ansible-playbook --inventory hosts --key-file $TF_VAR_PRIVATE_KEY install.yml --start-at-task="vhost : create virtual host"

## Playbooks

### install.yml

- clone the sources
- create the MySql database
- adapt the .env files
- create the execution directories both for standalone application and testing
- complete the Laravel installation, migrate the database
- Install a public directory as root for the Apache server
- Create an Apache virtual host
- Setup the DNS routes

### Manual cleanup

on the installation target

- destroy the database
- delete the public directory
- delete the installation tree

On AWS

- delete the DNS route