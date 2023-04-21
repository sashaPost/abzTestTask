<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();

        // $name = $faker->name();

        for ($i = 0; $i < 45; $i++) {
            User::create([
                'name' => $faker->name(),
                // make it smarter; use 'name' for 'email' creation
                'email' => $faker->unique()->safeEmail(),
                // 'email' => strtolower(trim($faker->name())) . $faker->regexify('@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?'),
                // 'email' => $faker->unique()->regexify('^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$'),
                'password' => Hash::make('password'),
                // 'phone' => $faker->phoneNumber(),
                'phone' => $faker->regexify('^\+380(5|6|7|9)(3|[5-9])\d{7}$'),
                'position_id' => $faker->numberBetween(1, 4),
                'photo' => '/images/users/' . Str::slug($faker->name()) . '.jpeg',
            ]);
        }
    }
}
