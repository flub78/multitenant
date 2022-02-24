<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;


/**
 * A controller for the home page visible once logged in
 * @author frederic
 *
 */
class HomeController extends Controller
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
    	if (tenant('id')) {
    		
    		$date = Carbon::now()->toDateTimeString();
    		$qrcode = QrCode::size(100)->generate("Multi " . tenant('id') . ", $date");
    		
    		return view('tenants.home', compact('qrcode'));
    	} else {
        	return view('home');
    	}
    }
}
