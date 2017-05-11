<?php
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class OrderShipmentDetails extends Model
{
    //
    public function ProductSku()
    {
        return $this->belongsTo('App\Models\ProductSku','product_skus_id','id')->with('product');
    }
    public function Order() {
        return $this->belongsTo('App\Models\Order')->where('user_id',\Auth::id());
    }
	public function saveShipmentDetails($data , $content ,$order_id)
	{   $count = 0;
		foreach ($content as $key => $content) 
		{
			$shipment                = new $this;
			$shipment->order_id      = $order_id;
			$shipment->product_skus_id    = $content->id;
			$shipment->address       = $data['address1'];
			$shipment->quantity      = $content->qty;
			$shipment->product_price = $content->price;
			$shipment->total_price   = $content->qty * $content->price;
			$shipment->promo_code    = 'x';
                        $shipment->user_id    = \Auth::id();
			$count++;
			$shipment->save();

		}
		
		return $count;
	}
	public function product($id)
        {
           $product = Product::find($id);
           return $product;
        }
        public function recently_purchased_product($status = null){
            if(isset($status) && ($status != null)){
                    return $this->select('*')->where('user_id',\Auth::id())
                            ->with('ProductSku')
                            ->where('order_status',$status)
                            ->orderBy('created_at', 'ASC')
                            ->limit(5)
                            ->get();
            }else{
                    return $this->select('*')->where('user_id',\Auth::id())
                            ->with('ProductSku')
                            ->orderBy('created_at', 'ASC')
                            ->limit(5)
                            ->get();
            }        
        }
}

