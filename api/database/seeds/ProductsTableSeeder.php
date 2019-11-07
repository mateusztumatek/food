<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
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
        $categories = ['Pizze', 'Piwa', 'Napoje ciepłe', 'Zapiekanki', 'Drinki', 'Whisky', 'Ryby'];
        $files = scandir(storage_path('/app/public/products/temp'));
        $files = array_slice($files, 2, count($files));

        foreach ($places as $place){
            if(count($place->products) == 0){
                for ($i=0; $i<rand(25,80); $i++){
                    $request = new \Illuminate\Http\Request();
                    $request->setMethod('POST');
                    $request->request->set('place_id', $place->id);
                    $request->request->set('name', $faker->name);
                    $request->request->set('description', $faker->text(200));
                    $request->request->set('image', '/products/temp/'.$files[rand(0, count($files) - 1)]);
                    $request->request->set('price', $faker->randomFloat(2, 0, 1000));
                    $request->request->set('active', true);
                    $request->request->set('categories', [$categories[rand(0, count($categories) -1)]]);
                    $request->request->set('tags', [$faker->word, $faker->word]);
                    \App\Services\ItemsService::create($request);
                }
            }
        }
    }
}
