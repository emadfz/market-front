<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

use App\Http\Requests;

class emadTesting extends Controller
{
	public function index()
	{
    
    
		$data['ref_no'] = 234234;
		$data['amount'] = 234;
		$x = new Order;
		$order = $x->saveOrder($data);


    return   $order;
	}
}
