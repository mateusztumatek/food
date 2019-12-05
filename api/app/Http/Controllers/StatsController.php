<?php

namespace App\Http\Controllers;

use App\Item;
use App\Order;
use App\OrderItem;
use App\Place;
use App\Product;
use App\Sale;
use App\Stats;
use App\StatsItem;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PragmaRX\Tracker\Vendor\Laravel\Facade as Tracker;
class StatsController extends Controller
{
    public function index(Request $request){
        $to_return = [];
        $user = $request->user('api');
        if(!Cache::has('stats.'.$user->id) || $request->date_from || $request->date_to){
            /* SET DATE */
            if(!$request->date_from) $request->request->set('date_from', Carbon::now()->subMonth());
            if(!$request->date_to) $request->request->set('date_to', Carbon::now());
            if(is_object($request->date_from) && get_class($request->date_from) == 'Carbon\Carbon') $to_return['date_from'] = $request->date_from->format('Y-m-d');
            if(is_object($request->date_to) == 'Carbon\Carbon') $to_return['date_to'] = $request->date_to->format('Y-m-d');
            /* END SET DATE */

            $places = Place::where('user_id', $user->id)->get();
            $orders = Order::where(function ($q)use($places){
                foreach ($places as $p){
                    $q->orWhere('place_id', $p->id);
                }
            })->where(function ($q)use($request){
                if($request->date_from){
                    $q->where('created_at', '>=', Carbon::parse($request->date_from));
                }
                if($request->date_to){
                    $q->where('created_at', '<=', Carbon::parse($request->date_to));
                }
            })->get();

            /* GET STATS */
            if(!$request->type || $request->type == 'shop_enter'){
                $stats = Stats::where(function ($q)use($places){
                    foreach ($places as $p){
                        $q->orWhere('place_id', $p->id);
                    }
                })->where(function ($q)use($request){
                    if($request->date_from){
                        $q->where('created_at', '>=', Carbon::parse($request->date_from));
                    }
                    if($request->date_to){
                        $q->where('created_at', '<=', Carbon::parse($request->date_to));
                    }
                })->get();
                $to_return['shop_enter'] = $stats->count();
            }
            /* GET STATS */

            if(!$request->type || $request->type == 'products'){
                $products = Item::where(function ($q)use($places){
                    foreach ($places as $p){
                        $q->orWhere('place_id', $p->id);
                    }
                })->where(function ($q)use($request){
                    if($request->date_from){
                        $q->where('created_at', '>=', Carbon::parse($request->date_from));
                    }
                    if($request->date_to){
                        $q->where('created_at', '<=', Carbon::parse($request->date_to));
                    }
                });
                $to_return['products'] = $products->count();
            }
            if(!$request->type || $request->type == 'orders'){
                $to_return['orders'] = $orders->count();
            }
            if(!$request->type || $request->type == 'profit'){
                $amount = 0;
                foreach ($orders as $order){
                    $amount = $amount + $order->amount;
                }
                $to_return['profit'] = $amount;
            }
            if(!$request->type){
                Cache::put('stats.'.$user->id, $to_return);
            }
        }else{
            $to_return = Cache::get('stats.'.$user->id);
        }
        return response()->json($to_return);
    }
   /*'type', 'place_id', 'sale_id', 'item_id', 'user_device', 'country', 'is_mobile', 'city'*/

