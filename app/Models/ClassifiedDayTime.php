<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class ClassifiedDayTime extends Model 
{

    protected $table    = 'classified_day_time';
    
    protected $fillable = ['id', 'classified_product_id', 'available_date', 'from_time', 'to_time','created_at','updated_at','deleted_at'];

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

}