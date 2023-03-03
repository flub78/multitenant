# Test diagnostic

The test environment start to become complicated and the diagnostic in case of failure is not really easy. It can be interresting to keep track of some diagnostic session. The information can be useful if the issue happens again. The ultimate goal would be to define a process to find the issues but it there is likely too many cases so I can describes them all. But even if this ultimate goal is not achievable it will help to have some cases documented.

To create this documentation the easiest way is to describe the resolution of some real cases, and then to identify commonalities to create the skeleton of a debuging process. So I'll describe some error cases

## Error cases description

### Case 1: Multitenant_phpunit crash on pastille

- 2023-02-28
- pastille Linuxmint fanless CI jenkins server

```sh
......................../test.sh : ligne 5 : 11175 Processus arrêté
/usr/bin/php8.0 vendor/bin/phpunit -d xdebug.mode=coverage -d zend_extension=/usr/lib/php/20200930/xdebug.so --log-junit results/results.xml --testdox-html doc/testdox.html --coverage-html results/coverage
Build step 'Exécuter un script shell' marked build as failure 
```


### Case 2

## Debuging process

### Step 1 collect information

- date
- environment
- failure description

### Step 2 Check the resources on the test machine

	df
	Sys. de fichiers          blocs de 1K  Utilisé Disponible Uti% Monté sur
	udev                          1822816        0    1822816   0% /dev
	tmpfs                          370892     1776     369116   1% /run
	/dev/mapper/mint--vg-root   490215384 36679796  428564324   8% /
	tmpfs                         1854456        0    1854456   0% /dev/shm
	tmpfs                            5120        4       5116   1% /run/lock
	tmpfs                         1854456        0    1854456   0% /sys/fs/cgroup
	
	free -h
              					total       utilisé      libre     partagé tamp/cache   disponible
	Mem:			           3,5G        1,8G        417M        4,2M        1,4G        1,5G
	Partition d'échange:        975M        728M        247M
	
### Step 3 attempt to reproduce in simpler environment

#### In case of jenkins job

- ssh as jenkins of the jenkins server
- try to restart the job from the command line

	sudo -i -u jenkins
	cd workspace/Multitenant_phpunit
	export BASE_URL='http://tenants.com/'
	export APP_URL='http://tenants.com/'
	export TENANT_URL='http://test.tenants.com/'
	export INSTALLATION_PATH='/var/www/html/multi_phpunit'
	export SERVER_PATH='/var/www/html/tenants.com'
	export VERBOSE="-v"
	export TRANSLATE_API_KEY='AIzaSyCqE...'

	export DB_HOST='localhost'
	export DB_USERNAME='root'
	export DB_PASSWORD='...'
	export DB_DATABASE='multi_jenkins'
	
	./test.sh
	
During the test execution there a a few errors ..........EE.EE..EEE.EEE under a reasonable workload or 0.7

/var/lib/jenkins/workspace/Multitenant_phpunit/tests/Feature/Tenants/TestControllerTest.php:39

ERRORS!
Tests: 112, Assertions: 256, Errors: 49.

That's a very small number of test I was expecting around 400 ... confirmed by a second execution ...


# In case of error

#### Multitenant run\tests.bat SQLSTATE[HY000] [2002] Aucune connexion n’a pu être établie

Start Apache and MySQL.

#### This version of ChromeDriver only supports Chrome version 105

php artisan dusk:chrome-driver