    public function charts(Request $request){
        $user = $request->user('api');
        if(!Cache::has('charts.'.$user->id)){
            if(!$user) return response()->json(['message' => 'Brak dostępu'], 403);
            $places = Place::where('user_id', $user->id)->get();
            $orders = Order::where(function ($q)use($places){
                foreach ($places as $p){
                    $q->orWhere('place_id', $p->id);
                }
            })->get();
            /*  if($request->from && $request->to){
                  $dif = Carbon::parse($request->from)->diffInDays(Carbon::parse($request->to));
                  if($dif > 12){
                      $temp = (number_format($dif / 12, 0)).' days';
                  }else{
                      $temp = ($dif).' days';
                  }
                  $period = CarbonPeriod::create(Carbon::parse($request->from), $temp, Carbon::parse($request->to));
              }else{
                  $period = CarbonPeriod::create( Carbon::now()->subYear(), '1 month', Carbon::now());
              }*/
            $period = CarbonPeriod::create( Carbon::now()->subYear(), '15 days', Carbon::now());
            $to_return = collect();
            $to_return['categories'] = collect();
            $to_return['order_completed'] = collect();
            $to_return['order_canceled'] = collect();
            foreach ($period as $date) {
                $to_return['categories']->push($date->format('Y-m-dTH:i:s'));
                $completed = $orders->where('created_at', '>=', $date)->where('created_at', '<=', $date->addMonth()->subSeconds(2))->where('status', 'completed')->count();
                $to_return['order_completed']->push($completed);
                $canceled = $orders->where('created_at', '>=', $date)->where('created_at', '<=', $date->addMonth()->subSeconds(2))->where('status', 'canceled')->count();
                $to_return['order_canceled']->push($canceled);
            }
            Cache::put('charts.'.$user->id, $to_return);
        }else{
            $to_return = Cache::get('charts.'.$user->id);
        }
        return $to_return;
    }
    public function getProductsChart(Request $request){
        $user = $request->user('api');
        if(!Cache::has('products_chart.'.$user->id) || $request->to || $request->from){
            if(!$user) return response()->json(['message' => 'Brak dostępu'], 403);
            $places = Place::where('user_id', $user->id)->get();
            $products = Item::where(function ($q)use($places){
                foreach ($places as $p){
                    $q->orWhere('place_id', $p->id);
                }
            })->get();
            if($request->tab == 'sales'){
                foreach ($products as $p){
                    $items = OrderItem::where('item_id', $p->id)->where(function ($q)use($request){
                        if($request->from){
                            $q->where('created_at', '>=', Carbon::parse($request->from));
                        }
                        if($request->to){
                            $q->where('created_at', '<=', Carbon::parse($request->to));
                        }
                    })->get();
                    $c = 0;
                    foreach ($items as $i){
                        $c = $c + $i->quantity;
                    }
                    $p->setAttribute('count', $c);
                }
                $products = $products->sortByDesc('count');
                $products = $products->take(6);
            }
            $to_return = array();
            $to_return['products'] = collect();
            $to_return['labels'] = collect();
            foreach ($products as $p){
                $to_return['products']->push($p->count);
                $to_return['labels']->push($p->name);
            }
            Cache::put('products_chart.'.$user->id, $to_return);
        }else{
            $to_return = Cache::get('products_chart.'.$user->id);
        }

        return $to_return;
    }
    public function store(Request $request){
        $date = Carbon::now()->setDay(1)->setHour(0)->setMinute(1);
        foreach($request->all() as $stat){
            $validation = Validator::make($stat, [
                'type' => 'required'
            ]);
            if($validation->fails()) continue;
            if($stat['type'] == 'place_view'){
                $validation = Validator::make($stat, [
                    'place_id' => 'required'
                ]);
                if($validation->fails()) continue;
                $user_data = $this->getUserInfo($request);
                if(!Stats::where('place_id', $stat['place_id'])->where('type', $stat['type'])->where('session_key', $request->header('session-key'))->first()){
                    Stats::create([
                        'type' => $stat['type'],
                        'user_id' => ($request->user('api'))? $request->user('api')->id : null,
                        'place_id' => $stat['place_id'],
                        'user_device' => (array_key_exists('user_device', $user_data))? $user_data['user_device'] : null,
                        'city' => (array_key_exists('city', $user_data))? $user_data['city'] : null,
                        'lat' => (array_key_exists('city', $user_data))? $user_data['lat'] : null,
                        'lng' => (array_key_exists('lng', $user_data))? $user_data['lng'] : null,
                        'is_mobile' => (array_key_exists('is_mobile', $user_data))? $user_data['is_mobile'] : null,
                        'country' => (array_key_exists('country', $user_data))? $user_data['country'] : null,
                        'ip' => (array_key_exists('ip', $user_data))? $user_data['ip'] : null,
                        'session_key' => $request->headers->get('session-key'),
                    ]);
                }
            }
            /*if($stat['type'] == 'item_view'){
                $item = Item::find($stat['item_id']);
                if($item){
                    $stats = $item->stats_arr;
                    $tmp_date = Carbon::now()->setDay(1)->setHour(0)->setMinute(1);
                    $tmp_date->subMinutes(3);
                    $stats->where('date', '>=', $tmp_date)->where('type', 'item_view')->take(1);
                    if(count($stats) == 0){
                        $stat_item = new \App\Services\StatsItem($date, $stat['type'], 1);
                        $item->stats = serialize(collect($stat_item));
                        $item->update();
                    }else{
                        $stat_item = $stats[0];
                        $stat_item->value = $stat_item->value + 1;
                        $tmp = $item->stats_arr;
                    }
                }
            }*/
        }
    }
    public function getUserInfo($request){
        $geoip = geoip($request->ip());
        $data = array();
        $visitor = Tracker::currentSession();
        $data['user_device'] = $visitor->device->platform;
        $data['is_mobile']= $visitor->device->is_mobile;
        $data['ip'] = $visitor->client_ip;
        if($geoip){
            $data['country']= ($geoip->country)? $geoip->country : null;
            $data['city']= ($geoip->city)? $geoip->city : null;
            $data['lat']= ($geoip->lat)? $geoip->lat : null;
            $data['lng']= ($geoip->lon)? $geoip->lon : null;
        }
        return $data;
    }
    public function getClients(Request $request){
        $user = $request->user('api');
        if(!$user) return response()->json(['message' => 'Brak dostępu'], 403);
        $places = Place::where('user_id', $user->id)->get();
        $stats = Stats::where(function ($q)use($places){
            foreach ($places as $p){
                $q->orWhere('place_id', $p->id);
            }
        })->where('created_at', '>=', Carbon::today())->with('user')->paginate(5);
        return response()->json($stats);
    }
}
