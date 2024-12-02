<?php

namespace Tests\Feature\Tenants;

use Tests\TenantTestCase;
use App\Models\User;
use App\Models\Tenants\CodeGenType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use App\Helpers\CodeGenerator as CG;


/**
 * Filter test for the CodeGenType CRUD 
 * 
 * This test focus on the filter feature, a way to select elements in the index view. 
 * @author frede
 *
 */
class CodeGenTypeControllerFilterTest extends TenantTestCase {

    protected $tenancy = true;

    protected $basename = "code_gen_type";

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
        return CodeGenType::count();
    }


    protected function checkFilter($url, $expected, $look_for) {
        $res = $this->get_tenant_url($this->user, $url, $look_for);
        $start = 'id="maintable"';
        $stop = '</table>';
        $row_marker = '<tr>';
        $this->assertOccurencesInString($row_marker, $res->getContent(), $expected, $start, $stop);
    }

    /**
     * Test display selected elements
     * Given the user is logged on
     * When calling index URL
     * Then the table view is displayed
     * 
     * @return void
     */
    public function testFiltering() {
        Log::Debug(__METHOD__);

        $look_for = [__('code_gen_type.title'), tenant('id')];
        $look_for[] = __('code_gen_type.name');
        $look_for[] = __('code_gen_type.phone');

        $this->get_tenant_url($this->user, 'code_gen_type', $look_for);

        $this->assertEquals(0, $this->eltCount());

        CodeGenType::factory()->create(['name' => 'dupont', 'price' => '10', 'birthday' => '1959-08-29', 'takeoff' => '2022-07-14 12:00']);
        CodeGenType::factory()->create(['name' => 'durand', 'price' => '10', 'birthday' => '1959-08-28', 'takeoff' => '2022-07-14 12:30']);
        CodeGenType::factory()->create(['name' => 'dugenou', 'price' => '20', 'birthday' => '1959-08-27', 'takeoff' => '2022-07-14 13:00']);
        CodeGenType::factory()->create(['name' => 'john', 'price' => '20', 'birthday' => '1959-07-26', 'takeoff' => '2022-07-14 13:30']);
        CodeGenType::factory()->create(['name' => 'james', 'price' => '30', 'birthday' => '1959-07-25', 'takeoff' => '2022-07-14 14:00']);
        $count = 5;

        $this->assertEquals($count, $this->eltCount());
        // it is not possible to look for strings like 'Showing 1 to 2 of 2 entries'
        // as the are generating by javascript which changes the DOM

        // here I rely on the number of row (<tr>) in the page which may be difficult to evaluate
        // for complex pages

        $this->checkFilter('code_gen_type', $count + 1, $look_for);

        $this->checkFilter('code_gen_type?filter=name:dupont', 2, $look_for);

        $this->checkFilter('code_gen_type?filter=birthday:1959-08-29', 2, $look_for);

        $this->checkFilter('code_gen_type?filter=birthday:<1959-08-29', 5, $look_for);

        $this->checkFilter('code_gen_type?filter=birthday:<1959-08-29,birthday:>1959-07-27', 3, $look_for);

        $this->checkFilter('code_gen_type?filter=takeoff:2022-07-14 13:00', 2, $look_for);

        $this->checkFilter('code_gen_type?filter=takeoff:>2022-07-14 12:00,takeoff:<2022-07-14 13:30', 3, $look_for);
    }

    public function testFilterOnIncorrectParameters() {
        Log::Debug(__METHOD__);

        $look_for = [__('code_gen_type.title'), __('navbar.tenant'), tenant('id')];
        $look_for[] = __('code_gen_type.name');
        $look_for[] = __('code_gen_type.phone');

        $url = 'code_gen_type?filter=takeoff:>2022-07-14 12:00,takeoff:<2022-07-14 13:30,unknown:yes';
        $this->be($this->user);
        $url = $this->tenant_url($url);
        $response = $this->get($url);
        $response->assertStatus(500);
    }
}
