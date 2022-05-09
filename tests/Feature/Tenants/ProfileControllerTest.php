<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace Tests\Feature\Tenants;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenants\Profile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;

/**
 * Functional test for the Profile CRUD 
 * 
 * It is a functional test which tests
 *   - the routes
 *   - the profilesController class
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
|        | GET|HEAD  | profiles                     | profiles.index   | App\Http\Controllers\profilesController@index          | web          |
|        | POST      | profiles                     | profiles.store   | App\Http\Controllers\profilesController@store          | web          |
|        | GET|HEAD  | profiles/create              | profiles.create  | App\Http\Controllers\profilesController@create         | web          |
|        | PUT|PATCH | profiles/{task}              | profiles.update  | App\Http\Controllers\profilesController@update         | web          |
|        | GET|HEAD  | profiles/{task}              | profiles.show    | App\Http\Controllers\profilesController@show           | web          |
|        | DELETE    | profiles/{task}              | profiles.destroy | App\Http\Controllers\profilesController@destroy        | web          |
|        | GET|HEAD  | profiles/{task}/edit         | profiles.edit    | App\Http\Controllers\profilesController@edit           | web          |
+--------+-----------+-------------------------------+-------------------+---------------------------------------------------------+--------------+
 * 
 * @author frede
 *
 */

class ProfileControllerTest extends TenantTestCase {

    protected $tenancy = true;
    
	protected $basename = "profile";	
	
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
		return Profile::count(); 
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
        
        $look_for = [__('profile.title'), __('navbar.tenant'), tenant('id')];
        $look_for[] = __('profile.first_name'); 
        $look_for[] = __('profile.last_name'); 
        $look_for[] = __('profile.birthday'); 
        $look_for[] = __('profile.user_id'); 

        $this->get_tenant_url($this->user, 'profile', $look_for);
    }

    /**
     * Test creation form display
     * Given the user is logged on
     * When calling create URL
     * Then the create form is displayed
     */
    public function testCreateUrlDisplaysCreationForm() {
        Log::Debug(__METHOD__);
        $this->get_tenant_url($this->user, 'profile/create', [__('profile.new')]);   
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
        
        Profile::factory()->create();
        $latest = Profile::latest()->first();
        
        $id = $latest->id;
        
        $this->get_tenant_url($this->user, 'profile/' . $id);
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
        $profile = Profile::factory()->make();
        $elt = ['_token' => csrf_token()];
        $elt['first_name'] = $profile->first_name; 
        $elt['last_name'] = $profile->last_name; 
        $elt['birthday'] = $profile->birthday; 
        $elt['user_id'] = $profile->user_id; 

        $initial_count = Profile::count ();
        
        // call the post method to create it
        $this->post_tenant_url($this->user, 'profile', ['created'], $elt);
        
        $new_count = Profile::count ();
        $expected = $initial_count + 1;
        $this->assertEquals ( $expected, $new_count, "profile created, actual=$new_count, expected=$expected" );       
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
        foreach (Profile::factory()->error_cases() as $case) {
            $initial_count = Profile::count ();
                
            $elt = ['_token' => csrf_token()];
            $elt = array_merge($elt, $case["fields"]);
        
            $response = $this->post_tenant_url( $this->user, 'profile', [], $elt, $errors_expected = true);
            // $response->dumpSession();
        
            $response->assertSessionHasErrors($case["errors"]);
        
            $new_count = Profile::count ();
            $this->assertEquals ( $initial_count, $new_count, "error case $cnt: profile not created, actual=$new_count, expected=$initial_count" );
            $cnt = $cnt + 1;
        }
        $this->assertTrue($cnt == 1 + count(Profile::factory()->error_cases()));
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
        
        Profile::factory()->create();
        $latest = Profile::latest()->first();
        $id = $latest->id;
        
        $this->get_tenant_url($this->user, 'profile/' . $id . '/edit', [__('general.edit') . " " . __('profile.elt')]);
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
        
        $profile = Profile::factory()->make();                // create an element
        $profile2 = Profile::factory()->make();               // and a second one
        $elt = ['_token' => csrf_token()];
        $elt2 = ['_token' => csrf_token()];
        
        foreach ([ "first_name", "last_name", "birthday", "user_id" ] as $field) {
            $elt[$field] = $profile->$field;
            $elt2[$field] = $profile2->$field;
        }
        
        $profile->save();                            // save the first element
        $latest = Profile::latest()->first();
        $this->assertNotNull($latest);
        $id = $latest->id;
        $this->assertNotNull($id);
        
        $initial = Profile::where('id', $id)->first();     // get it back
        $this->assertNotNull($initial);
        
        // Check that the first saved element has the correct values and is different from the second one
        foreach ([ "first_name", "last_name", "birthday", "user_id" ] as $field) {
            if ($field != 'id') {
                $this->assertEquals($initial->$field, $elt[$field]);
                $this->assertNotEquals($initial->$field, $profile2->$field);
            }
        }
                
        // Update the values using the second element
        $elt2['id'] = $id;
        $this->patch_tenant_url($this->user, 'profile/' . $id, $elt2);
        
        $updated = Profile::where('id', $id)->first();
        $this->assertNotNull($updated);     
        foreach ([ "first_name", "last_name", "birthday", "user_id" ] as $field) {
            if ($field != 'id') {
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
        
        $initial_count = Profile::count ();

        Profile::factory()->create();
        $latest = Profile::latest()->first();
        $id = $latest->id;
        
        $new_count = Profile::count ();
        $expected = $initial_count + 1;
        $this->assertEquals ( $expected, $new_count, "one profile created, actual=$new_count, expected=$expected" );
        
        $this->delete_tenant_url($this->user, 'profile/' . $id, ['deleted']);
        
        $count_after_delete = Profile::count ();
        $expected = $initial_count;
        $this->assertEquals ( $expected, $count_after_delete, "profile deleted, actual=$count_after_delete, expected=$expected" );             
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
        $response = $this->get('/profile/undefined_url');
        $response->assertStatus(404);
    }

    /**
     * Test locale
     * 
     * The test should check that pages are displayed according
     * to locale. If the test relies on the translations from ressources/lang
     * there is no way to detect that some translation have been forgotten
     * either by not using @lang in the views of by missing translation entry.
     * 
     * TODO loop around views and keys
     *
     */
    public function testPagesAreDisplayedAccordingToLocaleLanguage() {
        Log::Debug(__METHOD__);
        
        /**
         * Scenario: Profile testLocale fr
         * Given the local is set to fr
         * When calling URLs
         * Then views are displayed in French
         */
        
        $this->be($this->user);
        
        $locale = App::getLocale();

    	App::setLocale('fr');
        $this->get_tenant_url($this->user, 'profile/create', [__('profile.new')]);      
        $fr_string = __('profile.new');
   	/**
    	 * Scenario: Profile testLocale en
    	 * Given the local is set to en
    	 * When calling URLs
    	 * Then views are displayed in English
    	 */
    	
    	App::setLocale('en');
        $this->get_tenant_url($this->user, 'profile/create', [__('profile.new')]);      
        $en_string = __('profile.new');
        $this->get_tenant_url($this->user, 'profile', [__('profile.title')]);      
        $en_string = __('profile.title');
       $this->assertNotEquals($fr_string, $en_string);
   	
    	App::setLocale($locale);
    	$new_locale = App::getLocale();
    	$this->assertTrue($new_locale == $locale, "Locale back to initial value");	
    }    
}
