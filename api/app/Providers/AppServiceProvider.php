<?php

namespace App\Providers;

use App\Observers\OrderObserver;
use App\Observers\PayuModelObserver;
use App\Order;
use App\Services\PayuModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength('191');
        Order::observe(OrderObserver::class);
        PayuModel::observe(PayuModelObserver::class);
        date_default_timezone_set ( 'Europe/Warsaw' );
    }
}
