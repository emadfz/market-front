<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    protected $table = 'products';
    //protected $fillable = ['category_id', 'description', 'manufacturer', 'price', 'is_return_applicable', 'is_warranty_applicable', 'status','category_id','product_slug'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['submit_type', '_token', 'product_origin', 'ckeditor', 'billing_address_1', 'billing_address_2', 'billing_postal_code', 'billing_country', 'billing_state', 'billing_city', 'bin', 'maf', 'auction', 'auction_by_price', 'auction_by_time', 'bin_maf', 'bin_auction', 'bin_auction_by_price', 'bin_auction_by_time'];

    public function Files() {
        return $this->morphMany('App\Models\Files', 'imageable');
    }
    public function auction(){
        return $this->hasOne(ProductAuction::class);
    }    
    public function ProductSku() {
        return $this->hasMany('App\Models\ProductSku')->where('is_default','Yes');
    }

    public function getProductsByCategory($categoryIds,$getAllProducts=false, $skip = 0, $take = '',$requestdata=[]) {
        //dd($requestdata);
        $data=$this->select('*')->with('Files');
        $data=$data->whereHas('productSkusVariantAttribute',
                function($skuquery) use ($requestdata) {
                        $condition1 = (isset($requestdata['from']) && !empty($requestdata['from'])) || (isset($requestdata['from']) && $requestdata['from']==0);
                        $condition2 = (isset($requestdata['to']) && !empty($requestdata['to'])) || (isset($requestdata['to']) && $requestdata['to']==0);                        
                        if($condition1){
                            $skuquery->where('final_price','>=',$requestdata['from']);
                        }
                        if($condition2){
                            $skuquery->where('final_price','<=',$requestdata['to']);
                        }
                }
        );
        
        unset($requestdata['from']);
        unset($requestdata['to']);
            
        if(count($requestdata)>0){
                $data=$data->whereHas('ProductAttribute',
                    function($query) use ($requestdata) {
                            foreach($requestdata as $attributeSlug=>$attributeValue){
                                  $query->where(function ($subquery) use ($requestdata,$attributeSlug,$attributeValue) {
                                       $attribute=Attribute::where("attribute_slug", $attributeSlug)->first();
                                       $attributeValues=AttributeValues::where('attribute_values',$attributeValue)->where('attribute_id',$attribute->id)->first();
                                       $subquery
                                            ->where('attribute_id', $attribute->id)
                                            ->where('attribute_value_id', $attributeValues->id);
                                  });
                            }
                            $query->where('product_sku_id','!=',0);
                    }
                );
        }
        else{            
            $data=$data->with('ProductAttribute');
        }
        
        $data=$data->with('productSkusVariantAttribute');
        if($getAllProducts){
            return $data->whereIn('category_id', $categoryIds)->get();
        }
        else{
            return $data->whereIn('category_id', $categoryIds)->skip($skip)->take($take)->get();
            //DB::enableQueryLog();echo '<pre>';print_r(\DB::getQueryLog());die;
        }
        //\DB::enableQueryLog();echo '<pre>';print_r(\DB::getQueryLog()[19]);die;
        
        
    }

    public function getProductsByCategoryCount($categoryIds) {
        return $this->select('id')->whereIn('category_id', $categoryIds)->count();
    }

    /*public function getFeaturedProducts($where = []) {
        $data = $this->select('*')->with('Files')->where($where)->orderBy('id', 'DESC')->get();
        return $data;
    } */
    public function getFeaturedProducts($where = []) {
        $data = $this->select('*')
                ->with('Files','ProductSku')
                ->where($where)
                ->orderBy('id', 'DESC')->get();
        return $data;
    } 
      public function getPopularProducts($where = []) {
        $data = $this->select('*')
                ->with('Files','ProductSku')
                ->where($where)
                ->orderBy('id', 'ASC')->get();
        return $data;
    } 
    
