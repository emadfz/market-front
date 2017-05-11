<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Datatables;
use Carbon\Carbon;
use DB;

class Advertisement extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'advertisement';

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

    protected $fillable = ['advr_name', 'status', 'type','location','created_at', 'updated_at','deleted_at','id','user_id'];

    public function advertisementHome()
    {
        return $this->hasMany('\App\Models\AdvertisementHome','advertisement_id');
    }

    public function advertisementCategory()
    {
        return $this->hasMany('\App\Models\AdvertisementCategory','advertisement_id');
    }

    public function getalladvertisement($addID = null)
    {
        if(empty($addID))
        {

            $result = DB::table('advertisement as adv')
                ->select([ 'adv.id', 'adv.advr_name', 'adv.status','adv.user_id', 'adv.type', 'adv.created_at','adv_home_image.path as home_image','adv_cat_image.path as cat_image','adv_mingle_image.path as min_image'

                   , DB::raw(' case when  ( adv.id = adv_home.advertisement_id ) then '
                           . ' DATE_FORMAT( adv_home.start_date , "%c/%e/%Y" )'
                           . ' when ( adv.id = adv_cat.advertisement_id ) then '
                           . ' DATE_FORMAT( adv_cat.start_date , "%c/%e/%Y" ) '
                           . ' when ( adv.id = adv_mingle.advertisement_id ) then '
                           . ' DATE_FORMAT( adv_mingle.start_date , "%c/%e/%Y" ) end as start_date')
                   , DB::raw(' case when adv.id = adv_home.advertisement_id  then '
                           . ' DATE_FORMAT( adv_home.end_date , "%c/%e/%Y" )'
                           . ' when adv.id = adv_cat.advertisement_id then '
                           . ' DATE_FORMAT( adv_cat.end_date , "%c/%e/%Y" ) '
                           . ' when adv.id = adv_mingle.advertisement_id then '
                           . ' DATE_FORMAT( adv_mingle.end_date , "%c/%e/%Y" ) end as end_date')
                    ,DB::raw('adv.location as location ')

                    ])
                ->leftjoin('avertisement_home as adv_home','adv.id','=','adv_home.advertisement_id')
                ->leftjoin('avertisement_cat as adv_cat','adv.id','=','adv_cat.advertisement_id')
                ->leftjoin('avertisement_mingle as adv_mingle','adv.id','=','adv_mingle.advertisement_id')
                ->leftjoin('files as adv_home_image', function($join) {
                    $join->on('adv_home_image.imageable_id', '=', 'adv_home.id')->where('adv_home_image.imageable_type', '=', 'App\Models\AdvertisementHome');
                  })
                ->leftjoin('files as adv_cat_image', function($join) {
                    $join->on('adv_cat_image.imageable_id', '=', 'adv_cat.id')->where('adv_cat_image.imageable_type', '=', 'App\Models\AdvertisementCategory');
                  })
                ->leftjoin('files as adv_mingle_image', function($join) {
                    $join->on('adv_mingle_image.imageable_id', '=', 'adv_mingle.id')->where('adv_mingle_image.imageable_type', '=', 'App\Models\AdvertisementMingle');
                  })
                ->WhereNull('adv.deleted_at')->where(['user_id' => loggedinUserId()]);

        }/*else{

            $result = DB::table('advertisement as adv')
                ->select([ 'adv.id', 'adv.advr_name', 'adv.status','adv.user_id', 'adv.type', 'adv.created_at','adv_home_image.path as home_image','adv_cat_image.path as cat_image','adv_mingle_image.path as min_image','adv_home_image.id as home_image_id','adv_cat_image.id as cat_image_id','adv_mingle_image.id as min_image_id'

                   , DB::raw(' case when  ( adv.id = adv_home.advertisement_id ) then '
                           . ' DATE_FORMAT( adv_home.start_date , "%c/%e/%Y" )'
                           . ' when ( adv.id = adv_cat.advertisement_id ) then '
                           . ' DATE_FORMAT( adv_cat.start_date , "%c/%e/%Y" ) '
                           . ' when ( adv.id = adv_mingle.advertisement_id ) then '
                           . ' DATE_FORMAT( adv_mingle.start_date , "%c/%e/%Y" ) end as start_date')
                   , DB::raw(' case when adv.id = adv_home.advertisement_id  then '
                           . ' DATE_FORMAT( adv_home.end_date , "%c/%e/%Y" )'
                           . ' when adv.id = adv_cat.advertisement_id then '
                           . ' DATE_FORMAT( adv_cat.end_date , "%c/%e/%Y" ) '
                           . ' when adv.id = adv_mingle.advertisement_id then '
                           . ' DATE_FORMAT( adv_mingle.end_date , "%c/%e/%Y" ) end as end_date')
                    ,DB::raw('adv.location as location ')

                    , DB::raw(' case when adv.id = adv_home.advertisement_id  then '
                           . '  adv_home.no_of_days '
                           . ' when adv.id = adv_cat.advertisement_id then '
                           . ' adv_cat.no_of_days'
                           . ' when adv.id = adv_mingle.advertisement_id then '
                           . ' adv_mingle.no_of_days end as days')
                    , DB::raw(' case when adv.id = adv_home.advertisement_id  then '
                           . '  adv_home.advr_url '
                           . ' when adv.id = adv_cat.advertisement_id then '
                           . ' adv_cat.advr_url'
                           . ' when adv.id = adv_mingle.advertisement_id then '
                           . ' adv_mingle.advr_url end as ad_url')

                    ])
                ->leftjoin('avertisement_home as adv_home','adv.id','=','adv_home.advertisement_id')
                ->leftjoin('avertisement_cat as adv_cat','adv.id','=','adv_cat.advertisement_id')
                ->leftjoin('avertisement_mingle as adv_mingle','adv.id','=','adv_mingle.advertisement_id')
                ->leftjoin('files as adv_home_image', function($join) {
                    $join->on('adv_home_image.imageable_id', '=', 'adv_home.id')->where('adv_home_image.imageable_type', '=', 'App\Models\AdvertisementHome');
                  })
                ->leftjoin('files as adv_cat_image', function($join) {
                    $join->on('adv_cat_image.imageable_id', '=', 'adv_cat.id')->where('adv_cat_image.imageable_type', '=', 'App\Models\AdvertisementCategory');
                  })
                ->leftjoin('files as adv_mingle_image', function($join) {
                    $join->on('adv_mingle_image.imageable_id', '=', 'adv_mingle.id')->where('adv_mingle_image.imageable_type', '=', 'App\Models\AdvertisementMingle');
                  })
                ->WhereNull('adv.deleted_at')->where(['user_id' => loggedinUserId()]);

        }*/else{

            $result = DB::table('advertisement as adv')
                ->select([ 'adv.id', 'adv.advr_name', 'adv.status','adv.user_id', 'adv.type', 'adv.created_at','adv_home_image.path as home_image','adv_cat_image.path as cat_image','adv_mingle_image.path as min_image','adv_home_image.id as home_image_id','adv_cat_image.id as cat_image_id','adv_mingle_image.id as min_image_id'
                   , DB::raw(' case when  ( adv.id = adv_home.advertisement_id ) then '
                           . ' DATE_FORMAT( adv_home.start_date , "%c/%e/%Y" )'
                           . ' when ( adv.id = adv_cat.advertisement_id ) then '
                           . ' DATE_FORMAT( adv_cat.start_date , "%c/%e/%Y" ) '
                           . ' when ( adv.id = adv_mingle.advertisement_id ) then '
                           . ' DATE_FORMAT( adv_mingle.start_date , "%c/%e/%Y" ) end as start_date')
                   , DB::raw(' case when adv.id = adv_home.advertisement_id  then '
                           . ' DATE_FORMAT( adv_home.end_date , "%c/%e/%Y" )'
                           . ' when adv.id = adv_cat.advertisement_id then '
                           . ' DATE_FORMAT( adv_cat.end_date , "%c/%e/%Y" ) '
                           . ' when adv.id = adv_mingle.advertisement_id then '
                           . ' DATE_FORMAT( adv_mingle.end_date , "%c/%e/%Y" ) end as end_date')
                    ,DB::raw('adv.location as location ')
                    ,DB::raw(' case when adv.id = adv_home.advertisement_id  then '
                           . '  adv_home.no_of_days '
                           . ' when adv.id = adv_cat.advertisement_id then '
                           . ' adv_cat.no_of_days'
                           . ' when adv.id = adv_mingle.advertisement_id then '
                           . ' adv_mingle.no_of_days end as days')
                    , DB::raw(' case when adv.id = adv_home.advertisement_id  then '
                           . '  adv_home.advr_url '
                           . ' when adv.id = adv_cat.advertisement_id then '
                           . ' adv_cat.advr_url'
                           . ' when adv.id = adv_mingle.advertisement_id then '
                           . ' adv_mingle.advr_url end as ad_url')
                    ])
                ->leftjoin('avertisement_home as adv_home','adv.id','=','adv_home.advertisement_id')
                ->leftjoin('avertisement_cat as adv_cat','adv.id','=','adv_cat.advertisement_id')
                ->leftjoin('avertisement_mingle as adv_mingle','adv.id','=','adv_mingle.advertisement_id')
                ->leftjoin('files as adv_home_image', function($join) {
                    $join->on('adv_home_image.imageable_id', '=', 'adv_home.id')->where('adv_home_image.imageable_type', '=', 'App\Models\AdvertisementHome');
                  })
                ->leftjoin('files as adv_cat_image', function($join) {
                    $join->on('adv_cat_image.imageable_id', '=', 'adv_cat.id')->where('adv_cat_image.imageable_type', '=', 'App\Models\AdvertisementCategory');
                  })
                ->leftjoin('files as adv_mingle_image', function($join) {
                    $join->on('adv_mingle_image.imageable_id', '=', 'adv_mingle.id')->where('adv_mingle_image.imageable_type', '=', 'App\Models\AdvertisementMingle');
                  })

                ->WhereNull('adv.deleted_at')->where(['user_id' => loggedinUserId(),'adv.id' => $addID])->get();

        }
        
        return $result;
    }


    public static function getAdvertisement()
    {
        return $getAdvertisement = self::select('*')->where('user_id',\Auth::id())->get();
    }

    public static function updateAdvertisement($where,$data)
    {
        return self::where($where)->update($data);
    }

    public static function getAddDetails($where = [])
    {
        return self::select('location')->where($where)->get()->toArray();
    }

    /*public function getAdAllDetailsByID($id)
    {

    }*/

}