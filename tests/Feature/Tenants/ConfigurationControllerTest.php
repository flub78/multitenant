<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerced to avoid overwritting.
 */

namespace Tests\Feature\Tenants;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenants\Configuration;
use Illuminate\Support\Facades\Log;

/**
 * Functional test for the Configuration CRUD 
 * 
 * It is a functional test which tests
 *   - the routes
 *   - the configurationsController class
 *   - the views
 *   - and the model
 *   
 * It is a test at the HTTP level, which means that if the page relies on javascript
 * element to be functional it will not work. Javascript libraries should
 * be used to make the user experience easier, it should not be used to make
 * the application works. Note that this assertion could be reconsidered because of the datatable
 * library
 * 
 * From Laravel documentation each test should only test one request to the controller or the behavior is impredictable.
 * 
+--------+-----------+-------------------------------+-------------------+---------------------------------------------------------+--------------+
| Domain | Method    | URI                           | Name              | Action                                                  | Middleware   |
+--------+-----------+-------------------------------+-------------------+---------------------------------------------------------+--------------+
|        | GET|HEAD  | configurations                     | configurations.index   | App\Http\Controllers\configurationsController@index          | web          |
|        | POST      | configurations                     | configurations.store   | App\Http\Controllers\configurationsController@store          | web          |
|        | GET|HEAD  | configurations/create              | configurations.create  | App\Http\Controllers\configurationsController@create         | web          |
|        | PUT|PATCH | configurations/{task}              | configurations.update  | App\Http\Controllers\configurationsController@update         | web          |
|        | GET|HEAD  | configurations/{task}              | configurations.show    | App\Http\Controllers\configurationsController@show           | web          |
|        | DELETE    | configurations/{task}              | configurations.destroy | App\Http\Controllers\configurationsController@destroy        | web          |
|        | GET|HEAD  | configurations/{task}/edit         | configurations.edit    | App\Http\Controllers\configurationsController@edit           | web          |
+--------+-----------+-------------------------------+-------------------+---------------------------------------------------------+--------------+
 * 
 * @author frede
 *
 */
class ConfigurationControllerTest extends TenantTestCase {

    protected $tenancy = true;
    
	protected $basename = "configurations";	
	
	function __construct() {
		parent::__construct();
		
		// required to be able to use the factory inside the constructor
		$this->createApplication();
				
		$this->user = User::factory()->make();
		$this->user->admin = true;
	}
	
	function __destruct() {
		$this->user->delete();
	}		

	
	/**
	 * 
	 * @param string $segments
	 * @return string
	 */
	protected function base_url($segments = "") {
		$url = "/" . $this->basename;
		if ($segments) {
			$url = join("/", [$url, $segments]);
		}
		return $url;
	}
	
	/**
	 * Return the number of elements in the table managed by the CRUD controller under test
	 * @return int
	 */
	protected function eltCount() {
		return Configuration::count(); 
	}
    
    /**
     * Test display of all elements
     * Given the user is logged on
     * When calling index URL
     * Then the table view is displayed
     * 
     * @return void
     */
    public function testBaseUrlDisplaysTableView() {   
        Log::Debug(__METHOD__);
        
        $look_for = [__('configuration.title'), __('configuration.add'), __('navbar.tenant'), tenant('id')];
        $look_for[] = __('configuration.key'); 
        $look_for[] = __('configuration.value'); 

        $this->get_tenant_url($this->user, 'configuration', $look_for);
    }

    /**
     * Test creation form display
     * Given the user is logged on
     * When calling create URL
     * Then the create form is displayed
     */
    public function testCreateUrlDisplaysCreationForm() {
        Log::Debug(__METHOD__);
        $this->get_tenant_url($this->user, 'configuration/create', [__('configuration.new')]);   
     }
    
    /**
     * Test display of one element
     * Given the user is logged on
     * Given at least one element in the table
     * When sending a get request
     * Then the element is displayed
     * 
     * @return void
     */
    public function testGetRequestShowsElement() {        
        Log::Debug(__METHOD__);
        
        Configuration::factory()->create();
        $latest = Configuration::latest()->first();
        
        $id = $latest->key;
        
        $this->get_tenant_url($this->user, 'configuration/' . $id);
    }
    
    /**
     * Test a post request
     * Given the user is logged on
     * When sending a post request
     * Then the element is stored in the database
     * 
     * @return void
     */
    public function testPostRequestStoresElement()
    {    	        
        Log::Debug(__METHOD__);
        
        // Post a creation request
        $configuration = Configuration::factory()->make();
        $elt = ['_token' => csrf_token()];
        $elt['key'] = $configuration->key; 
        $elt['value'] = $configuration->value; 

        $initial_count = Configuration::count ();
        
        // call the post method to create it
        $this->post_tenant_url($this->user, 'configuration', ['created'], $elt);
        
        $new_count = Configuration::count ();
        $expected = $initial_count + 1;
        $this->assertEquals ( $expected, $new_count, "configuration created, actual=$new_count, expected=$expected" );       
    }
    
