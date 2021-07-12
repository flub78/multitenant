rm tests/Browser/screenshots/*.png
/usr/bin/php8.0 vendor/bin/phpunit --testdox-html doc/testdox.html --log-junit results/results.xml
