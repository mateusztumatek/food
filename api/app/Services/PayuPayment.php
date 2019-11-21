<?php

namespace App\Services;
use OpenPayU_Order;
use OpenPayU_Configuration;
use Carbon\Carbon;
use App\Services\PayuModel;
class PayuPayment{
    public function __construct()
    {
        OpenPayU_Configuration::setEnvironment(config('payu.env'));
        OpenPayU_Configuration::setMerchantPosId(config('payu.pos_id'));
        OpenPayU_Configuration::setSignatureKey(config('payu.md5'));
        OpenPayU_Configuration::setOauthClientId(config('payu.client_id'));
        OpenPayU_Configuration::setOauthClientSecret(config('payu.client_secret'));
    }
    public function getById($id){
        return OpenPayU_Order::retrieve($id)->getResponse();
    }
    public function makePayment($ord){
        $order['continueUrl'] = config('app.url').'/orders/'.$ord->hash;
        $order['notifyUrl'] = url('/orders/notify');
        $order['customerIp'] = $_SERVER['REMOTE_ADDR'];
        $order['merchantPosId'] = OpenPayU_Configuration::getMerchantPosId();
        $order['description'] = 'Zamówienie nr '.$ord->id;
        $order['currencyCode'] = 'PLN';
        $order['totalAmount'] = $ord->amount;
        $order['extOrderId'] = $ord->id;
        foreach ($ord->OrderItems as $key => $item){
            $order['products'][$key]['name'] = $item->item->name;
            $order['products'][$key]['unitPrice'] = $item->item->price;
            $order['products'][$key]['quantity'] = $item->quantity;
        }

        //optional section buyer
        $order['buyer']['email'] = $ord->email;
        $order['buyer']['firstName'] = $ord->name;

        $response = OpenPayU_Order::create($order);
        $service = new PayuPayment();
        $payu_order = $service->getById($response->getResponse()->orderId);
        if(array_key_exists(0, $payu_order->orders)){
            $ord = $payu_order->orders[0];
            $history = collect();
            $history->push(PayuHistory::create(Carbon::now(), 'Płatność utworzona', 'Płatność dla zamówienia '.$ord->extOrderId. 'utworzona w systemie PayU'));
            PayuModel::create([
                'local_order_id' => $ord->extOrderId,
                'payu_order_id' => $ord->orderId,
                'currency' => $ord->currencyCode,
                'totalAmount' => number_format(intval($ord->totalAmount)/100, 2),
                'history' => serialize($history),
                'status' => $payu_order->status->statusCode,
                'link' => $response->getResponse()->redirectUri
            ]);
        }
        return ['redirect' => $response->getResponse()->redirectUri];
    }
}