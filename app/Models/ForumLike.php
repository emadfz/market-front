<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumLike extends Model {

    protected $fillable = ['forum_id', 'user_id', 'type', 'status'];

    //
    public function getExistData($data) {
        $res = $this->select('id', 'status')->where('forum_id', '=', $data['forum_id'])->where('user_id', '=', $data['user_id'])->where('type', '=', $data['type'])->first();
        if ($res)
            return $res->toArray();
        else
            return '';
    }

}
