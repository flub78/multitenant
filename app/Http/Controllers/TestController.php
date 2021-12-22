<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Mail\Invitation;

class TestController extends Controller
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
    	return view('test', ['locale' => Config::config('app.locale'), 'url' => URL::to('/')]);
    }
    
    public function info() {
    	phpinfo();
    }
    
    public function email () {
    
    	$name = 'Fred';
    	Mail::to('frederic.peignot@free.fr')->send(new Invitation($name));
    	
    	return 'Email was sent';
    }
    
}
