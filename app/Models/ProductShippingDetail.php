<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductShippingDetail extends Model {

    protected $table = 'product_shipping_details';

    /**
     * Fields that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id', 'parcel_dimension_length', 'parcel_dimension_width', 'parcel_dimension_height',
        'length_class', 'parcel_weight', 'weight_class', 'shipping_type'];

    public static function createProductShippingDetail($request) {
        //echo "<pre>";print_r($request);die;
        return self::create($request);
    }

}
