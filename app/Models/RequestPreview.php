<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class RequestPreview extends Model 
{
    protected $table    = 'request_preview';
    
    protected $fillable = ['id', 'classified_product_id', 'preview_date', 'preview_from_time', 'preview_to_time','status','created_at','updated_at'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */

    /**
     * Scope a query to only include active status
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function user() 
    {
        return $this->belongsTo('App\Models\User');
    }

    public static function getCountClassifiedProduct ()
    {
       return self::where('status','Pending')->groupBy('user_id')->count();
    }

    public static function getRequestBuyerDetails($where)
    {
        return self::select('*')->with('user')->where($where)->get();
    }
}