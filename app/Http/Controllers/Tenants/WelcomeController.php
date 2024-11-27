<?php

namespace App\Http\Controllers\Tenants;

use App\Http\Controllers\Controller;

class WelcomeController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $port = request()->getPort();
        $protocol = request()->getScheme(); // Returns 'http' or 'https'
        return view('welcome', compact('protocol', 'port'));
    }
}
