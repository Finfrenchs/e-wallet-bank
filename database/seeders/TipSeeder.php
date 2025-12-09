<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
//db
use Illuminate\Support\Facades\DB;

class TipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tips')->insert([
            [
                'title' => 'Nabung Emas di Pegadaian, Solusi Cerdas untuk Masa Depan Gemilang!',
                'thumbnail' => 'img_tips1.png',
                'url' => 'https://pegadaian.co.id/produk/tabungan-emas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Regularly monitor your transaction history to spot any unauthorized activities.',
                'thumbnail' => 'img_tips2.png',
                'url' => 'https://pegadaian.co.id/produk/tabungan-emas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Use strong and unique passwords for your account to enhance security.',
                'thumbnail' => 'img_tips3.png',
                'url' => 'https://pegadaian.co.id/produk/tabungan-emas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Enable two-factor authentication (2FA) for an added layer of security.',
                'thumbnail' => 'img_tips4.png',
                'url' => 'https://pegadaian.co.id/produk/tabungan-emas',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
