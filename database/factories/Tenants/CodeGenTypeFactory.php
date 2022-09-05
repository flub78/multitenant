<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace Database\Factories\Tenants;

use App\Models\Tenants\CodeGenType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class CodeGenTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CodeGenType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        $count = CodeGenType::count ();
        $next = $count + 1;
        
        return [
            'name' => "name_" . $next . "_" . Str::random(),
            'phone' => "phone_" . $next . "_" . Str::random(),
        	'description' => $this->faker->unique()->text(200),
            'year_of_birth' => rand(1901, 2099),
            'weight' => $this->faker->unique()->randomFloat(2, 3.0, 300.0),
        	'birthday' => $this->faker->unique()->date(__("general.database_date_format")),
        	'tea_time' => $this->faker->unique()->time("H:i:s"),
        	'takeoff' => $this->faker->unique()->date(__("general.database_datetime_format")),
            'price' => $this->faker->unique()->randomFloat(2, 0, 1000),
            'big_price' => $this->faker->unique()->randomFloat(2, 0, 10000),
            'qualifications' => rand(0, 10000),
            'color_name' => $this->faker->unique()->randomElement(["blue","red","green","white","black"]),
            'picture' => $file = UploadedFile::fake()->image('picture.jpg'),
            'attachment' => $file = UploadedFile::fake()->create('attachment.pdf', 3)->store('attachment.pdf'),
        ];
    }
    
    /**
     * return a list of erroneous fields and associated expected errors 
     * [
     *      ["fieds" => [],
     *       "errors" => ["name" => "The name field is required."]
     *      ],
     *      ["fields" => ['name' => $too_long_name, 'email' => 'incorrect_email'],
     *       "errors" => ['name' => 'The name must not be greater than 255 characters.', 'email' => 'The email must be a valid email address.']
     *      ]
     * ]
     * @return string[]
     */
    public function error_cases () {
        $scenarios = [];
        // $scenarios[] = ["fields" => [], "errors" => ["name" => "The name field is required."]];
        // $scenarios[] = ["fields" => ["name" => $bad_name], "errors" => ["name" => "The name must not be greater than 255 characters."]];
        $scenarios[] = ["fields" => ["picture" => "not_a_picture"], "errors" => ["picture" => "The picture must be a file of type: jpeg, bmp, png."]];
        return $scenarios;       
    }
}
