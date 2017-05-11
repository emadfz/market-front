<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPaymentDetails extends Model
{
    //
       public function savePaymentDetails($data ,$order_id)
      {
			
			$payment                       = new $this;
			$payment->order_id             = $order_id;
			$payment->payment_method       = $data['payment_method'];
			$payment->card_type            = $data['card_type'];
			$payment->card_number          = $data['card_number'];
			$payment->card_expiration_date = $data['expiration_month']
											.$data['expiration_year'];
											
			$payment->card_holder_name     = $data['card_name'];
			$payment->card_cvv             = $data['cvv'];
			
			return $payment->save();

      }
}

 
  