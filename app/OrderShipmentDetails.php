<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;


class OrderShipmentDetails extends Model
{
    //

	public function saveShipmentDetails($data , $content ,$order_id)
	{   $count = 0;
		foreach ($content as $key => $content) 
		{
			$shipment                = new $this;
			$shipment->order_id      = $order_id;
			$shipment->product_id    = $content->id;
			$shipment->address       = $data['address1'];
			$shipment->quantity      = $content->qty;
			$shipment->product_price = $content->price;
			$shipment->total_price   = $content->qty * $content->price;
			$shipment->promo_code    = 'x';
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
}

