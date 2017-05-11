<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAuction extends Model {

    protected $table = 'product_auctions';

    /**
     * Auctions that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id', 'mode', 'min_bid_increment', 'min_reserved_price', 'max_product_price', 'start_datetime', 'end_datetime'];
    
    public static function createProductAuction($request) {
        $data = $request;
        $data['start_datetime'] = convertToDatetimeFormat($request['start_datetime']);
        $data['end_datetime'] = convertToDatetimeFormat($request['end_datetime']);
        return self::create($data);
    }

}
