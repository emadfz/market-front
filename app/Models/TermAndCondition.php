<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TermAndCondition extends Model {

    protected $table = 'term_and_conditions';
    protected $fillable = ['topic_name', 'slug', 'terms_conditions', 'meta_title', 'meta_keywords', 'meta_description', 'status', 'admin_user_id', 'created_at', 'updated_at'];
    public $timestamps = false;
    
    public function getTermCondition($id=''){
        if(isset($id) && $id != ''){
            return $this->where('id',$id)->first()->toArray();
        }
        return $this->get()->toArray();
    }
    
    public function createTermCondition($request){
        $data = $request->all();
        $data['slug'] = str_slug($request->get('topic_name'));
        $data['admin_user_id'] = auth()->guard('admin')->user()->id;
        $data['created_at'] = Carbon::now();
        return $this->create($data)->id;
    }
    
    public function updateTermCondition($request, $id){
        $data = $request;
        $data['updated_at'] = Carbon::now();
        return $this->where('id', $id)->update($data);
    }
}
