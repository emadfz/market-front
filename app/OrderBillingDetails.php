<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderBillingDetails extends Model
{
    //
      protected $table = 'order_billing_details';

      public function saveBillingDetails($data ,$order_id)
      {

			$billing                     = new $this;
			$billing->order_id           = $order_id;
			$billing->billing_address_1  = $data['address1'];
			$billing->billing_address_2  = $data['address2'];
			$billing->zip_code           = $data['zip'];
			$billing->country            = $data['country'];
			$billing->phone_number       = $data['phone_number'];
			$billing->state              = $data['state'];
			$billing->city               = $data['city'];
			$billing->general_promo_code = 'x';
      	return $billing->save();

      }
}