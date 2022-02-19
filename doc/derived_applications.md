# Derived applications

The goal of this project is to provide a starting point to quickly derive different applications. The main common application should only contain features and services useful to most of these applications.

One way of deriving is just to clone the github project and start the new application from there. However when bugs are fixed in the common part or when new common features are developed it would be better to have the common part updated.

It is still possible to report the fixes or new features. However it would imply duplication of efforts, discipline and risks that the modifications are not compatible with all applications.

## Project structure

* The common application will be managed in its own github project
* Every derived application will be managed in its own github project

* To build a derived application
    * first the files from the common application will be deployed
    * Then the files from the derived application will be copied in the same directory structure
    
It can be implemented with the execution environment having symbolic links or hard links to the two github projects. It can be implemented with a script copying the files from the edition directory to the execution directory.

It can be implemented with all the files from common put in the git ignore file of the derived application. This method is likely convenient if the common application is stable and most of the work is done in derived...

Note that due to the high level of nesting between common and derived, gitub sub-projects are likely not convenient.

Maybe that merging developments done in derived and cherry picking is the best approach ???




