<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    protected $sale_id;
    public $order, $method;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($order, $method)
    {
        $this->order = $order;
        $this->sale_id = $this->order->sale->id;
        $this->method = 'order_'.$method;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [new Channel('SaleChannel.'.$this->sale_id),
            new Channel('OrderChannel.'.$this->order->id)
        ];
    }
    public function broadcastAs(){
        return $this->method;
    }
}
