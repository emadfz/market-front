<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvertisementCategory extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'avertisement_cat';

     protected $fillable = ['advr_url', 'advertisement_id', 'start_date', 'end_date', 'no_of_days', 'status', 'type', 'created_at', 'updated_at', 'deleted_at',  'id'];
    
    public function Files() {        
        return $this->morphMany('App\Models\Files', 'imageable');
    }

    public static function updateAdvertisementCategory($where,$data) {        
        return self::where($where)->update($data);
    }

    public static function getIdByAdID($adID)
    {
      return self::select('id')->where('advertisement_id',$adID)->get();
    }


    public function deleteAdCat($adId) {        
        if (isset($adId) && !empty($adId)) {
            return $this->where('advertisement_id', $adId)->delete();
        }
        return false;
    }
}
