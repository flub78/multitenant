<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace tests\Unit\Tenants;

use Tests\TenantTestCase;

use App\Models\Tenants\CodeGenTypesView1;
use App\Models\Tenants\CodeGenType;

/**
 * Unit test for CodeGenTypesView1 model
 
 * @author frederic
 *
 */
class CodeGenTypesView1ModelTest extends TenantTestCase {
        
    /*
     * Test of the view model is a little different from a regular table as
     *    - the test is only read only
     *    - and it require the generation of elements in the tables referenced by the view
     */
     
     public function test_view() {
        $this->assertTrue(true);
        
        // Here generation of the referenced elements
        $name1 = 'name_1';
        CodeGenType::factory()->create(['name' => $name1, 'description' => 'description_1']);
        $description_2 = 'description_2';
        CodeGenType::factory()->create(['name' => 'name_2', 'description' => $description_2]);
        
        $this->assertTrue(CodeGenTypesView1::count() > 0);
        
        $all = CodeGenTypesView1::all();

        // Here the verification that the returned list is correct
        $this->assertEquals($name1, $all[0]->name);
        $this->assertEquals($description_2, $all[1]->description);
        
     } 
}
