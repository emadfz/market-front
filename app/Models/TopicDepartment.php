<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TopicDepartment extends Model {

    use SoftDeletes;
    
    public function Files()
    {
        
              return $this->hasOne('App\Models\Files','imageable_id','id')->where('imageable_type','App\Models\EmployeeDepartments');
    }
    
    
    //
    public function getDepartment() {
        //return $this->pluck('department_name', 'id','topics')->all();
        return $this->select('department_name', 'id', 'topics','color')->with('Files')->get();
    }
    
    public function getDepartmentName($id = NULL){
        return $this->select('id', 'department_name')->where('id', '=', $id)->first();
    }

    public function getDepartmentNames($id = null) {
        return $this->pluck('department_name', 'id')->all();
    }

    public function incrementDepartmentTopic($id) {
        $this->where('id', $id)->increment('topics');
    }

}
