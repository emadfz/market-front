<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Baum\Node;

class Category extends Node {

    protected $table = 'category';
    // 'parent_id' column name
    protected $parentColumn = 'parent_id';
    // 'lft' column name
    protected $leftColumn = 'lft';
    // 'rgt' column name
    protected $rightColumn = 'rgt';
    // 'depth' column name
    protected $depthColumn = 'depth';
    // guard attributes from mass-assignment
    protected $guarded = array('id', 'parent_id', 'lft', 'rgt', 'depth');
    protected $orderColumn;

    public function Files() {
        return $this->morphMany('App\Models\Files', 'imageable');
    }

    public function AdvertisementCatMap() {

        return $this->hasMany('App\Models\AdvertisementCatMap', 'cat_id')
                        ->with('AdvertisementCategory')
                        ->whereHas('AdvertisementCategory', function($query) {
                            $query->where('status', '=', 1)
                            ->where('type', '=', 'Main_Box')
                            ->where('start_date', '<=', \DB::RAW('NOW()'));
                            //->where('end_date', '>=', \DB::RAW('NOW()'));
                        }
        );

//        return $this->hasMany('App\Models\AdvertisementCatMap', 'cat_id')
//                        ->with(['AdvertisementCategory' => function ($query) {
//                        $query->where('status', '=', 1)
//                              ->where('type', '=', 'Main_Box')                              
//                              ->where('start_date', '<=',\DB::RAW('NOW()'))
//                              ->where('end_date', '>=', \DB::RAW('NOW()'));
//                      }]);
    }

    public function getChildNode($parentId = '') {
        $node = $this->select('*')->where('text', 'Root category')->first();
        if (count($node->children) > 0) {
            $data = $node->getDescendantsAndSelf()->toHierarchy()->toArray();
            return $data;
        } else {
            return $node;
        }
    }

    public function getCategoriesByCharacter($ch = '') {
        return $this->where('text', 'like', $ch . '%')->where('id', '!=', 0)->orderby('text')->get();
    }
    public function getCategoriesBySlug($category_slug) {
        $node = $this->with('AdvertisementCatMap')->where('category_slug', $category_slug)->where('id', '!=', 0)->where('status', '=', "Active")->orderby('text')->first();
        //$node->children=$node->children()->get();                    
//            /dd($node);
        //dd($node->children[0]->children->toArray());
        return $node;
    }
    
    public function getCategoriesById($Id) {
        $node = $this->where('id', '=', $Id)
                ->orderby('text')
                ->first();
        return $node;
    }
    
    public function getChildNameid($parentId = '') {
        if (is_array($parentId)) {
            $node = $this->whereIn('id', implode(',', $parentId))->first();
        } else {
            $node = $this->where('id', $parentId)->first();
        }
        return $node->children()->get()->toArray();
    }

    public function products() {
        return $this->belongsToMany('App\Product')->where('status', '=', 'Active');
    }
    

    public function attributeSets() {
        return $this->belongsToMany('App\Models\AttributeSet','attribute_set_categories','attribute_set_categoryid')->with("Attributes");
        //return $this->belongsToMany('App\Product')->where('status', '=', 'Active');
        //return $this->hasMany('App\Models\AttributeSetCategory', 'attribute_set_categoryid', 'id')
                //->withPivot('attribute_set_id', 'attribute_set_categoryid')
                //->wherePivot('is_deleted', 0)
                //->with("Attributes");
    }
    
    public function getNestedData() {        
        //dd($this->getNestedList("text", null, "&nbsp;&nbsp;&nbsp;"));
        return $this->getNestedList("text", null, "&nbsp;&nbsp;&nbsp;");
    }

}
