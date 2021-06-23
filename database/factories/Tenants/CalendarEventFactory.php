<?php

namespace Database\Factories\Tenants;

use App\Models\Tenants\CalendarEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition()
    {
    	$count = CalendarEvent::count();
    	$next = $count + 1;
    	$event = "event $next";
        return [
            'title' => $event,
        	'groupId' => 'appointement',
        	'start' => '2021-06-23'
        		
        ];
    }
}
