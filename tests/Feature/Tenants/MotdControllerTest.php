<?php

/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace Tests\Feature\Tenants;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenants\Motd;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use App\Helpers\CodeGenerator as CG;

/**
 * Functional test for the Motd CRUD 
 * 
 * It is a functional test which tests
 *   - the routes
 *   - the motdsController class
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
|        | GET|HEAD  | motds                     | motds.index   | App\Http\Controllers\motdsController@index          | web          |
|        | POST      | motds                     | motds.store   | App\Http\Controllers\motdsController@store          | web          |
|        | GET|HEAD  | motds/create              | motds.create  | App\Http\Controllers\motdsController@create         | web          |
|        | PUT|PATCH | motds/{task}              | motds.update  | App\Http\Controllers\motdsController@update         | web          |
|        | GET|HEAD  | motds/{task}              | motds.show    | App\Http\Controllers\motdsController@show           | web          |
|        | DELETE    | motds/{task}              | motds.destroy | App\Http\Controllers\motdsController@destroy        | web          |
|        | GET|HEAD  | motds/{task}/edit         | motds.edit    | App\Http\Controllers\motdsController@edit           | web          |
+--------+-----------+-------------------------------+-------------------+---------------------------------------------------------+--------------+
 * 
 * @author frede
 *
 */

class MotdControllerTest extends TenantTestCase {

    protected $tenancy = true;

    protected $basename = "motd";

    function __construct(?string $name = null) {
        parent::__construct($name);

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
        return Motd::count();
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

        $look_for = [__('motd.title'), tenant('id')];
        $look_for[] = __('motd.title');
        $look_for[] = __('motd.message');
        $look_for[] = __('motd.publication_date');
        $look_for[] = __('motd.end_date');

        $this->get_tenant_url($this->user, 'motd', $look_for);
    }

    /**
     * Test creation form display
     * Given the user is logged on
     * When calling create URL
     * Then the create form is displayed
     */
    public function testCreateUrlDisplaysCreationForm() {
        Log::Debug(__METHOD__);
        $this->get_tenant_url($this->user, 'motd/create', [__('motd.new')]);
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

        Motd::factory()->create();
        $latest = Motd::latest()->first();

        $id = $latest->id;

        $this->get_tenant_url($this->user, 'motd/' . $id);
    }

    /**
     * Test a post request
     * Given the user is logged on
     * When sending a post request
     * Then the element is stored in the database
     * 
     * @return void
     */
    public function testPostRequestStoresElement() {
        Log::Debug(__METHOD__);

        // Post a creation request
        $motd = Motd::factory()->make();
        $elt = ['_token' => csrf_token()];
        $elt['title'] = $motd->title;
        $elt['message'] = $motd->message;
        $elt['publication_date'] = $motd->publication_date;
        $elt['end_date'] = $motd->end_date;

        $initial_count = Motd::count();

        // call the post method to create it
        $this->post_tenant_url($this->user, 'motd', ['created'], $elt);

        $new_count = Motd::count();
        $expected = $initial_count + 1;
        $this->assertEquals($expected, $new_count, "motd created, actual=$new_count, expected=$expected");
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
        foreach (Motd::factory()->error_cases() as $case) {
            $initial_count = Motd::count();

            $elt = ['_token' => csrf_token()];
            $elt = array_merge($elt, $case["fields"]);

            $response = $this->post_tenant_url($this->user, 'motd', [], $elt, $errors_expected = true);
            // $response->dumpSession();

            $response->assertSessionHasErrors($case["errors"]);

            $new_count = Motd::count();
            $this->assertEquals($initial_count, $new_count, "error case $cnt: motd not created, actual=$new_count, expected=$initial_count");
            $cnt = $cnt + 1;
        }
        $this->assertTrue($cnt == 1 + count(Motd::factory()->error_cases()));
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

        Motd::factory()->create();
        $latest = Motd::latest()->first();
        $id = $latest->id;

        $this->get_tenant_url($this->user, 'motd/' . $id . '/edit', [__('general.edit') . " " . __('motd.elt')]);
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

        $motd = Motd::factory()->make();                // create an element
        $motd2 = Motd::factory()->make();               // and a second one
        $elt = ['_token' => csrf_token()];
        $elt2 = ['_token' => csrf_token()];

        foreach (["title", "message", "publication_date", "end_date"] as $field) {
            $elt[$field] = $motd->$field;
            $elt2[$field] = $motd2->$field;
        }

        $motd->save();                            // save the first element
        $latest = Motd::latest()->first();
        $this->assertNotNull($latest);
        $id = $latest->id;
        $this->assertNotNull($id);

        $initial = Motd::where('id', $id)->first();     // get it back
        $this->assertNotNull($initial);

        $table = "motds";
        // Check that the first saved element has the correct values and is different from the second one
        foreach (["title", "message", "publication_date", "end_date"] as $field) {
            if ($field != 'id') {
                $this->assertEquals($initial->$field, $elt[$field], "correct field $field retreived from the database");
                if (CG::lot_of_values($table, $field))
                    $this->assertNotEquals($initial->$field, $motd2->$field, "field $field is different between two random instances");
            }
        }

        // Update the values using the second element
        $elt2['id'] = $id;
        $this->patch_tenant_url($this->user, 'motd/' . $id, $elt2);

        $updated = Motd::where('id', $id)->first();
        $this->assertNotNull($updated);
        foreach (["title", "message", "publication_date", "end_date"] as $field) {
            if ($field != 'id' && CG::testable($table, $field)) {
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

        $initial_count = Motd::count();

        Motd::factory()->create();
        $latest = Motd::latest()->first();
        $id = $latest->id;

        $new_count = Motd::count();
        $expected = $initial_count + 1;
        $this->assertEquals($expected, $new_count, "one motd created, actual=$new_count, expected=$expected");

        $this->delete_tenant_url($this->user, 'motd/' . $id, ['deleted']);

        $count_after_delete = Motd::count();
        $expected = $initial_count;
        $this->assertEquals($expected, $count_after_delete, "motd deleted, actual=$count_after_delete, expected=$expected");
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
        $response = $this->get('/motd/undefined_url');
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
         * Scenario: Motd testLocale fr
         * Given the local is set to fr
         * When calling URLs
         * Then views are displayed in French
         */

        $this->be($this->user);

        $locale = App::getLocale();

        App::setLocale('fr');
        $this->get_tenant_url($this->user, 'motd/create', [__('motd.new')]);
        $fr_string = __('motd.new');

        /**
         * Scenario: Motd testLocale en
         * Given the local is set to en
         * When calling URLs
         * Then views are displayed in English
         */

        App::setLocale('en');
        $this->get_tenant_url($this->user, 'motd/create', [__('motd.new')]);
        $en_string = __('motd.new');
        $this->get_tenant_url($this->user, 'motd', [__('motd.title')]);
        $en_string = __('motd.title');
        $this->assertNotEquals($fr_string, $en_string);

        App::setLocale($locale);
        $new_locale = App::getLocale();
        $this->assertTrue($new_locale == $locale, "Locale back to initial value");
    }
}
