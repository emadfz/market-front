<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AttributeSetCategory extends Model {
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'attribute_set_categories';
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
    protected $fillable = ['attribute_set_id', 'attribute_set_categoryid', 'is_deleted','created_at'];    
    
//    public function AttributeSets(){
//        return $this->hasMany('App\Models\AttributeSet','id','attribute_set_id')->with('Attributes');
//    }        
    public function Attributes(){        
        return $this->hasMany('App\Models\Attribute','attribute_set_id','attribute_set_id')->with('AttributeValues');
    }        
    public function getProductAttributes($categorieIdsForAttribute){
        return $this
//                    ->whereHas(['Attributes' => function($query) {
//                                $query->where('view_in_filter','1')->groupBy('id');
//                         }])
                         ->whereHas('Attributes',
                                function($query){
                                      $query->where('view_in_filter','1');
                                }
                         )
                    //->has('Attributes')
                    ->whereIn('attribute_set_categoryid',$categorieIdsForAttribute)
                    ->groupBy('attribute_set_id')
                    ->get();                         
    }
    
    public function getCategoryAttributes($categorieIdsForAttribute){
        return $this->with('Attributes')
                    ->has('Attributes')
                    ->whereIn('attribute_set_categoryid',$categorieIdsForAttribute)                    
                    ->get();
    }

}
