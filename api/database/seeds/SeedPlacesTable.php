<?php

use Illuminate\Database\Seeder;

class SeedPlacesTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $cities = \App\Helpers\Helper::csvToArray(storage_path('/app/miasta.csv'), ',');
        $images = scandir(storage_path('app/public/places/example'));
        $images = array_slice($images, 2, count($images));
        $i = 0;
        $collect = collect($cities);
        $cities = $collect->shuffle();
        $lat = 51.100876799999995;
        $lng = 17.0369024;
        foreach ($cities as $key => $city){
            $user = \App\User::inRandomOrder()->first();
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address=Wrocław&key=AIzaSyDtb3Q4IcFfJ8FM4M8f7DbaPuk448NcyoM";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($ch);
            curl_close($ch);
            $response_a = json_decode($response);
            dd($response_a);
            if(is_object($response_a)){
                if(property_exists($response_a, 'results')){
                    $results = $response_a->results;

                    if(array_key_exists(0, $results)){
                        $lat = $results[0]->geometry->location->lat;
                        $lng = $results[0]->geometry->location->lng;
                    }
                }
            }

             if(isset($lat)){
                 \App\Place::create([
                     'user_id' => $user->id,
                     'name' => $faker->company,
                     'description' => $faker->text(150),
                     'city' => $city['Miasto'],
                     'postal_code' => $city['Kod'],
                     'street' => $faker->streetName.' '.$faker->streetAddress,
                     'image' => 'places/example/'.$images[rand(0, count($images) -1)],
                     'lat' => $lat,
                     'lng' => $lng
                 ]);
             }

             $i = $i+1;
             if($i > 50){
                 break;
             }
        }
    }
}
