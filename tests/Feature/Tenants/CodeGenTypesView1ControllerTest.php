<?php

/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace Tests\Feature\Tenants;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenants\CodeGenTypesView1;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;

/**
 * Functional test for the CodeGenTypesView1 MySQL view
 * 
 * Most of the time, views are read only. This test is a subset of the one used for regular table.
 * It only checks the index method.
 * 
+--------+-----------+-------------------------------+-------------------+---------------------------------------------------------+--------------+
| Domain | Method    | URI                           | Name              | Action                                                  | Middleware   |
+--------+-----------+-------------------------------+-------------------+---------------------------------------------------------+--------------+
|        | GET|HEAD  | code_gen_types_view1                     | code_gen_types_view1.index   | App\Http\Controllers\code_gen_types_view1Controller@index          | web          |
+--------+-----------+-------------------------------+-------------------+---------------------------------------------------------+--------------+
 * 
 * @author frede
 *
 */
class CodeGenTypesView1ControllerTest extends TenantTestCase {

    protected $tenancy = true;

    protected $basename = "code_gen_types_view1";

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
        return CodeGenTypesView1::count();
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

        $look_for = [__('code_gen_types_view1.title'), tenant('id')];
        $look_for[] = __('code_gen_type.name');
        $look_for[] = __('code_gen_type.description');
        $look_for[] = __('code_gen_type.tea_time');

        $this->get_tenant_url($this->user, 'code_gen_types_view1', $look_for);
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
        $response = $this->get('/code_gen_types_view1/undefined_url');
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
         * Scenario: CodeGenTypesView1 testLocale fr
         * Given the local is set to fr
         * When calling URLs
         * Then views are displayed in French
         */

        $this->be($this->user);

        $locale = App::getLocale();

        App::setLocale('fr');
        $this->get_tenant_url($this->user, 'code_gen_types_view1', [__('code_gen_types_view1.title')]);
        $fr_string = __('code_gen_types_view1.title');

        /**
         * Scenario: CodeGenTypesView1 testLocale en
         * Given the local is set to en
         * When calling URLs
         * Then views are displayed in English
         */

        App::setLocale('en');
        $this->get_tenant_url($this->user, 'code_gen_types_view1', [__('code_gen_types_view1.title')]);
        $en_string = __('code_gen_types_view1.title');
        $this->assertNotEquals($fr_string, $en_string);

        App::setLocale($locale);
        $new_locale = App::getLocale();
        $this->assertTrue($new_locale == $locale, "Locale back to initial value");
    }
}
