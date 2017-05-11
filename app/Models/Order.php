<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {

    protected $table = 'orders';
    protected $fillable = ['id','shoppingcart_identifier','user_id','order_amount','order_status','created_at','updated_at'];

    public function getOrders($id=null){
        if(isset($id) && !empty($id)){
            return $this->where('id',$id)->first();
        }
            return $this->select('*')->where('user_id',\Auth::id())->orderBy('created_at', 'ASC')->get();
    }
    public function getOrderswithdetails($id=null){
        if(isset($id) && !empty($id)){
            return $this->where('id',$id)->first();
        }
            return $this->select('*')
                    ->where('user_id',\Auth::id())
                    ->with('shipmentDetails')
                    ->orderBy('created_at', 'ASC')->get();
    }
    public function cancel_order($id=null){
        if (isset($id) && !empty($id)) {
            return $this->where('id',$id)->update(['order_status' => 'canceled']);
        }
    }
    

    public function saveOrder($data)
    {

		$order = new $this;
        $order->shoppingcart_identifier = $data['ref_no'];
        $order->user_id = \Auth::id();
        $order->order_amount =$data['amount'];
        $order->order_status = 'new';
        $order->save();

        return $order->id;
    }
    public function recently_purchased_product(){
            return $this->select('*')
                    ->with('shipmentDetails')
                    ->orderBy('created_at', 'ASC')
                    ->limit(5)
                    ->get();
        }
// relations functions 

    public function billingDetails()
    {
        return $this->hasOne('App\Models\OrderBillingDetails');
    }
     public function paymentDetails()
    {
        return $this->hasOne('App\Models\OrderPaymentDetails');
    }

     public function shipmentDetails()
    {
        return $this->hasMany('App\Models\OrderShipmentDetails');
    }
}
