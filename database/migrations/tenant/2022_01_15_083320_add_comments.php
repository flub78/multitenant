<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * It is not possible to modify column comment in the up migration procedure because the table does not exist yet in the up routine.
 * So a special migration is required to be run after the referenced table creation.
 * 
 * @author frederic
 *
 */
class AddComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	// to add a comment to a table :
    	DB::select("ALTER TABLE users COMMENT = 'This a comment for the users table'");
    	
    	// to add a comment to a field :
    	foreach (["calendar_events", "configurations", "roles", "users", "user_roles"] as $table) {
    		DB::select("ALTER TABLE $table" . ' MODIFY COLUMN created_at timestamp DEFAULT CURRENT_TIMESTAMP COMMENT "{\"fillable\":\"no\", \"inTable\":\"no\", \"inForm\":\"no\"}"');
    		DB::select("ALTER TABLE $table" . ' MODIFY COLUMN updated_at timestamp DEFAULT CURRENT_TIMESTAMP COMMENT "{\"fillable\":\"no\", \"inTable\":\"no\", \"inForm\":\"no\"}"');
    	}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Nothing to do, we do not really care if comments are only deleted with their column
    }
}
