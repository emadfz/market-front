<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model {

    protected $fillable = ['topic_name', 'topic_department_id', 'admin_users_id', 'status'];

    public function Users() {

        return $this->hasOne('App\Models\User', 'id', 'admin_users_id')->select(array('id', 'username', 'created_at','image','gender'));
    }
    
    public function Likes() {

        return $this->hasOne('App\Models\ForumLike', 'forum_id', 'id')->select(array('id','status','user_id','forum_id'))->where('type','=','topic')->where('user_id','=',\Auth::id());
    }
    
    public function ReportFlags() {

        return $this->hasOne('App\Models\ReportAbuse', 'ref_id', 'id')->select(array('id','ref_id','user_id','type'))->where('type','=','topic')->where('user_id','=',\Auth::id());
    }

    //
    public function getTopicsByCharacter($ch = '', $id = '') {

        if (!empty($id))
            return $this->select('id', 'topic_name', 'topic_department_id')->where('topic_name', 'like', $ch . '%')->where('topic_department_id', '=', $id)->where('status', '=', 'Active')->get();
        else
            return $this->select('id', 'topic_name', 'topic_department_id')->where('topic_name', 'like', $ch . '%')->where('status', '=', 'Active')->get();
    }

    public function getTopicsCount($id = '') {
        if (!empty($id))
            return $this->select('id')->where('topic_department_id', '=', $id)->where('status', '=', 'Active')->get();
        else
            return $this->select('id')->where('status', '=', 'Active')->get();
    }

    public function getTopics($id,$skip=0,$take='') {
        return $this->select('*')->where('topic_department_id', '=', $id)->where('status', '=', 'Active')->with('Users','Likes','ReportFlags')->skip($skip)->take($take)->get();
    }

    public function incrementTypeTopic($id, $status) {
        if ($status == 'like')
            $this->where('id', $id)->increment('total_likes');
        else if ($status == 'dislike')
            $this->where('id', $id)->increment('total_dislikes');
        else if($status == 'total_comments')
            $this->where('id', $id)->increment('total_comments');
    }

    public function decrementTypeTopic($id, $status) {
        if ($status == 'like')
            $this->where('id', $id)->decrement('total_likes');
        else if ($status == 'dislike')
            $this->where('id', $id)->decrement('total_dislikes');
    }

    public function getTopicTypeCount($id) {
        return $this->select('id', 'total_likes', 'total_dislikes')->where('id', '=', $id)->get();
    }

    public function getTopicData($id) {
        return $this->select('*')->where('id', '=', $id)->with('Users','Likes','ReportFlags')->first();
    }
    
    public function getAllTopics($skip=0,$take=''){
        return $this->select('*')->where('status', '=', 'Active')->orderBy('total_comments', 'DESC')->with('Users','Likes','ReportFlags')->skip($skip)->take($take)->get();
    }
    
    public function getAllPopularTopics($skip=0,$take=''){
        return $this->select('*')->where('status', '=', 'Active')->with('Users','Likes','ReportFlags')->skip($skip)->take($take)->get();
    }

}
