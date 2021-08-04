<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Factories\ProductFactory;

use App\Models\Product;

use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Product::create([
        //     'product_name'  => 'Seed Product',
        //     'category_id'   => 1,
        //     'description'   => 'test',
        //     'stripped_description' => 'test',
        //     'date_and_time' => Carbon::now(),
        // ]);

        Product::factory()
            ->count(10)
            ->create();
    }
}
