<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	// See https://fullcalendar.io/docs/event-object
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->boolean('allDay')->default(true);
            $table->datetime('start');	// '2021-06-29 00:00:00'
            $table->datetime('end')->nullable();	// '2021-06-28 11:30:00'
            $table->boolean('editable')->default(true);
            $table->boolean('startEditable')->default(true);
            $table->boolean('durationEditable')->default(true);
            
            // colors
            // #f00, #ff0000, rgb(255,0,0), or red.
            // https://www.w3schools.com/colors/colors_hex.asp
            $table->string('backgroundColor')->nullable(); 
            $table->string('borderColor')->nullable();		  
            $table->string('textColor')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_events');
    }
}
