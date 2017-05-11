<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MingleFollowing extends Model {
    
    use SoftDeletes;

    
    protected $fillable = ['following_id', 'user_id'];

    //
    public function getExistData($data) {
        $res = $this->select('id')->where('following_id', '=', $data['following_id'])->where('user_id', '=', $data['user_id'])->first();
        if ($res)
            return $res->toArray();
        else
            return '';
    }
    
     public function user() {
        return $this->belongsTo('App\Models\User');
    }
    
    public function followUser(){
        return $this->belongsTo('App\Models\User','following_id');
    }

}
