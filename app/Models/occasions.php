<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class occasions extends Model
{
    public function Files()
    {
      return $this->morphMany('App\Models\Files', 'imageable');
    }
    public function getOccasions($date=''){    	
    	$occasions=$this->where('status','Active')->with('Files');
        if(isset($date) && !empty($date)){
                $occasions->where('start_date','<=',$date)
                    ->where('end_date','>=',$date);
        }
        return $occasions->get();
    }
    public function getOccasionByName($occasionName){
        return $this->where('status','Active')->where('name',$occasionName)->with('Files')->first();
    }
}
