<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class IndustryTypesTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $industryTypes = ['Jewellery', 'Real Estate', 'Fashion', 'Clothing', 'Automobile', 'Electronics', 'Fitness and Sports'];
        foreach ($industryTypes AS $industry) {
            DB::table('industry_types')->insert([
                'title' => $industry,
                 'created_at' => Carbon::now(),
                 'updated_at' => Carbon::now(),
            ]);
        }
    }

}
