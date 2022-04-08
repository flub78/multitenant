<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A migration to create the views to test the code generator
 * 
 * @author frederic
 *
 */
class CreateTestViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @SuppressWarnings("PMD.ShortMethodName")
     */
    public function up() {
    	\DB::statement($this->createView1());
    	\DB::statement($this->createView2());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
    	\DB::statement($this->dropView1());
    	\DB::statement($this->dropView2());
    }
    
    /**  
    * Create view 1
    *
    * @return query
    */
    private function createView1(): string {
    	
    	return "CREATE OR REPLACE VIEW code_gen_types_view1 AS select `code_gen_types`.`name` AS `name`,`code_gen_types`.`description` AS `description`,`code_gen_types`.`tea_time` AS `tea_time` from `code_gen_types` where 1
";
    }
    
    /**
    * Reverse the migrations.
    *
    * @return void
    */
    private function dropView1(): string {
    	
    	return "DROP VIEW IF EXISTS `code_gen_types_view1` ";
    }

    /**
     * Create view 1
     *
     * @return query
     */
    private function createView2(): string {
    	
    	return "CREATE OR REPLACE VIEW user_roles_view1 AS select `users`.`name` AS `user_name`,`users`.`email` AS `user_email`,`roles`.`name` AS `role_name` from ((`users` join `user_roles`) join `roles`) where `user_roles`.`user_id` = `users`.`id` and `user_roles`.`role_id` = `roles`.`id`";
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    private function dropView2(): string {
    	
    	return "DROP VIEW IF EXISTS `user_roles_view1` ";
    }
    
}
