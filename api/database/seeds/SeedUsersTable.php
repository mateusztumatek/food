<?php

use Illuminate\Database\Seeder;

class SeedUsersTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i<100; $i++){

            \App\User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'login' => substr($faker->name, 0, 6),
                'desc' => $faker->text(200),
                'image' => '/users/default'
            ]);
        }
    }
}
