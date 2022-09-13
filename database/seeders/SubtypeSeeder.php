<?php

namespace Database\Seeders;

use App\Models\Subtype;
use Illuminate\Database\Seeder;

class SubtypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subtypes = [
            [
                'subtype' => 'X% OFF'
            ],
            [
                'subtype' => 'FLAT RATE OFF'
            ],
            [
                'subtype' => 'FREE 1+1'
            ]
        ];
        foreach ($subtypes as $key => $value) {
            Subtype::create($value);
        }
    }
}
