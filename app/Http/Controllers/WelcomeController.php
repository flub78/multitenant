<?php

namespace App\Http\Controllers;

use App\Http\Requests\TenantRequest;

use Stancl\Tenancy\Database\Models\Domain;
use App\Models\Tenant;
use App\Helpers\DirHelper;
use App\Helpers\TenantHelper;
use Illuminate\Validation\Rule;


class WelcomeController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $tenants = Tenant::all();

        return view('welcome', compact('tenants'));
    }
}
