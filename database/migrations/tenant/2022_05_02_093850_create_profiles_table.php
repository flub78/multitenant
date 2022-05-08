<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create the profiles table
 
 * @author frederic
 *
 */
class CreateProfilesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     * @SuppressWarnings("PMD.ShortMethodName")
     */
    public function up() {
    
        Schema::create ( 'profiles', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 128);
            $table->string('last_name', 128);
            $table->date('birthday')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('created_at')->useCurrent()->comment('{"fillable":"no", "inTable":"no", "inForm":"no"}');
            $table->timestamp('updated_at')->useCurrent()->comment('{"fillable":"no", "inTable":"no", "inForm":"no"}');

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('restrict')->onDelete('restrict');
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists ( 'profiles' );
    }
}
