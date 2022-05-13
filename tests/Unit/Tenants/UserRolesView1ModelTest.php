<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace tests\Unit\Tenants;

use Tests\TenantTestCase;
use App\Models\Tenants\UserRolesView1;
// Here use clause for the model referenced by the viex

/**
 * Unit test for UserRolesView1 model
 
 * @author frederic
 *
 */
class UserRolesView1ModelTest extends TenantTestCase {
        
    /*
     *
     */
     public function test_factory() {
     	$this->assertTrue(true);
        $initial_count = UserRolesView1::count();
        
        UserRolesView1::factoryCreate();        return;
        $new_count = UserRolesView1::count();
        $this->assertEquals($initial_count + 1, $new_count);
        
        UserRolesView1::factoryCreate();
        $final_count = UserRolesView1::count();
        $this->assertEquals($initial_count + 2, $final_count);
     }
      
    /*
     * Test of the view model is a little different from a regular table as
     *    - the test is only read only
     *    - and it require the generation of elements in the tables referenced by the view
     */     
     public function ttest_view() {
        $this->assertTrue(true);
        
        // Here generation of the referenced elements
         UserRolesView1::factoryCreate(); 
         UserRolesView1::factoryCreate(); 
         
        $this->assertTrue(UserRolesView1::count() > 0);
        
        $all = UserRolesView1::all();
        
        // Here the verification that sthe returned list is correct
     } 
}
