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
        CodeGenType::factory()->create();
        CodeGenType::factory()->create();
        
        $this->assertTrue(CodeGenTypesView1::count() > 0);
        
        $all = CodeGenTypesView1::all();
        // Here the verification that the returned list is correct
        // var_dump($all);
        echo "\nname = " . $all[0]->name . "\n";
        echo "description = " . $all[0]->description . "\n";
     } 
}
