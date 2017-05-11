<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Attribute extends Model {
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'attributes';
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
    protected $fillable = ['attribute_name', 'attribute_description', 'is_visible','attribute_set_id', 'catelog_input_type','view_in_filter','comparable','is_deleted'];

    public function AttributeValues(){
        return $this->hasMany('App\Models\AttributeValues');
    }
    public function AttributeSet(){
        return $this->belongsTo('App\Models\AttributeSet','attribute_set_id','id');
    }
}
