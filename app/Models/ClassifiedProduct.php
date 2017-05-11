<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class ClassifiedProduct extends Model {

    protected $table    = 'classified_products';
    protected $fillable = ['id', 'system_generated_product_id', 'user_id', 'user_address_id', 'category_id', 'product_conditions_id', 'name','video_link','description','product_origin','meta_tag','meta_keyword','meta_description','product_slug','status','product_listing_price','base_price','created_at','updated_at','deleted_at'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    /*protected $guarded = ['submit_type', '_token', 'product_origin', 'ckeditor', 'billing_address_1', 'billing_address_2', 'billing_postal_code', 'billing_country', 'billing_state', 'billing_city', 'bin', 'maf', 'auction', 'auction_by_price', 'auction_by_time', 'bin_maf', 'bin_auction', 'bin_auction_by_price', 'bin_auction_by_time'];
*/
    public function Files() {
        return $this->morphMany('App\Models\Files', 'imageable');
    }

    public function productCategory() {
        return $this->belongsTo('App\Models\Category', 'category_id', 'id');
    }

    public function classifiedDayTime() {
        return $this->hasMany('App\Models\ClassifiedDayTime');
    }

    public function classifiedRelatedProduct() {
        return $this->hasMany('App\Models\ClassifiedRelatedProduct');
    }

    public function productOriginAddress() {
        return $this->hasOne(UserAddress::class, 'id', 'user_address_id');
    }

    public static function getProductDetails($where = [], $status = 'Active') {
        return self::select('*')->with(['Files','classifiedDayTime','classifiedRelatedProduct','productOriginAddress'])->with(['requestPreview' => function ($query) {
                            $query->groupBy('user_id');
                        }])->where($where)->first();
    }

    public static function updateClassifiedProduct($where, $data,$update = null) {

        if(!empty($update))
        {
            return self::whereIn('id',$where)->update($data);    
        }else{
            return self::where($where)->update($data);
        }
        
        
    }

    public static function getOnlyProductDetails($where = [], $status = 'Active') {
        return self::select('*')->where($where)->first();
    }
    
    public function requestPreview() {
        return $this->hasMany('App\Models\RequestPreview','classified_products_id');
    }

    public static function getAllClassifiedProducts($IsSearch = []) {
        
        if(empty($IsSearch))
        {
            return self::select(['classified_products.*',
                            DB::raw("(CAST(RAND() * 40 AS UNSIGNED) + 1) AS stock_available"), //products.stock_available
                            DB::raw("(CAST(RAND() * 50 AS UNSIGNED) + 1) AS viewers_per_day"),
                            DB::raw("(CAST(RAND() * 4 AS UNSIGNED) + 1) AS total_rating"),
                        ])
                        ->with('productCategory')
                        ->with(['requestPreview' => function ($query) {
                            $query->groupBy('user_id');
                        }])
                        ->where(['user_id' => loggedinUserId()]);
        }else{
            return self::select(['classified_products.*',
                            DB::raw("(CAST(RAND() * 40 AS UNSIGNED) + 1) AS stock_available"), //products.stock_available
                            DB::raw("(CAST(RAND() * 50 AS UNSIGNED) + 1) AS viewers_per_day"),
                            DB::raw("(CAST(RAND() * 4 AS UNSIGNED) + 1) AS total_rating"),
                        ])
                        ->with('productCategory')
                        ->with(['requestPreview' => function ($query) {
                            $query->groupBy('user_id');
                        }])
                        ->whereHas('requestPreview', function($query) {
                            $query->where('updated_at', '>= ', \Request::input('updated_at'));
                        })
                        ->where(['user_id' => loggedinUserId()]);
        }
    }

    public static function getCountClassifiedProduct ()
    {
       return self::count();
    }

    
}