/*
    public function getPopularProducts() {
        $data = $this->select('*')->with('Files','ProductSku')->orderBy('id', 'ASC')->get();
        return $data;
    } */

    public static function getProductDetails($where = [], $status = 'Active') {
        return self::select('*')->with('productSkusVariantAttribute','productSkusVariantAttribute.productVariantAttribute.attribute','productSkusVariantAttribute.productVariantAttribute.attributeValue','productOriginAddress','productOriginAddress.country','productOriginAddress.state','productOriginAddress.city','auction')->where($where)->status($status)->first();
    }
     public static function getProductDetailsnonvariant($where = [], $status = 'Active') {
        return self::select('*')
                ->with('productNonVariantAttribute','productNonVariantAttribute.attribute','productNonVariantAttribute.attribute.AttributeValues')
                ->where($where)->status($status)->first();        
    }

    public static function getProductDetailsById($where = [], $status = 'Active') {
        return self::select('*')->where($where)->first();
    }

    /**
     * Scope a query to only include active status
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, $status = 'Active') {
        return $query->where('status', $status);
    }

    public function getProductsByIds($ids) {
        return $this->with('Files')->select('*')->whereIn('id', $ids)->get();
    }

    public static function createProduct($request) {
        $data = $request;
        $data['user_id'] = loggedinUserId();
        $data['system_generated_product_id'] = generateToken($request['name']);
        $data['product_slug'] = str_slug($request['name']);
        return self::create($data);
    }

    public static function updateProduct($where, $data,$update = null) {
        if(!empty($update))
        {
            return self::whereIn('id',$where)->update($data);    
        }else{
            return self::where($where)->update($data);    
        }
        
    }

    public function category() {
        return $this->belongsTo('App\Models\Category', 'category_id', 'id')->with("attributeSets");
    }
    public function productCategories() {
        return $this->belongsTo('App\Models\Category', 'category_id', 'id');
    }

    /* Get product attributes */

    public static function getProductAttributes($productId) {
        return $resultSet = self::with(['category'])->where(['id' => $productId])->get();
    }

    public function getProduct($id = '') {
        if (isset($id) && !empty($id)) {
            return $this->where('products.id', $id)->with('category')->first();
        }
        return $this->with('category');
    }

    public function productAuction() {
        return $this->hasOne(ProductAuction::class);
    }

    public function productOriginAddress() {
        return $this->hasOne(UserAddress::class, 'id', 'user_address_id');
    }

    public function productSkus() {
        return $this->hasMany(ProductSku::class, 'product_id', 'id');
    }

    public function productSkusVariantAttribute() {
        return $this->hasMany(ProductSku::class, 'product_id', 'id')->with('productVariantAttribute');
    }
    
    public function productNonVariantAttribute() {
        return $this->hasMany(ProductAttribute::class, 'product_id', 'id')->where(['product_sku_id' => 0]);
    }

    /*
     * get all products - loggedin seller id
     */

    public static function getAllProducts($IsSearch = '') {
        
        if(empty($IsSearch))
        {
            return self::select(['products.*',
                            //DB::raw("sum(quantity) AS stock_available"), //products.stock_available
                            DB::raw("'29d 10h' AS expiration"),
                            DB::raw("(CAST(RAND() * 50 AS UNSIGNED) + 1) AS viewers_per_day"),
                            DB::raw("(CAST(RAND() * 4 AS UNSIGNED) + 1) AS total_rating"),
                        ])
                        ->with(['productCategory','Files','productSkus'])
                        ->where(['user_id' => loggedinUserId()])->get();
        }else{
            return self::select(['products.*',
                            //DB::raw("(CAST(RAND() * 40 AS UNSIGNED) + 1) AS stock_available"), //products.stock_available
                            DB::raw("'29d 10h' AS expiration"),
                            DB::raw("(CAST(RAND() * 50 AS UNSIGNED) + 1) AS viewers_per_day"),
                            DB::raw("(CAST(RAND() * 4 AS UNSIGNED) + 1) AS total_rating"),
                        ])
                        ->with(['productCategory','Files','productSkus'])
                        ->where(['user_id' => loggedinUserId()])
                        ->where( 'name', 'like', '%' . $IsSearch . '%' );
        }
    }

    public function productCategory() {
        return $this->belongsTo('App\Models\Category', 'category_id', 'id');
    }

    public function ProductAttribute(){
        return $this->hasMany('App\Models\ProductAttribute');
    }
    
    public function Offers() {
        return $this->hasMany('App\Models\Offers');
    }

    public function orderShipmentDetails() {
        return $this->hasMany('App\OrderShipmentDetails');
    }

    public static function getAllProductsWithOffer($IsSearch = array()) {
        
        if(empty($IsSearch))
        { 
            return self::select(['*',
                            DB::raw("(CAST(RAND() * 40 AS UNSIGNED) + 1) AS stock_available"), //products.stock_available
                            DB::raw("'29d 10h' AS expiration"),
                            DB::raw("(CAST(RAND() * 50 AS UNSIGNED) + 1) AS viewers_per_day"),
                            DB::raw("(CAST(RAND() * 4 AS UNSIGNED) + 1) AS total_rating"),
                        ])
                        ->with(['productCategory','Offers','orderShipmentDetails'])
                        ->where(['user_id' => loggedinUserId()])->has('Offers');

        }else if(isset($IsSearch['search'])){
            return self::select(['*',
                            DB::raw("(CAST(RAND() * 40 AS UNSIGNED) + 1) AS stock_available"), //products.stock_available
                            DB::raw("'29d 10h' AS expiration"),
                            DB::raw("(CAST(RAND() * 50 AS UNSIGNED) + 1) AS viewers_per_day"),
                            DB::raw("(CAST(RAND() * 4 AS UNSIGNED) + 1) AS total_rating"),
                        ])
                        ->with(['productCategory','Offers','orderShipmentDetails'])
                        ->where(['user_id' => loggedinUserId()])
                        ->where( 'name', 'like', '%' . $IsSearch['search'] . '%' )->has('Offers');
                        
        }else if(isset($IsSearch['created_at'])){
            return self::select(['*',
                            DB::raw("(CAST(RAND() * 40 AS UNSIGNED) + 1) AS stock_available"), //products.stock_available
                            DB::raw("'29d 10h' AS expiration"),
                            DB::raw("(CAST(RAND() * 50 AS UNSIGNED) + 1) AS viewers_per_day"),
                            DB::raw("(CAST(RAND() * 4 AS UNSIGNED) + 1) AS total_rating"),
                        ])
                        ->with(['productCategory','Offers','orderShipmentDetails'])
                        ->where(['user_id' => loggedinUserId()])
                        ->whereHas('Offers', function($query) {
                            $query->where('updated_at', '>=', \Request::input('created_at'));
                        }) ->has('Offers');
        }
    }

    public static function getProductDetailsWithBidders($where = [], $status = 'Active') {
        return self::select('*')->with('Offers')->where($where)->status($status)->first();
    }

    public static function productIdByName($productName = '')
    {
        return self::select('id')->where( 'name', 'like', '%' . $productName . '%' )->get();
    }
    public static function getAuctionProducts($data = array(),$productIds=array(),$userId=''){  
            $product=self::with(['auction','productCategory','productSkus','Files']);
            
            if( isset($userId) && !empty($userId) ){
                $product->where('user_id',$userId);
            }
            if(count($productIds)>0){
                $product->whereIn('id',$productIds);
            }            
            return $product->has('auction');
    }
    public function getProductByManufacturer($manufacturer,$skip = 0, $take = 12,$data=array() ){
        $products=$this->with('productCategories','Files')
                    ->where('manufacturer',$manufacturer);
                if(isset($data['categories']) && count($data['categories'])>0){
                    $products->whereHas('productCategories',function($query) use ($data){
                            $query->whereIn('category_slug',$data['categories']);
                    });
                }
                if(isset($data['q']) && count($data['q'])>0){
                    $products->where('name','like','%'.$data['q'].'%');
                }
                    
                $products=$products->skip($skip)
                                   ->take($take)
                                   ->get();
                    
        return $products;
        
    }
    public function getProductByOccasion($occasionId,$productSearch=''){
        if(isset($productSearch) && !empty($productSearch)){
            return $this->where('occassion_id',$occasionId)->where('name','LIKE','%'.$productSearch.'%')->get(); 
        }
        return $this->where('occassion_id',$occasionId)->get(); 
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
