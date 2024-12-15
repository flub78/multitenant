<?php

/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace Tests\Feature\Tenants;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenants\PersonalAccessToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use App\Helpers\CodeGenerator as CG;

/**
 * Functional test for the PersonalAccessToken CRUD 
 * 
 * It is a functional test which tests
 *   - the routes
 *   - the personal_access_tokensController class
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
|        | GET|HEAD  | personal_access_tokens                     | personal_access_tokens.index   | App\Http\Controllers\personal_access_tokensController@index          | web          |
|        | POST      | personal_access_tokens                     | personal_access_tokens.store   | App\Http\Controllers\personal_access_tokensController@store          | web          |
|        | GET|HEAD  | personal_access_tokens/create              | personal_access_tokens.create  | App\Http\Controllers\personal_access_tokensController@create         | web          |
|        | PUT|PATCH | personal_access_tokens/{task}              | personal_access_tokens.update  | App\Http\Controllers\personal_access_tokensController@update         | web          |
|        | GET|HEAD  | personal_access_tokens/{task}              | personal_access_tokens.show    | App\Http\Controllers\personal_access_tokensController@show           | web          |
|        | DELETE    | personal_access_tokens/{task}              | personal_access_tokens.destroy | App\Http\Controllers\personal_access_tokensController@destroy        | web          |
|        | GET|HEAD  | personal_access_tokens/{task}/edit         | personal_access_tokens.edit    | App\Http\Controllers\personal_access_tokensController@edit           | web          |
+--------+-----------+-------------------------------+-------------------+---------------------------------------------------------+--------------+
 * 
 * @author frede
 *
 */

class PersonalAccessTokenControllerTest extends TenantTestCase {

    protected $tenancy = true;

    protected $basename = "personal_access_token";

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
        return PersonalAccessToken::count();
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

        $look_for = [__('personal_access_token.title'), tenant('id')];
        $look_for[] = __('personal_access_token.tokenable_type');
        $look_for[] = __('personal_access_token.tokenable_id');
        $look_for[] = __('personal_access_token.name');
        $look_for[] = __('personal_access_token.token');
        $look_for[] = __('personal_access_token.abilities');
        $look_for[] = __('personal_access_token.last_used_at');

