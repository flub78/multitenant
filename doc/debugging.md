# Debugging

## Install the debugger

### Past phpinfo into the XDebug wizard

    https://xdebug.org/wizard
    
Installation Wizard
Summary

    Xdebug installed: no
    Server API: Apache 2.0 Handler
    Windows: yes
    Compiler: MS VS16
    Architecture: x64
    Zend Server: no
    PHP Version: 8.0.13
    Zend API nr: 420200930
    PHP API nr: 20200930
    Debug Build: no
    Thread Safe Build: yes
    OPcache Loaded: no
    Configuration File Path: no value
    Configuration File: C:\xampp\php\php.ini
    Extensions directory: C:\xampp\php\ext

### Instructions

    Download php_xdebug-3.1.3-8.0-vs16-x86_64.dll
    Move the downloaded file to C:\xampp\php\ext, and rename it to php_xdebug.dll
    Update C:\xampp\php\php.ini and add the line:
    zend_extension = xdebug
    Restart the Apache Webserver
    
    check phpinfo again to see the XDEBUG section
    
## Configuration

    Add the following lines in php.init (and restart apache after each modification)
    
    zend_extension = xdebug
    [XDEBUG]
    xdebug.mode = debug
   
## Code coverage

    run\coverage.bat
    
    Coverage slows down the execution by a factor of almost 2.
    
    file:///C:/Users/frederic/Dropbox/xampp/htdocs/multitenant/results/coverage/index.html
    
## Debuging with Eclipse

