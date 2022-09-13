<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            [
                'type' => 'single'
            ],
            [
                'type' => 'multi-limit'
            ],
            [
                'type' => 'single-expires'
            ],
            [
                'type' => 'multi-expires'
            ],
            [
                'type' => 'unlimited'
            ]
        ];
        foreach ($types as $key => $value) {
            Type::create($value);
        }
    }
}
