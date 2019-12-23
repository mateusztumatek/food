<?php

use Illuminate\Database\Seeder;

class SeedOrdersTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $statuses = ['new', 'completed', 'canceled'];
        $sales = \App\Sale::whereHas('items')->whereHas('place', function($q){
            $q->where('user_id', 1);
        })->get();
        foreach ($sales as $sale){
            for ($i = 0; $i<450; $i++){
                $date = \Carbon\Carbon::now()->subHours(rand(1, 8600));
                if($date < \Carbon\Carbon::now()->subHour()) (rand(1,10) < 8)? $status = 'completed' : $status = 'canceled';
                else $status = 'new';
                if(rand(0,1) == 1) $paid = true;
                else $paid = false;
                $local_id = \App\Order::where('sale_id', $sale->id)->count() + 1;
                $products = $sale->items()->take(5)->get();
                $amount = 0;
                foreach ($products as $p){
                    $amount = $amount + $p->price;
                }
/*                protected $fillable = ['user_id', 'sale_id', 'place_id', 'payment_link', 'payment_date', 'local_id', 'amount', 'comment', 'payment_type', 'paid', 'status', 'time', 'name', 'email', 'hash'];*/
                $order = \App\Order::create([
                    'user_id' => (rand(0,1) == 1)? \App\User::inRandomOrder()->first()->id : null,
                    'sale_id' => $sale->id,
                    'place_id' => $sale->place->id,
                    'payment_link' => null,
                    'payment_date' => ($paid)? \Carbon\Carbon::now() : null,
                    'local_id' => $local_id,
                    'amount' => $amount,
                    'comment' => $faker->text(200),
                    'payment_type' => 'bank',
                    'paid' => $paid,
                    'status' => $status,
                    'time' => 30,
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'hash' => md5(\Illuminate\Support\Str::random(60)),
                    'created_at' => $date,
                    'updated_at' => $date
                ]);
                foreach ($products as $item){
                    $order->OrderItems()->create([
                        'item_id' => $item->id,
                        'order_id' => $order->id,
                        'quantity' => 1,
                        'single_price' => $item->price,
                        'total_price' => $item->price * 1,
                        'comment' => $faker->text(50)
                    ]);
                }
            }
        }
    }
}
