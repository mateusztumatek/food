<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for ($i=0; $i<100; $i++){
            $name = $faker->name();
            \App\User::create([
                'name' => $name,
                'email' => $faker->email(),
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'login' => substr($name, 0, 6),
                'desc' => $faker->text(200),
                'image' => null,
                'city' => $faker->city(),
            ]);
        }
    }
}
