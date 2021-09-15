<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Concerns\MaintenanceMode;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;
    use MaintenanceMode;
    
    protected $fillable = [
    		'id',
    		'email',
    		'db_name',
    		'domain'
    ];
      
    public function url() {
    	$parsed = parse_url(url('/'));
    	
    	$url = $parsed['scheme'] . '://' . $this->domain;
    	
    	return $url;
    }    
}
