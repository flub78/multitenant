<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace Tests\Feature\Tenants;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenants\CodeGenType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;


/**
 * Functional test for the CodeGenType CRUD 
 * 
 * It is a functional test which tests
 *   - the routes
 *   - the code_gen_typesController class
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
|        | GET|HEAD  | code_gen_types                     | code_gen_types.index   | App\Http\Controllers\code_gen_typesController@index          | web          |
|        | POST      | code_gen_types                     | code_gen_types.store   | App\Http\Controllers\code_gen_typesController@store          | web          |
|        | GET|HEAD  | code_gen_types/create              | code_gen_types.create  | App\Http\Controllers\code_gen_typesController@create         | web          |
|        | PUT|PATCH | code_gen_types/{task}              | code_gen_types.update  | App\Http\Controllers\code_gen_typesController@update         | web          |
|        | GET|HEAD  | code_gen_types/{task}              | code_gen_types.show    | App\Http\Controllers\code_gen_typesController@show           | web          |
|        | DELETE    | code_gen_types/{task}              | code_gen_types.destroy | App\Http\Controllers\code_gen_typesController@destroy        | web          |
|        | GET|HEAD  | code_gen_types/{task}/edit         | code_gen_types.edit    | App\Http\Controllers\code_gen_typesController@edit           | web          |
+--------+-----------+-------------------------------+-------------------+---------------------------------------------------------+--------------+
 * 
 * @author frede
 *
 */
class CodeGenTypeControllerTest extends TenantTestCase {

    protected $tenancy = true;
    
	protected $basename = "code_gen_type";	
	
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
		return CodeGenType::count(); 
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
        
        $look_for = [__('code_gen_type.title'), __('code_gen_type.add'), __('navbar.tenant'), tenant('id')];
        $look_for[] = __('code_gen_type.name'); 
        $look_for[] = __('code_gen_type.phone'); 
        $look_for[] = __('code_gen_type.description'); 
        $look_for[] = __('code_gen_type.year_of_birth'); 
        $look_for[] = __('code_gen_type.weight'); 
        $look_for[] = __('code_gen_type.birthday'); 
        $look_for[] = __('code_gen_type.tea_time'); 
        $look_for[] = __('code_gen_type.takeoff'); 
        $look_for[] = __('code_gen_type.price'); 
        $look_for[] = __('code_gen_type.big_price'); 
        $look_for[] = __('code_gen_type.qualifications'); 
        $look_for[] = __('code_gen_type.color_name'); 
        $look_for[] = __('code_gen_type.picture'); 
        $look_for[] = __('code_gen_type.attachment'); 

