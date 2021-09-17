<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;


class AppServiceProvider extends ServiceProvider {

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		Blade::directive('datetime', function ($expression) {
			return "<?php echo ($expression)->format('m/d/Y H:i'); ?>";
		});

		Blade::directive('email', function (String $email) {
			// <A HREF="mailto:{{$user->email}}">{{$user->email}}</A>
			$res = '<A HREF="mailto:' . $email . '">'
					. $email . '</A>';
			return $res;
		});
	}

}
