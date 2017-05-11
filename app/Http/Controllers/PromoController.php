<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PromoCode;
use App\PromocodeLog;
use App\Http\Requests;

class PromoController extends Controller
{

	public function getDetails($promo_code, $user_id)
	{ 

		$promo = PromoCode::where('promo_code', $promo_code)->first();		
 			// {"id":1,"promo_code":"DEC28","discount":"11.00","discount_type":"percentage","start_date":"2016-11-28","end_date":"2016-12-30","selected_users":"all","users_id":1,"user_type":"Admin","status":"Active","deleted_at":null,"created_at":"2016-11-28 12:53:26","updated_at":"2016-11-28 12:53:26"}


		if (count($promo))
		{
			//promo code is found and true
			if ($promo->selected_users == "all" && $promo->status == 'Active' && $promo->promo_code === $promo_code ) 
			{
                $promo_log = PromocodeLog::where('user_id', $user_id)->where('promo_code', $promo_code)->first();
                if (count($promo_log))
                {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'The Promotion code is used before for this user',
                    ]);
                }
                else
                {
                    $new_promo_log = new PromocodeLog ;
                    $new_promo_log->promo_code = $promo_code;
                    $new_promo_log->user_id = $user_id;
                    $new_promo_log->promo_code_id = $promo->id;
                    $new_promo_log->product_id = NULL;
                    $new_promo_log->order_id = NULL;
                    $new_promo_log->save();
                    return response()->json([
                        'status'        => 'success',
                        'discount_type' => $promo->discount_type,
                        'discount'      => $promo->discount,
                        'start_date'    => $promo->start_date,
                        'end_date'      => $promo->end_date,
                        'code_status'   => $promo->status,
                        'message'       => 'The Promotion code is valid',
                    ]);
                }
				// the user has the right to apply this promo code 

			}
			else
			{
				// this use has no right to apply this promo code 
				return response()->json([
				'status'  => 'error',				
				'message' => 'The Promotion code is invalid',
				]);	
			}

		}
		else
		{
			//promo code is not in the database 
			return response()->json([
			'status'  => 'error',				
			'message' => 'The Promotion code is invalid',
			]);	
		}


		// try{
		// 	return response()->json([
		// 		'status'     =>'success',
		// 		'promo_code' => $promo->promo_code,		
		// 		'user_id'    => $user_id,
		// 		'message'    => 'Promo code successfully applied!!',
		// 		]);
		// }
		// catch(\Exception $e){
		// 	return response()->json([
		// 		'status'  =>'error',				
		// 		'message' => 'There is some error!!',
		// 		]);	
		// }
	}
}
