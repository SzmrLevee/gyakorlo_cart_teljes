<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Aloe Vera',
            'description' => 'Sokoldalú gyógynövény. Nagyon kevés vizet igényel.',
            'price' => 2500,
            'size_cm' => 9
        ]);

        Product::create([
            'name' => 'Amerikai kövirózsa (Echeveria)',
            'description' => 'Gyönyörű rózsaszerű pozsgás, változatos színekben. Könnyen gondozható és gyorsan szaporodik.',
            'price' => 1800,
            'size_cm' => 7
        ]);

        Product::create([
            'name' => 'Sarjika (Kalanchoe daigremontiana)',
            'description' => 'Elevenszülő pozsgás, amely a leveleinek szélén kis palántákat növeszt, amik gyökeret eresztenek, és leesnek.',
            'price' => 1200,
            'size_cm' => 6
        ]);

        Product::create([
            'name' => 'Jade növény (Crassula ovata)',
            'description' => 'Vastag, húsos levelekkel rendelkező pozsgás. Kedvelt szobanövény.',
            'price' => 2800,
            'size_cm' => 13
        ]);
    }
}
