<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Datatables;
use Carbon\Carbon;

class AdvertisementHome extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'avertisement_home';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['advr_url', 'advertisement_id', 'start_date', 'end_date', 'no_of_days', 'status', 'type', 'created_at', 'updated_at', 'deleted_at',  'id'];
    public function Files()
    {        
      return $this->morphMany('App\Models\Files', 'imageable', '', 'imageable_id', 'id');
      //return $this->morphMany('App\Models\Files', 'imageable', '', 'imageable_id', 'advertisement_id');
    }
    public function advertisements(){                        
        //return $this->belongsTo('App\Models\Advertisement','advertisement_id','id');        
        return $this->hasOne('App\Models\Advertisement','id','advertisement_id');        
    }
    public function getAdvertisements($type,$id=null){
        if(isset($id) && !empty($id)){
            return $this->where('id',$id)->select('donation_vendors.id', 'donation_vendors.vendor_name', 'donation_vendors.vendor_description', 'donation_vendors.website_link', 'donation_vendors.admin_fees', 'donation_vendors.start_date', 'donation_vendors.end_date', 'donation_vendors.status')->first();
        }
      return $this->where('type',$type)
              ->where('start_date', '<=', date("Y-m-d"))
              ->where('status', '!=', '0')
              ->select('advr_url', 'advertisement_id', 'start_date', 'end_date', 'no_of_days', 'status', 'type', 'created_at', 'updated_at', 'deleted_at',  'id')
              ->with('advertisements')
              ->whereHas('advertisements',
                      function($query){
                            $query->where('location', '=', 'Home');
                      }
               )
              ->get();
    }
    
    public static function updateAdvertisementHome($where,$data) {
      return self::where($where)->update($data);
    }

    public static function getIdByAdID($where)
    {
      return self::select('id')->where($where)->first();
    }

    public function deleteAdHome($adId) {        
        if (isset($adId) && !empty($adId)) {
            return $this->where('advertisement_id', $adId)->delete();
        }
        return false;
    }
}