<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;

class TestTenantsList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test-tenants:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List the tenants on the test database connection';
    
    /**
     * The connection resolver instance.
     *
     * @var \Illuminate\Database\ConnectionResolverInterface
     */
    protected $resolver;
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
    	echo "Listing all tenants on test connection\n";

    	// https://fideloper.com/laravel-multiple-database-connections
     	$TenantModel = new Tenant();
    	$TenantModel->setConnection('mysql_test');			// does not work
    	
    	$tenants = $TenantModel->all();
    	foreach ($tenants as $tenant) {
    		if ($tenant->domains) {
    			$this->line("[Tenant] id: {$tenant['id']} @ " . implode('; ', $tenant->domains->pluck('domain')->toArray() ?? []));
    		} else {
    			$this->line("[Tenant] id: {$tenant['id']}");
    		}
    	}
    	
    	return 0;    	
    }
}