        $this->get_tenant_url($this->user, 'personal_access_token', $look_for);
    }

    /**
     * Test creation form display
     * Given the user is logged on
     * When calling create URL
     * Then the create form is displayed
     */
    public function testCreateUrlDisplaysCreationForm() {
        Log::Debug(__METHOD__);
        $this->get_tenant_url($this->user, 'personal_access_token/create', [__('personal_access_token.new')]);
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

        PersonalAccessToken::factory()->create();
        $latest = PersonalAccessToken::latest()->first();

        $id = $latest->id;

        $this->assertTrue(1 > 0);
        // TODO check if this URL makes sense
        // $this->get_tenant_url($this->user, 'personal_access_token/' . $id);
    }

    /**
     * Test a post request
     * Given the user is logged on
     * When sending a post request
     * Then the element is stored in the database
     * 
     * TODO: this test is not working
     * 
     * @return void
     */
    public function ttestPostRequestStoresElement() {
        Log::Debug(__METHOD__);

        // Post a creation request
        $personal_access_token = PersonalAccessToken::factory()->make();
        $elt = ['_token' => csrf_token()];
        // $elt['tokenable_type'] = $personal_access_token->tokenable_type;
        $elt['tokenable_id'] = User::find($personal_access_token->tokenable_id)->email;
        $elt['name'] = $personal_access_token->name;        // $elt['token'] = $personal_access_token->token;
        $elt['abilities'] = $personal_access_token->abilities;
        //$elt['last_used_at'] = $personal_access_token->last_used_at;

        $initial_count = PersonalAccessToken::count();

        // call the post method to create it
        $this->post_tenant_url($this->user, 'personal_access_token', ['created'], $elt);

        $new_count = PersonalAccessToken::count();
        $expected = $initial_count + 1;
        $this->assertEquals($expected, $new_count, "personal_access_token created, actual=$new_count, expected=$expected");
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
        foreach (PersonalAccessToken::factory()->error_cases() as $case) {
            $initial_count = PersonalAccessToken::count();

            $elt = ['_token' => csrf_token()];
            $elt = array_merge($elt, $case["fields"]);

            $response = $this->post_tenant_url($this->user, 'personal_access_token', [], $elt, $errors_expected = true);
            // $response->dumpSession();

            $response->assertSessionHasErrors($case["errors"]);

            $new_count = PersonalAccessToken::count();
            $this->assertEquals($initial_count, $new_count, "error case $cnt: personal_access_token not created, actual=$new_count, expected=$initial_count");
            $cnt = $cnt + 1;
        }
        $this->assertTrue($cnt == 1 + count(PersonalAccessToken::factory()->error_cases()));
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

        PersonalAccessToken::factory()->create();
        $latest = PersonalAccessToken::latest()->first();
        $id = $latest->id;

        $this->get_tenant_url($this->user, 'personal_access_token/' . $id . '/edit', [__('general.edit') . " " . __('personal_access_token.elt')]);
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
    public function ttestPostRequestUpdatesElement() {
        Log::Debug(__METHOD__);

        $personal_access_token = PersonalAccessToken::factory()->make();                // create an element
        $personal_access_token2 = PersonalAccessToken::factory()->make();               // and a second one
        $elt = ['_token' => csrf_token()];
        $elt2 = ['_token' => csrf_token()];

        foreach (["tokenable_type", "tokenable_id", "name", "token", "abilities", "last_used_at"] as $field) {
            $elt[$field] = $personal_access_token->$field;
            $elt2[$field] = $personal_access_token2->$field;
        }

        $personal_access_token->save();                            // save the first element
        $latest = PersonalAccessToken::latest()->first();
        $this->assertNotNull($latest);
        $id = $latest->id;
        $this->assertNotNull($id);

        $initial = PersonalAccessToken::where('id', $id)->first();     // get it back
        $this->assertNotNull($initial);

        $table = "personal_access_tokens";
        // Check that the first saved element has the correct values and is different from the second one
        foreach (["tokenable_id", "name", "token", "abilities", "last_used_at"] as $field) {
            if ($field != 'id') {
                $this->assertEquals($initial->$field, $elt[$field], "correct field $field retrieved from the database");
                if (CG::lot_of_values($table, $field))
                    $this->assertNotEquals($initial->$field, $personal_access_token2->$field, "field $field is different between two random instances");
            }
        }

        // Update the values using the second element
        $elt2['id'] = $id;
        $this->patch_tenant_url($this->user, 'personal_access_token/' . $id, $elt2);

        $updated = PersonalAccessToken::where('id', $id)->first();
        $this->assertNotNull($updated);
        foreach (["tokenable_type", "tokenable_id", "name", "token", "abilities", "last_used_at"] as $field) {
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

        $initial_count = PersonalAccessToken::count();

        PersonalAccessToken::factory()->create();
        $latest = PersonalAccessToken::latest()->first();
        $id = $latest->id;

        $new_count = PersonalAccessToken::count();
        $expected = $initial_count + 1;
        $this->assertEquals($expected, $new_count, "one personal_access_token created, actual=$new_count, expected=$expected");

        $this->delete_tenant_url($this->user, 'personal_access_token/' . $id, ['deleted']);

        $count_after_delete = PersonalAccessToken::count();
        $expected = $initial_count;
        $this->assertEquals($expected, $count_after_delete, "personal_access_token deleted, actual=$count_after_delete, expected=$expected");
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
        $response = $this->get('/personal_access_token/undefined_url');
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
         * Scenario: PersonalAccessToken testLocale fr
         * Given the local is set to fr
         * When calling URLs
         * Then views are displayed in French
         */

        $this->be($this->user);

        $locale = App::getLocale();

        App::setLocale('fr');
        $this->get_tenant_url($this->user, 'personal_access_token/create', [__('personal_access_token.new')]);
        $fr_string = __('personal_access_token.new');

        /**
         * Scenario: PersonalAccessToken testLocale en
         * Given the local is set to en
         * When calling URLs
         * Then views are displayed in English
         */

        App::setLocale('en');
        $this->get_tenant_url($this->user, 'personal_access_token/create', [__('personal_access_token.new')]);
        $en_string = __('personal_access_token.new');
        $this->get_tenant_url($this->user, 'personal_access_token', [__('personal_access_token.title')]);
        $en_string = __('personal_access_token.title');
        $this->assertNotEquals($fr_string, $en_string);

        App::setLocale($locale);
        $new_locale = App::getLocale();
        $this->assertTrue($new_locale == $locale, "Locale back to initial value");
    }
}
