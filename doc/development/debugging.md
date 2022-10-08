# Debugging

## Install the debugger

### Past phpinfo into the XDebug wizard

Cut the phpinfo page and paste it in the XDebug installation wizard

[XDebug installation wizard](https://xdebug.org/wizard)
    

### Instructions

Then follow the instructions.

    Download php_xdebug-3.1.3-8.0-vs16-x86_64.dll
    Move the downloaded file to C:\xampp\php\ext, and rename it to php_xdebug.dll
    Update C:\xampp\php\php.ini and add the line:
    zend_extension = xdebug
    Restart the Apache Webserver
    
    check phpinfo again to see that XDEBUG is enabled
    
## Configuration

    Add the following lines in php.init (and restart apache after each modification)
    
    [xdebug]
	xdebug.log=c:\logs\xdebug.log
	; enable/disable xdebug in Eclipse in commenting in or out the following line (and restarting Apache)
	xdebug.mode = debug
	xdebug.start_with_request = yes
	xdebug.client_port = 9003

	zend_extension = xdebug
   
## Code coverage

    run\coverage.bat
    
    Coverage slows down the execution by a factor of almost 2.
    
    file:///C:/Users/frederic/Dropbox/xampp/htdocs/multitenant/results/coverage/index.html
    
## Debugging a Unit test with Eclipse

- set a breakpoint in the code to debug (right mouse button)
- Debug as PHPUnit test (right mouse button)

in case of error

![PHP error](images/php_error.png?raw=true "PHP version error")

Select the correct PHP interpretor

![PHP version](images/php_version.png?raw=true "Eclipse PHP interpretors")

Put a breakpoint in the test and debug as PHPUnit test


![Debugging](images/debugging.png?raw=true "Debugging")

## Debugging an interactive browser session

tbd


