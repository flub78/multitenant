# Dusk Chrome version issue

The dusk chrome driver must match the version of chrome installed on the machine. As Chrome is often updated a mismatch may occur.

## Fix it on windows

	Facebook\WebDriver\Exception\SessionNotCreatedException: session not created: Chrome version must be between 70 and 73
	(Driver info: chromedriver=2.45.615291 (ec3682e3c9061c10f26ea9e5cdcf3c53f3f74387),platform=Windows NT 10.0.19044 x86_64)
	
Check the chrome version, top right menu, help, About.

Version 105.0.5195.102 (Build officiel) (64 bits)

	php artisan dusk:chrome-driver 105
	
ChromeDriver binary successfully installed for version 105.0.5195.52.