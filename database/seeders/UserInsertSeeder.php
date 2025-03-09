<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;
use Spatie\Permission\Models\Role;

class UserInsertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->name = $faker->name();
            $user->email = $faker->email();
            $user->password = bcrypt('12345678');
            $user->save();
            $user->assignRole('User');
        }

    }
}
