<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::insert([
            [
                'name' => 'Milligram',
                'symbol' => 'mg',
                'type' => 'mass',
                'conversion_factor' => 0.001,
            ],
            [
                'name' => 'Gram',
                'symbol' => 'gr',
                'type' => 'mass',
                'conversion_factor' => 1,
            ],
            [
                'name' => 'Kilogram',
                'symbol' => 'kg',
                'type' => 'mass',
                'conversion_factor' => 1000,
            ],
            [
                'name' => 'Milliliter',
                'symbol' => 'mL',
                'type' => 'volume',
                'conversion_factor' => 1,
            ],
            [
                'name' => 'Liter',
                'symbol' => 'L',
                'type' => 'volume',
                'conversion_factor' => 1000,
            ],
            [
                'name' => 'Piece',
                'symbol' => 'pcs',
                'type' => 'count',
                'conversion_factor' => 1,
            ],
            [
                'name' => 'Box',
                'symbol' => 'box',
                'type' => 'other',
                'conversion_factor' => null,
            ],
        ]);
    }
}
