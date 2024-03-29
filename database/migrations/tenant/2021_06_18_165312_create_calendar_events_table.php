<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @author frederic
 *
 * @SuppressWarnings("PMD.ShortMethodName")
 */
class CreateCalendarEventsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     * @SuppressWarnings("PMD.ShortVariable")
     */
	public function up() {
    	// See https://fullcalendar.io/docs/event-object
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default("titre");
            $table->string('description')->nullable();
            $table->boolean('allDay')->default(true)->comment('{"subtype": "checkbox"}');
            $table->datetime('start')
            	->comment('{"subtype": "datetime", "fillable":"yes"}');	// '2021-06-29 00:00:00'
            $table->datetime('end')->nullable()
            ->comment('{"subtype": "datetime", "fillable":"yes"}');	// '2021-06-28 11:30:00'
            $table->boolean('editable')->default(true)
            	->comment('{"subtype": "checkbox", "inTable": "no", "inForm": "no"}');
            $table->boolean('startEditable')->default(true)
            	->comment('{"subtype": "checkbox", "inTable": "no", "inForm": "no"}');
            $table->boolean('durationEditable')->default(true)
            	->comment('{"subtype": "checkbox", "inTable": "no", "inForm": "no"}');
            
            // colors
            // #f00, #ff0000, rgb(255,0,0), or red.
            // https://www.w3schools.com/colors/colors_hex.asp
            $table->string('backgroundColor')->default("#ffffcc")->nullable()
            	->comment('{"subtype": "color", "inTable": "no", "inForm": "yes"}'); 
            $table->string('borderColor')->nullable()
            ->comment('{"subtype": "color", "inTable": "no", "inForm": "no"}');		  
            $table->string('textColor')->default("#000066")->nullable()
            	->comment('{"subtype": "color", "inTable": "no", "inForm": "yes"}');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('calendar_events');
    }
}
