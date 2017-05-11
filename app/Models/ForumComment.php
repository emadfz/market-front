<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumComment extends Model {

    protected $fillable = ['forum_id', 'user_id', 'comment', 'total_likes', 'total_dislikes'];

    public function Users() {

        return $this->hasOne('App\Models\User', 'id', 'user_id')->select(array('id', 'username','gender','image'));
    }
    
    public function Likes() {

        return $this->hasOne('App\Models\ForumLike', 'forum_id', 'id')->select(array('id','status','user_id','forum_id'))->where('type','=','comment')->where('user_id','=',\Auth::id());
    }
    
    public function ReportFlags() {

        return $this->hasOne('App\Models\ReportAbuse', 'ref_id', 'id')->select(array('id','ref_id','user_id','type'))->where('type','=','comment')->where('user_id','=',\Auth::id());
    }

    public function getCommentData($id,$skip=0,$take='') {
        return $this->select('*')->where('forum_id', '=', $id)->orderBy('id', 'DESC')->with('Users','Likes','ReportFlags')->skip($skip)->take($take)->get();
    }
    
    public function getCountCommentData($id) {
        return $this->select('*')->where('forum_id', '=', $id)->orderBy('id', 'DESC')->with('Users')->count();
    }

    public function incrementTypeComment($id, $status) {
        if ($status == 'like')
            $this->where('id', $id)->increment('total_likes');
        else if ($status == 'dislike')
            $this->where('id', $id)->increment('total_dislikes');
    }

    public function decrementTypeComment($id, $status) {
        if ($status == 'like')
            $this->where('id', $id)->decrement('total_likes');
        else if ($status == 'dislike')
            $this->where('id', $id)->decrement('total_dislikes');
    }

    public function getCommentTypeCount($id) {
        return $this->select('id', 'total_likes', 'total_dislikes')->where('id', '=', $id)->get();
    }

}
