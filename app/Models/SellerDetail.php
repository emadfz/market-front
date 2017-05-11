<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerDetail extends Model {

    //protected $guarded = ['position_other', 'bank_name', 'bank_phone_number', 'bank_routing_number', 'bank_account_number', 'website'];
    protected $fillable = ['user_id', 'business_name', 'industry_type_id', 'business_details', 'tax_id_number', 'business_reg_number', 'business_phone_number', 'website', 'position_id', 'position_other', 'video_link', 'bank_name', 'bank_phone_number', 'bank_routing_number', 'bank_account_number'];
    protected $table = 'seller_details';

    public static function createSellerDetail($request) {
        $data = $request;
        $data['industry_type_id'] = $request['industry_type'];
        $data['position_id'] = $request['position'];
        $data['position_other'] = ($request['position_other']=="")?"-":$request['position_other'];
        unset($data['industry_type'], $data['position']);
        return self::create($data);
    }

    /**
     * Get the user that owns the seller detail.
     */
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the industry name associated with the seller.
     */
    public function industryType() {
        return $this->hasOne('App\Models\IndustryType');
    }

    public static function updateSellerDetail($where, $data) {
        return self::where($where)->update($data);
    }

    public function Files() {
        return $this->morphMany('App\Models\Files', 'imageable');
    }

    public function saveSellerDetail($data, $id = null, $image = '', $flg = '',$update='') {

        if (isset($id) && !empty($id)) {
            unset($data['_token']);
            unset($data['_method']);
            unset($data['code']);
            $seller = $this->where('user_id', $id)->first();
            $data = self::where('user_id', $id)->update($data);

            if (isset($image) && !empty($image)) {
                if (is_array($image) && array_key_exists(0, $image)) { 
                    $i = 0;
                    foreach ($image as $image) {
                        if ($flg == 'update') {
                            if(array_key_exists($i, $update)){
                                $seller->Files()->where('id','=',$update[$i]['id'])->update($image);
                            }else{
                                $seller->Files()->create($image);
                            }
                        } else {
                            $seller->Files()->create($image);
                        }
                        $i++;
                    }
                } else { 
                    if ($flg == 'update') {
                        if(array_key_exists(0, $update)){
                            $seller->Files()->where('id','=',$update[0]['id'])->update($image);
                        }else{
                            $seller->Files()->create($image);
                        }
                    } else {
                        $seller->Files()->create($image);
                    }
                }
            }
            //return $this->where('id',$id)->update($data);
            return $seller;
        }

        $seller = $this->create($data);
        if (isset($image) && !empty($image)) {
            $seller->Files()->create($image);
        }
        return $seller;
    }
    
    /**
     * Get the user that owns the seller detail.
     */
    public function seller() {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

}
