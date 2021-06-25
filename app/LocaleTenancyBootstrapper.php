<?php
declare(strict_types = 1);

namespace App;

// use Illuminate\Foundation\Application;
use Stancl\Tenancy\Contracts\TenancyBootstrapper;
use Stancl\Tenancy\Contracts\Tenant;
use Illuminate\Support\Facades\App;
use App\Helpers\Config;

class LocaleTenancyBootstrapper implements TenancyBootstrapper {
	protected $locale;

	public function __construct() {
		$this->locale = App::getLocale();
	}

	public function bootstrap(Tenant $tenant) {
		$locale = Config::config('app.locale');
		App::setLocale($locale);
	}

	public function revert() {
		App::setLocale($this->locale);
	}

}
