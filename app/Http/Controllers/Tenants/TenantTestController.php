<?php

namespace App\Http\Controllers\Tenants;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Config;
use App\Helpers\HtmlHelper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

/**
 * Just a controller to trigger test code used during development. It should be disabled before deployment.
 * 
 * (maybe I should create a developer middleware)
 * 
 * @author frederic
 *
 */
class TenantTestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        
        // Forbiden access except during testing
        if (config("app.env") != "testing" ) {
        	// echo "Access only for testing";
        	// exit;
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
    	setlocale(LC_ALL, 'fr_FR');
    	
    	$vars = ['locale' => Config::config('app.locale'), 
    	    'url' => URL::to('/')];
    	$vars['app_locale'] = App::getLocale();
    	$vars['route'] = route('calendar_event.index');
    	$vars['central_db'] = env ( 'DB_DATABASE' );
    	
    	return view('test/test', $vars);
    	
    }
    
    /**
     * Show a test checklist
     *
     */
    public function checklist()
    {        
        $vars = ['locale' => Config::config('app.locale'),
            'url' => URL::to('/')];
        return view('test/checklist', $vars);
        
    }
    
}