        $this->get_tenant_url($this->user, 'code_gen_type', $look_for);
    }

    /**
     * Test creation form display
     * Given the user is logged on
     * When calling create URL
     * Then the create form is displayed
     */
    public function testCreateUrlDisplaysCreationForm() {
        Log::Debug(__METHOD__);
        $this->get_tenant_url($this->user, 'code_gen_type/create', [__('code_gen_type.new')]);   
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
        
        CodeGenType::factory()->create();
        $latest = CodeGenType::latest()->first();
        
        $id = $latest->id;
        
        $this->get_tenant_url($this->user, 'code_gen_type/' . $id);
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
        $code_gen_type = CodeGenType::factory()->make();
        $elt = ['_token' => csrf_token()];
        $elt['name'] = $code_gen_type->name; 
        $elt['phone'] = $code_gen_type->phone; 
        $elt['description'] = $code_gen_type->description; 
        $elt['year_of_birth'] = $code_gen_type->year_of_birth; 
        $elt['weight'] = $code_gen_type->weight; 
        $elt['birthday'] = $code_gen_type->birthday; 
        $elt['tea_time'] = $code_gen_type->tea_time; 
        $elt['takeoff_date'] = $code_gen_type->takeoff_date; 
        $elt['takeoff_time'] = $code_gen_type->takeoff_time; 
        $elt['price'] = $code_gen_type->price; 
        $elt['big_price'] = $code_gen_type->big_price; 
        $elt['qualifications'] = $code_gen_type->qualifications; 
        $elt['picture'] = $code_gen_type->picture; 
        $elt['attachment'] = $code_gen_type->attachment; 
        
        $initial_count = CodeGenType::count ();
        
        // call the post method to create it
        $this->post_tenant_url($this->user, 'code_gen_type', ['created'], $elt);

        $new_count = CodeGenType::count ();
        $expected = $initial_count + 1;
        $this->assertEquals ( $expected, $new_count, "code_gen_type created, actual=$new_count, expected=$expected" );       
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
        foreach (CodeGenType::factory()->error_cases() as $case) {
            $initial_count = CodeGenType::count ();
                
            $elt = ['_token' => csrf_token()];
            $elt = array_merge($elt, $case["fields"]);
        
            $response = $this->post_tenant_url( $this->user, 'code_gen_type', [], $elt, $errors_expected = true);
            // $response->dumpSession();
        
            $response->assertSessionHasErrors($case["errors"]);
        
            $new_count = CodeGenType::count ();
            $this->assertEquals ( $initial_count, $new_count, "error case $cnt: code_gen_type not created, actual=$new_count, expected=$initial_count" );
            $cnt = $cnt + 1;
        }
        $this->assertTrue($cnt == 1 + count(CodeGenType::factory()->error_cases()));
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
        
        CodeGenType::factory()->create();
        $latest = CodeGenType::latest()->first();
        $id = $latest->id;
        
        $this->get_tenant_url($this->user, 'code_gen_type/' . $id . '/edit', [__('general.edit') . " " . __('code_gen_type.elt')]);
    }

    /**
     * Test an element update
     * Given the user is logged on
     * Given at least one element in the table
     * When sending a put request
     * Then the element is modified in the database
     * 
     * TODO: test also with files, pictures and bitfields
     * 
     * @return void
     */
    public function testPostRequestUpdatesElement() {   
        Log::Debug(__METHOD__);
        
        $code_gen_type = CodeGenType::factory()->make();                // create an element
        $code_gen_type2 = CodeGenType::factory()->make();               // and a second one
        $elt = ['_token' => csrf_token()];
        $elt2 = ['_token' => csrf_token()];
        
        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "takeoff_date", "takeoff_time", "price", "big_price", "qualifications", "picture", "attachment" ] as $field) {
            $elt[$field] = $code_gen_type->$field;
            $elt2[$field] = $code_gen_type2->$field;
        }
        
        $code_gen_type->save();                            // save the first element
        $latest = CodeGenType::latest()->first();
        $this->assertNotNull($latest);
        $id = $latest->id;
        $this->assertNotNull($id);

        $initial = CodeGenType::where('id', $id)->first();     // get it back
        $this->assertNotNull($initial);
        
        // Check that the first saved element has the correct values and is different from the second one
        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "takeoff_date", "takeoff_time", "price", "big_price", "qualifications", "picture", "attachment" ] as $field) {
            if ($field != 'id') {
                $this->assertEquals($initial->$field, $elt[$field]);
                $this->assertNotEquals($initial->$field, $code_gen_type2->$field);
            }
        }
                
        // Update the values using the second element
        $elt2['id'] = $id;
        $this->patch_tenant_url($this->user, 'code_gen_type/' . $id, $elt2);
        
        $updated = CodeGenType::where('id', $id)->first();
        $this->assertNotNull($updated);     
        foreach ([ "name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "takeoff_date", "takeoff_time", "price", "big_price", "qualifications", "picture", "attachment" ] as $field) {
        	if ($field != 'id' && $field != 'qualifications' && $field != 'picture') {
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
        
        $initial_count = CodeGenType::count ();

        CodeGenType::factory()->create();
        $latest = CodeGenType::latest()->first();
        $id = $latest->id;
        
        $new_count = CodeGenType::count ();
        $expected = $initial_count + 1;
        $this->assertEquals ( $expected, $new_count, "one code_gen_type created, actual=$new_count, expected=$expected" );
        
        $this->delete_tenant_url($this->user, 'code_gen_type/' . $id, ['deleted']);
        
        $count_after_delete = CodeGenType::count ();
        $expected = $initial_count;
        $this->assertEquals ( $expected, $count_after_delete, "code_gen_type deleted, actual=$count_after_delete, expected=$expected" );             
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
        $response = $this->get('/code_gen_type/undefined_url');
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
         * Scenario: CodeGenType testLocale fr
         * Given the local is set to fr
         * When calling URLs
         * Then views are displayed in French
         */
        
        $this->be($this->user);
        
        $locale = App::getLocale();

    	App::setLocale('fr');
    	$this->get_tenant_url($this->user, 'code_gen_type/create', [__('code_gen_type.new')]);    	
    	$fr_string = __('code_gen_type.new');
    	
    	/**
    	 * Scenario: CodeGenType testLocale en
    	 * Given the local is set to en
    	 * When calling URLs
    	 * Then views are displayed in English
    	 */
    	
    	App::setLocale('en');
    	$this->get_tenant_url($this->user, 'code_gen_type/create', [__('code_gen_type.new')]);    	
    	$en_string = __('code_gen_type.new');
    	$this->assertNotEquals($fr_string, $en_string);
    	
    	App::setLocale($locale);
    	$new_locale = App::getLocale();
    	$this->assertTrue($new_locale == $locale, "Locale back to initial value");	
    }
        
}
