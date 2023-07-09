# Windows Development Environment

This is a description of the installation and configuration of the development environment on a Windows workstation.

## Xamp

* rename or delete c:\xamp or x:\xampp_php8
* supend antivirus
* Download and Install XAMPP

### Setup the root directory

	In the XAMPP control panel
	xampp - Apache - Config - httpd.conf
	
	Change default editor
	to C:\Program Files\Notepad++\notepad++.exe
	in 
	Change DocumentRoot
	
	replace
	DocumentRoot "C:/xampp_php8/htdocs"
	<Directory "C:/xampp_php8/htdocs">
	by
	
	C:/xampp_php8/htdocs
	DocumentRoot "C:\Users\frede\Dropbox\xampp\htdocs"
	<Directory "C:\Users\frede\Dropbox\xampp\htdocs">
	
	restart Apache

### Edit the the hosts file

	c:\windows\system32\drivers\etc\hosts
	
	127.0.0.1	crud8.com

	127.0.0.1	tenants.com
	127.0.0.1	abbeville.tenants.com
	127.0.0.1	troyes.tenants.com
	127.0.0.1	test.tenants.com

### Define the Apache Virtual Servers

	C:\xampp\apache\conf\extra
	
in httpd-vhosts.conf

	in C:\xampp\apache\conf\original\extra
	
	Comment out the existing virtual hosts

	<VirtualHost *:80>
		ServerAdmin frederic.peignot@free.fr
		DocumentRoot "C:\Users\frede\Dropbox\xampp\htdocs\multitenant\public"
		ServerName tenants.com
		ServerAlias *.tenants.com
	</VirtualHost>

## PHP

	install GD which is required for unit tests
	
	In the xampp control
	
	Apache - Config - php.ini
	uncomment ;extension=gd
	restart Apache
	
## Eclipse

### Phpunit

#### Setup a PHP executable

#### Execution

* Select a Unit test
* run it as phpunit test

## Visual Studio Code

* install the editor
* Open the project directory
  
## Debuger

* toggle a breakpoint
* 

## Coverage and profiling

Install XDebug as described in debugging.md

[Debugging and Debuger Installation ](./debugging.md)

## Installation

[Application Installation ](../devops/installation.md)

