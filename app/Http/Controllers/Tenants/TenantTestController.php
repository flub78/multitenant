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
        	echo "Access only for testing";
        	//exit;
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
    	$locale = setlocale(LC_ALL, 'fr_FR');
    	
    	$msg = HtmlHelper::h1("Tenant Test Controller") . "\n";
    	$msg .= "Tenant=" . tenant('id') . " \n";
    	$msg .= "Local from Config:: =" . Config::config('app.locale'). " \n";
    	$msg .= "Local =" . App::getLocale() . " \n";
    	$route = route('calendar_event.index');
    	$msg .= "route('calendar_event.index') = $route";
    	$msg .= " url=" . URL::to('/');
    	$msg .= ", locale=$locale";
    	echo $msg;
    	// return $msg;
    	// return view('test');
    }
}
