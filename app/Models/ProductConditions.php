<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductConditions extends Model
{
    
    protected $fillable = [
        'category_id',
        'name',
        'description',
    ];

    public function saveProductCondition($request, $id = NULL) {                                        
        $request=$request->except('_token','_method');        
        if (isset($id) && !empty($id)) {                                   
            return $this->where('id', $id)->update($request);            
        }                  
        return $this->create($request);
    }
    public function getProductConditions($datatable = false, $id = NULL) {
        if ($datatable == true) {
            $data=$this->get();
            return getAuthorizedButton($data)->toJson();            
        } else if (isset($id) && !empty($id)) {
            $data=$this->where('id', $id)->first();            
            echo "<pre>";
            print_r($data);
            die;
            return $data;
        } else {
            return $this->get();
        }
    }
    public function deleteProductCondition($id) {        
        if (isset($id) && !empty($id)) {
            return $this->where('id', $id)->delete();
        }
        return false;
    }
    
    public function getProductConditionsByCategory($categoryId){
        return $this->whereIn('category_id', explode(",", $categoryId))->get();
    }
}
