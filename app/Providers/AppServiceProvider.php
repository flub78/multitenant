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

		
		Blade::directive('label', function (String $arguments) {
			// <label for="user_id">{{__('user_roles.user_id')}}</label>
			list ($id, $label) = explode(',', $arguments);
			return '<label for=' . $id . '>' . $label . '</label>';
		});

		
		Blade::directive('button_create', function (String $arguments) {
			// <a href="{{url('users')}}/create"><button type="submit" class="btn btn-primary" >@lang('users.add')</button></a>
			list ($base_url, $display) = explode(',', $arguments);
			return '<a href="' . $base_url . '/create"><button type="submit" class="btn btn-primary" >' . $display . '</button></a>';
		});


		Blade::directive('button_submit', function (String $label = "") {
			if (!$label)
				$label = __('general.submit');
			return '<button type="submit" class="btn btn-primary">' . $label . '</button>';
		});

	}

}
