<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerced to avoid overwritting.
 */

namespace Database\Factories\Tenants;

use App\Models\Tenants\CalendarEvent;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CalendarEventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CalendarEvent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
    	$count = CalendarEvent::count();
    	$next = $count + 1;
    	$event = "event $next";
        return [
            'title' => $event,
        	'description' => 'appointement',
        	'start' => '2021-06-23'        		
        ];
    }
}
