<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class ClassifiedRelatedProduct extends Model 
{
    protected $table    = 'classified_related_product';
    
    protected $fillable = ['id', 'classified_product_id', 'related_products_id', 'type', 'created_at', 'updated_at', 'deleted_at'];

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