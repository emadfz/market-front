<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AdvertisementCatMap extends Model {
    
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'advertise_map_cat';

    protected $fillable = ['advertise_id', 'cat_id','created_at','updated_at','deleted_at','id'];

    public function AdvertisementCategory() {
        return $this->hasOne('App\Models\AdvertisementCategory','advertisement_id', 'advertise_id')->with('Files');
    }

    /*public static function getCategoryIDByAdID($adID) {
        return self::where();
    }*/

    public function deleteAdCategoryAssociation($adID) {        
        if (isset($adID) && !empty($adID)) {
            return $this->where('advertise_id', $adID)->delete();
        }
        return false;
    }

    
}
