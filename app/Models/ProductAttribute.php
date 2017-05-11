<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $table = 'product_attributes';
    
    protected $fillable = ['product_id', 'product_sku_id', 'attribute_id', 'attribute_value_id', 'attribute_value', 'attribute_set_id'];
    
    public static function createProductAttribute($request) {
        $data = $request;
        return self::create($data);
    }
    
    public static function updateProductAttribute($where, $data) {
        return self::where($where)->update($data);
    }
    public function attributeValue() {
        return $this->hasOne(AttributeValues::class,'id','attribute_value_id');
    }
    public function attribute() {        
        return $this->hasOne(Attribute::class,'id','attribute_id');
        //return $this->hasManyThrough(Attribute::class,AttributeSet::class,'id1','attribute_set_id');
    }
    
    public function getattributes($product_id=null,$product_sku_id=null){
        if (isset($product_id) && !empty($product_id)) {
            $productAttributes = self::select('*')
                    ->where(['product_id' => $product_id])
                    ->with('attribute','attributeValue')
                    ->get();
            return $productAttributes;

        }
    }
}
