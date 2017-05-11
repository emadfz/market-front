<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferDetails extends Model
{
    protected $guarded = [];
    protected $table = 'offer_detail';

    public static function retractOffer($where, $data) {
        return self::where($where)->update($data);	    
    }

    public static function updateOfferDetails($where, $data) {
        return self::where($where)->update($data);	    
    }
}
