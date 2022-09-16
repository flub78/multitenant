# Windows Development Environment

## Wamp

* rename or delete c:\xamp
* supend antivirus
* Download and Install XAMPP

### Setup the root directory

	xampp - Apache - Config - httpd.conf
	
	Change DocumentRoot
	
	DocumentRoot "C:\Users\frederic\...\xampp\htdocs"
	<Directory "C:\Users\frederic\...\xampp\htdocs">
	
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
	DocumentRoot C:\Users\frederic\Dropbox\xampp\htdocs\multitenant\public
	ServerName tenants.com
	ServerAlias *.tenants.com
	</VirtualHost>


## Eclipse


### Phpunit

## Debuger

## Coverage and profiling