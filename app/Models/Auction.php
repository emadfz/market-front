<?php

namespace App\Models;

use Jenssegers\Mongodb\Model as Moloquent;

class Auction extends Moloquent
{
    protected $connection = 'mongodb';    
    protected $collection = 'auction';
    
    public static function getAuctionBids($productId=''){
        if(isset($productId) && !empty($productId)){
            return self::where('productId',(int)$productId)->orderBy('createdAt','desc')->get();
        }
        return self::orderBy('createdAt','desc')->get();
    }
    public static function recent_AuctionBids(){
        return self::orderBy('createdAt','desc')
                ->where('user_id',\Auth::id())
                ->get();
    }
}
