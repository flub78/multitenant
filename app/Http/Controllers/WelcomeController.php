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

        $port = request()->getPort();
        $protocol = request()->getScheme(); // Returns 'http' or 'https'

        $tenants_data = Tenant::all();

        $tenants = [];

        foreach ($tenants_data as $tenant) {
            $tenants[] = [
                "id" => $tenant->id,
                'href' => $protocol . '://' . $tenant->domain . ':' . $port
            ];
        }

        return view('welcome', compact('tenants'));
    }
}
