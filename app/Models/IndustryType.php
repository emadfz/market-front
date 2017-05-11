<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndustryType extends Model {

    use SoftDeletes;

    protected $guarded = [''];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public static function getIndustryTypes($id = '', $dropdown = false) {
        if (isset($id) && $id != '') {
            return self::where('id', $id)->first()->toArray();
        }

        if ($dropdown) {
            return ['' => trans('form.common.select_industry_type')] + self::pluck('title', 'id')->all();
        }

        return self::get()->toArray();
    }
    
    /**
     * Get the seller detail that owns the industry type.
     */
    public function sellerDetail() {
        return $this->belongsToMany('App\Models\SellerDetail');
    }

}
