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
			$res = '<A HREF="mailto:' . $email . '">' . $email . '</A>';
			return $res;
		});

		Blade::directive('button_create', function (String $arguments) {
			list ($base_url, $display) = explode(',', $arguments);
			// <a href="{{url('users')}}/create"><button type="submit" class="btn btn-primary" >@lang('users.add')</button></a>
			$res = '<a href="' . $base_url . '/create"><button type="submit" class="btn btn-primary" >' . $display . '</button></a>';
			return $res;
		});

		/*
		 * I cannot get the following code to work ... interpretation context issue
		Blade::directive('selector_with_null', function ($json) {
			$params = json_decode($json, true);
			// var_dump($params);exit;
			$res = "selector_with_null($json)";
			return $res;
		});
		*/
		
	}

}
