# No Dusk test run with this script
# rm tests/Browser/screenshots/*.png
# /usr/bin/php8.0 vendor/bin/phpunit --testdox-html doc/testdox.html --log-junit results/results.xml
export XDEBUG_MODE=coverage
/usr/bin/php8.0 vendor/bin/phpunit -d xdebug.mode=coverage -d zend_extension=/usr/lib/php/20200930/xdebug.so --log-junit results/results.xml --testdox-html doc/testdox.html --coverage-html results/coverage
