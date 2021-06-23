<?php

namespace App\Http\Controllers\Tenants;

use Illuminate\Http\Request;
use app\Http\Controllers\Controller;

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
    	echo ("Tenant=" . tenant('id'));
    	echo ("Local=" . config('app.locale'));
    	// return view('test');
    }
}
