<?php

namespace Database\Seeders;

use App\Models\Position;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $positions = [
            ['name' => 'Security'],
            ['name' => 'Designer'],
            ['name' => 'Content manager'],
            ['name' => 'Lawyer'],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }
    }
}
