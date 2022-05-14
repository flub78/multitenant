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
 * Migration to create the motds table
 
 * @author frederic
 *
 */
class CreateMotdsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     * @SuppressWarnings("PMD.ShortMethodName")
     */
    public function up() {
    
        Schema::create ( 'motds', function (Blueprint $table) {
            $table->id();
            $table->string('title', 128)->nullable();
            $table->string('message', 1024);
            $table->date('publication_date');
            $table->date('end_date')->nullable();
            $table->timestamp('created_at')->useCurrent()->comment('{"fillable":"no", "inTable":"no", "inForm":"no"}');
            $table->timestamp('updated_at')->useCurrent()->comment('{"fillable":"no", "inTable":"no", "inForm":"no"}');
            
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists ( 'motds' );
    }
}
