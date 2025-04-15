<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Beras Premium',
                'category' => 'Sembako',
                'price' => 120000.00,
                'unit' => 'karung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Minyak Goreng 2L',
                'category' => 'Sembako',
                'price' => 28000.00,
                'unit' => 'botol',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gula Pasir 1kg',
                'category' => 'Sembako',
                'price' => 15000.00,
                'unit' => 'pak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kopi Bubuk 500gr',
                'category' => 'Minuman',
                'price' => 25000.00,
                'unit' => 'pak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Teh Celup 25pcs',
                'category' => 'Minuman',
                'price' => 10000.00,
                'unit' => 'kotak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
