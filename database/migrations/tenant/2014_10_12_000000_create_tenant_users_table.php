<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @SuppressWarnings("PMD.ShortVariable")
     */
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            $table->string('name');
            
            $table->string('email')
            	->unique()
            	->comment('{"subtype": "email"}');
            
            $table->timestamp('email_verified_at')->nullable()
	            ->comment('{"fillable":"no", "inTable":"no", "inForm":"no"}');
            
            $table->string('password')
            	->comment('{"subtype": "password_with_confirmation", "fillable":"yes", "inTable":"no", "inForm":"yes"}');
            
            $table->boolean('admin')->default(false)
            	->comment('{"subtype": "checkbox"}');
            
            $table->boolean('active')->default(true)
            	->comment('{"subtype": "checkbox"}');
            
            $table->rememberToken()->comment('{"fillable":"no", "inTable":"no", "inForm":"no"}');
            
            $table->timestamps(); 
            // No direct support for comment by the timestamp helper, the routine returns null
            // ->comment('{"fillable":"no", "inTable":"no", "inForm":"no"}');
            
            // these comments will be added in a subsequent migration (as the table does not exist yet
            // when this code is executed
                        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
