<?php

namespace App\Observers;

use App\Services\PayuModel;

class PayuModelObserver
{
    /**
     * Handle the order "created" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function created(PayuModel $payuModel)
    {
        $order = $payuModel->order;
        if($payuModel->status == 'COMPLETED' || $payuModel->status == 'SUCCESS'){
            $order->paid = true;
        }else{
            $order->paid = false;
        }
        $order->update();
    }

    /**
     * Handle the order "updated" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function updated(PayuModel  $payuModel)
    {
        $order = $payuModel->order;
        if($payuModel->status == 'COMPLETED' || $payuModel->status == 'SUCCESS'){
            $order->paid = true;
        }else{
            $order->paid = false;
        }
        $order->update();
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function deleted(PayuModel  $order)
    {
        //
    }

    /**
     * Handle the order "restored" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function restored(PayuModel  $order)
    {
        //
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function forceDeleted(PayuModel  $order)
    {
        //
    }
}
