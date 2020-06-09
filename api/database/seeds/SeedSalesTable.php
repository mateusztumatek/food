<?php

use Illuminate\Database\Seeder;

class SeedSalesTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $places = \App\Place::all();
      /*  $sales = \App\Sale::all();
        foreach ($sales as $sale){
            if(count($sale->items) == 0){
                $products = $sale->place->products;
                $i = rand(3, 70);
                if($i > count($products)){
                    $i = count($products) -1;
                }
                for ($y = 0; $y<$i; $y++){
                    $item = [
                        'item_id' => $products[$y]->id,
                        'sale_id' => $sale->id,
                        'active' => true,
                    ];
                    DB::table('sale_items')->insert($item);
                }
            }
        }
        die;*/
        foreach ($places as $place){
            $hour_from = '0'.strval(rand(5, 9)).':00:00';
            $hour_to= strval(rand(14, 23)).':00:00';
            $products = $place->products;
            if(count($place->sales) == 0){
                $sale = \App\Sale::create([
                    'hour_from' => $hour_from,
                    'hour_to' => $hour_to,
                    'place_id' => $place->id,
                    'name' => $faker->company,
                    'lat' => $place->lat,
                    'lng' => $place->lng,
                    'city' => $place->city,
                    'street' => $place->street,
                    'postal_code' => $place->postal_code,
                    'active' => 1,
                    'payment_type' => (rand(0,1) == 0)? 'prepaid' : 'afterpaid',
                    'archivize' => 10,
                    'hash' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(40)),
                    'last_attempt' => \Carbon\Carbon::now(),
                ]);
                $i = rand(3, 70);
                if($i > count($products)){
                    $i = count($products) -1;
                }
                for ($y = 0; $y<$i; $y++){
                    $item = [
                        'item_id' => $products[$y]->id,
                        'sale_id' => $sale->id,
                        'active' => true,
                    ];
                    DB::table('sale_items')->insert($item);
                }
            }
        }
    }
}
