<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use DB;


class Offers extends Model
{
    protected $guarded  = [];    
    protected $table    = 'offers';

    public function offerDetails()
    {
        return $this->hasMany('\App\Models\offerDetails')->where('offer_status', '!=', 'Cancelled');
    }

    public function products()
    {
        return $this->belongsTo('\App\Models\Product','product_id');
    }

    public function users()
    {
        return $this->belongsTo('\App\Models\User','seller_id');
    }

     public function userBuyerRelation()
    {
        return $this->belongsTo('\App\Models\User','buyer_id');
    }

    public function offerAllDetails()
    {
        return $this->hasMany('\App\Models\offerDetails','offers_id');
    }

    public static function updateOffer($where, $data) 
    {
        return self::where($where)->update($data);    
    }

    public static function getOfferById($where = []) 
    {
        return self::select('*')->where($where)->first();
    }

    public static function getAllOffers($IsSearch = '') 
    {
        
        if(empty($IsSearch))
        {	
            return self::select('*')->with('offerDetails')->has('offerDetails')->where(['seller_id' => loggedinUserId()]);
        }else{
            //return self::select('*')->with('offerDetails')->where(['seller_id' => loggedinUserId()])->where( 'name', 'like', '%' . $IsSearch . '%' );
        }
    }

    public static function getProductOfferDetails($where = []) 
    {
        /*return self::select('*')
                        //->with('offerDetails')
                        ->with(['offerDetails'=> function($query) {
                                $query->where('offer_status', '!=', 'Cancelled');
                            }])
                        ->where($where)->has('offerDetails')
                        ->get();*/

        return self::select('*')
                        ->with('offerDetails','userBuyerRelation')
                        ->where($where)->has('offerDetails')
                        ->get();
    }

    public static function getOfferCommunication($where =[])
    {
        return self::select('*')
                        ->with('offerAllDetails')
                        ->where($where)->has('offerAllDetails')
                        ->get();
    }

    public static function getBuyerOffer($where =[],$whereIn =[])
    {
        if(empty($whereIn))
        {
            return self::select('*')
                        ->with(['offerDetails','products','products.Files','users'])
                        ->where($where)->has('offerDetails')
                        ->get();
        }else{
            return self::select('*')
                        ->with(['offerDetails','products','products.Files','users'])
                        ->where($where)->whereIn('product_id',$whereIn)->has('offerDetails')
                        ->get();
        }
    }
}