    /**
     * Test an invalid post request
     * 
     * Given the user is logged on
     * When sending a post request with invalid fields
     * Then errors messages are generated
     * 
     * @return void
     */
    public function testInvalidPostGeneratesErrors() {
        Log::Debug(__METHOD__);

        $cnt = 1;
        foreach (Configuration::factory()->erroneous_cases() as $case) {
            $initial_count = Configuration::count ();
                
            $elt = ['_token' => csrf_token()];
            $elt = array_merge($elt, $case["fields"]);
        
            $response = $this->post_tenant_url( $this->user, 'configuration', [], $elt, $errors_expected = true);
            // $response->dumpSession();
        
            $response->assertSessionHasErrors($case["errors"]);
        
            $new_count = Configuration::count ();
            $this->assertEquals ( $initial_count, $new_count, "error case $cnt: configuration not created, actual=$new_count, expected=$initial_count" );
            $cnt = $cnt + 1;
        }
        $this->assertTrue($cnt == 1 + count(Configuration::factory()->erroneous_cases()));
    }
    

    /**
     * Check that the edit form is correctly displayed
     * Given the user is logged on
     * When sending a get request to edit
     * Then the modification form is displayed pre-filled with the element
     * 
     * @return void
     */
    public function testEditUrlDisplaysPopulatedEditForm() {        
        Log::Debug(__METHOD__);
        
        Configuration::factory()->create();
        $latest = Configuration::latest()->first();
        $id = $latest->key;
        
        $this->get_tenant_url($this->user, 'configuration/' . $id . '/edit', ['Edit configuration']);
    }

    /**
     * Test an element update
     * Given the user is logged on
     * Given at least one element in the table
     * When sending a put request
     * Then the element is modified in the database
     * 
     * @return void
     */
    public function testPostRequestUpdatesElement() {   
        Log::Debug(__METHOD__);
        
        $configuration = Configuration::factory()->make();                // create an element
        $configuration2 = Configuration::factory()->make();               // and a second one
        $elt = ['_token' => csrf_token()];
        $elt2 = ['_token' => csrf_token()];
        
        foreach ([ "key", "value" ] as $field) {
            //if ($field != 'key') {
                $elt[$field] = $configuration->$field;
                $elt2[$field] = $configuration2->$field;
            //}
        }
        
        $configuration->save();                            // save the first element
        $latest = Configuration::latest()->first();
        $this->assertNotNull($latest);
        $key = $latest->key;
        $this->assertNotNull($key);
        
        $initial = Configuration::where('key', $key)->first();     // get it back
        $this->assertNotNull($initial);
        
        // Check that the first saved element has the correct values and is different from the second one
        foreach ([ "key", "value" ] as $field) {
            if ($field != 'key') {
                $this->assertEquals($initial->$field, $elt[$field]);
                $this->assertNotEquals($initial->$field, $configuration2->$field);
            }
        }
                
        // Update the values using the second element
        $elt2['key'] = $key;
        // echo route('configuration.update', $key );
        $this->patch_tenant_url($this->user, 'configuration/' . $key, $elt2);
        
        $updated = Configuration::where('key', $key)->first();
        $this->assertNotNull($updated);
        // var_dump($updated);
        // echo "key = $key\n";
        foreach ([ "key", "value" ] as $field) {
            if ($field != 'key') {
                $this->assertEquals($updated->$field, $elt2[$field]);
            }
        }
        $updated->delete();
    }

    /**
     * Test delete element
     * Given the user is logged on
     * Given at least one element in the table
     * When sending a delete request
     * Then the element is removed from the database
     * @return void
     */
    public function testDeleteRequestsRemovesElement() {
        Log::Debug(__METHOD__);
        
        $initial_count = Configuration::count ();

        Configuration::factory()->create();
        $latest = Configuration::latest()->first();
        $id = $latest->key;
        
        $new_count = Configuration::count ();
        $expected = $initial_count + 1;
        $this->assertEquals ( $expected, $new_count, "one configuration created, actual=$new_count, expected=$expected" );
        
        $this->delete_tenant_url($this->user, 'configuration/' . $id, ['deleted']);
        
        $count_after_delete = Configuration::count ();
        $expected = $initial_count;
        $this->assertEquals ( $expected, $count_after_delete, "configuration deleted, actual=$count_after_delete, expected=$expected" );             
    }
    
   
    /**
     * Test not found URL
     * Given the user is logged on
     * When calling an unknow URL
     * Then an error is returned
     * @return void
     */
    public function testUrlNotFoundReturns404() {
        Log::Debug(__METHOD__);
        $response = $this->get('/configuration/undefined_url');
        $response->assertStatus(404);
    }
        
}
