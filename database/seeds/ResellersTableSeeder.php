<?php

use Illuminate\Database\Seeder;
use App\Reseller;
use App\Discount;

class ResellersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Reseller::create([
            'id' => 1,
            'name' => 'Carlos Andres',
            'email' => 'carlosaperez1997@gmail.com',
            'password' => \Hash::make('123456789'),
            'document' => '1090508062',
            'minimum_value' => '10000',
        ]);
        Discount::create([
            'id' => 1,
            'reseller_id' => 1,
            'user_id' => 1,
            'discount' => 0.00
        ]);
        Discount::create([
            'id' => 2,
            'reseller_id' => 1,
            'user_id' => 1,
            'discount' => 0.00
        ]);
    }
}
