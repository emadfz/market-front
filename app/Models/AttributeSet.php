<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AttributeSet extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'attribute_sets';

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
    protected $fillable = ['attribute_set_name', 'attribute_set_description', 'is_visible', 'is_deleted'];

    public function Attributes() {
        return $this->hasMany('App\Models\Attribute', 'attribute_set_id', 'attribute_set_id')->with('AttributeValues');
    }
}
