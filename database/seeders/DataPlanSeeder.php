<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DataPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('data_plans')->insert([
            [
                'name' => '10 GB',
                'price' => 100000,
                'operator_card_id' => 1, //sesuaikan yg sudah terbuat pada table operator card
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => '25 GB',
                'price' => 200000,
                'operator_card_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => '40 GB',
                'price' => 350000,
                'operator_card_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => '60 GB',
                'price' => 500000,
                'operator_card_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => '10 GB',
                'price' => 100000,
                'operator_card_id' => 2, //sesuaikan yg sudah terbuat pada table operator card
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => '25 GB',
                'price' => 200000,
                'operator_card_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => '40 GB',
                'price' => 350000,
                'operator_card_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => '60 GB',
                'price' => 500000,
                'operator_card_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => '10 GB',
                'price' => 100000,
                'operator_card_id' => 3, //sesuaikan yg sudah terbuat pada table operator card
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => '25 GB',
                'price' => 200000,
                'operator_card_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => '40 GB',
                'price' => 350000,
                'operator_card_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => '60 GB',
                'price' => 500000,
                'operator_card_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),

            ]

        ]);
    }
}
