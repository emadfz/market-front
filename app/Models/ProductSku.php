<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model {

    protected $table    = 'product_skus';
    protected $fillable = ['product_id', 'additional_price', 'sku', 'quantity', 'available_in_bulk', 'image', 'is_default', 'final_price'];

    public static function createProductSku($request) {
        $data = $request;
        return self::create($data);
    }

    public static function updateProductSku($where, $data) {
        return self::where($where)->update($data);
    }

    public static function getProductSku($id = '') {
        return self::with('product');
    }

    public function product() {
        return $this->belongsTo(Product::class)->with('category');
    }

    public function productVariantAttribute() {
        return $this->hasMany(ProductAttribute::class)->where('product_sku_id', '!=', 0);
    }

    public function productAttribute() {
        switch ($this->$type) {
            case 'wordpress':
                return $this->hasOne('App\WordpressProject');
            case 'anotherType':
                return $this->hasOne('App\AnotherTypeOfProject');
        }
    }

    public static function getProductSkuDetails($product_id = null)
    {
        $productSkuDetails = self::select('id')->where(['product_id' => $product_id])->first();
        
        return $productSkuDetails;
    }
    public static function getPrimaryProductSkuDetails($product_id = null)
    {
        $productSkuDetails = self::select('*')->with('productVariantAttribute')->where(['product_id' => $product_id],['is_default' => 'yes'])->first();
        return $productSkuDetails;
    }

}
