<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use Carbon\Carbon;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $description = Str::random(20);
        return [
            'product_name' => $this->faker->name,
            'category_id' => 1,
            'description' => $description,
            'stripped_description' => $description,
            'date_and_time' => Carbon::now(),
        ];
    }
}
