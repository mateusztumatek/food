<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
class PayuModel extends Model {
    protected $fillable = ['local_order_id', 'payu_order_id', 'currency', 'totalAmount', 'history', 'status', 'link'];
    protected $table = 'payu_payments';
    public function order(){
        return $this->belongsTo('App\Order', 'local_order_id');
    }
}