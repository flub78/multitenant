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
        
        $look_for = [__('code_gen_type.title'), __('navbar.tenant'), tenant('id')];
        $look_for[] = __('code_gen_type.name'); 
        $look_for[] = __('code_gen_type.phone'); 

        $this->get_tenant_url($this->user, 'code_gen_type', $look_for);
        
        $this->assertEquals(0, $this->eltCount());
        
        CodeGenType::factory()->create(['name' => 'dupont', 'price' => '10']); 
        CodeGenType::factory()->create(['name' => 'durand', 'price' => '10']);
        CodeGenType::factory()->create(['name' => 'dugenou', 'price' => '20']);
        CodeGenType::factory()->create(['name' => 'john', 'price' => '20']);
        CodeGenType::factory()->create(['name' => 'james', 'price' => '30']);
        $count = 5;
        
        $this->assertEquals($count, $this->eltCount());
        // it is not possible to look for strings like 'Showing 1 to 2 of 2 entries'
        // as the are generating by javascript which changes the DOM

        // here I rely on the number of row (<tr>) in the page which may be difficult to evaluate
        // for complex pages
        
        $url = 'code_gen_type';
        $res = $this->get_tenant_url($this->user, $url, $look_for);
        $start = 'id="maintable"';
        $stop = '</table>';
        $row_marker = '<tr>';
        $this->assertOccurencesInString($row_marker, $res->getContent(), $count + 1, $start, $stop);

        // filter on one name
        $url = 'code_gen_type?filter=name:dupont';
        $res = $this->get_tenant_url($this->user, $url, $look_for);
        $start = 'id="maintable"';
        $stop = '</table>';
        $row_marker = '<tr>';
        $this->assertOccurencesInString($row_marker, $res->getContent(), 2, $start, $stop);
        
    }


        
}
