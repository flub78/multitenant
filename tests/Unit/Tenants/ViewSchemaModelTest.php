<?php

namespace tests\Unit\Tenants;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\ViewSchema;
use Illuminate\Database\QueryException;

class ViewSchemaModelTest extends TestCase
{
    // Clean up the database
    use RefreshDatabase;
    
    public function test_is_view() {
    	$view_def = ViewSchema::isView("code_gen_types_view1");
    	$this->assertNotEquals('', $view_def);

    	$view_def = ViewSchema::isView("user_roles_view1");
    	
    	$this->assertEquals('', ViewSchema::isView(""));
    	$this->assertEquals('', ViewSchema::isView("users"));
    }
    
    public function test_dev() {
    	$def1 = "select `tenantabbeville`.`code_gen_types`.`name` AS `name`,`tenantabbeville`.`code_gen_types`.`description` AS `description`,`tenantabbeville`.`code_gen_types`.`tea_time` AS `tea_time` from `tenantabbeville`.`code_gen_types` where 1";
    	$def2 = "select `tenantabbeville`.`users`.`name` AS `user_name`,`tenantabbeville`.`users`.`email` AS `user_email`,`tenantabbeville`.`roles`.`name` AS `role_name` from ((`tenantabbeville`.`users` join `tenantabbeville`.`user_roles`) join `tenantabbeville`.`roles`) where `tenantabbeville`.`user_roles`.`user_id` = `tenantabbeville`.`users`.`id` and `tenantabbeville`.`user_roles`.`role_id` = `tenantabbeville`.`roles`.`id`";

    	$this->assertEquals(["name", "description", "tea_time"], ViewSchema::fieldList($def1));
    	$this->assertNotEquals(["user_name", "user_email", "role_name"], ViewSchema::fieldList($def2));    	
    }
    
    public function test_scan_view () {
        $view_def = ViewSchema::isView("code_gen_types_view1");
        $view_list = ViewSchema::ScanViewDefinition($view_def);
        // var_dump($view_list);
        $this->assertEquals("name", $view_list[0]['name']);
        $this->assertEquals("description", $view_list[1]['field']);
    }
}
