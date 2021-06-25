<?php

namespace App\Http\Controllers\Tenants;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Config;
use Illuminate\Support\Facades\App;

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
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
    	echo ("Tenant Test Controller\n");
    	echo ("Tenant=" . tenant('id') . " \n");
    	echo ("Local from Config:: =" . Config::config('app.locale'). " \n");
    	echo ("Local =" . App::getLocale() . " \n");
    	// return view('test');
    }
}
