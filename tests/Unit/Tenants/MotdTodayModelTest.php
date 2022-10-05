<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace tests\Unit\Tenants;

use Tests\TenantTestCase;
use App\Models\Tenants\MotdToday;
use App\Helpers\CodeGenerator as CG;
// Here use clause for the model referenced by the viex

/**
 * Unit test for MotdToday model
 
 * @author frederic
 *
 */
class MotdTodayModelTest extends TenantTestCase {
        
    /*
     *
     */
     public function test_factory() {
        $initial_count = MotdToday::count();
        
        MotdToday::factoryCreate();        
        $new_count = MotdToday::count();
        $this->assertEquals($initial_count + 1, $new_count);
        
        MotdToday::factoryCreate();
        $final_count = MotdToday::count();
        $this->assertEquals($initial_count + 2, $final_count);
     }
      
    /*
     * Test of the view model is a little different from a regular table as
     *    - the test is only read only
     *    - and it require the generation of elements in the tables referenced by the view
     */     
     public function test_view() {
        $this->assertTrue(true);
        
        // Here generation of the referenced elements
         MotdToday::factoryCreate(); 
         MotdToday::factoryCreate(); 
         
        $this->assertTrue(MotdToday::count() > 0);
        
        $all = MotdToday::all();
        
        // Here the verification that sthe returned list is correct
     } 
}
