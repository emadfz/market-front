<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MingleInvitation extends Model {

    use SoftDeletes;

    protected $fillable = ['inviation_id', 'user_id','status'];

    //
    public function getExistData($data) {
        $res = $this->select('id')->where('inviation_id', '=', $data['inviation_id'])->where('user_id', '=', $data['user_id'])->first();
        if ($res)
            return $res->toArray();
        else
            return '';
    }
    
    public function updateData($id,$data){
        return self::where('id', $id)->update($data);
    }

    public function addressBillingDetail() {
        return $this->hasOne('App\Models\UserAddress', 'id', 'user_id')->select(array('*'));
    }

    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->select(array('image', 'gender', 'username', 'id'));
    }
    
    public function sentAddressBillingDetail() {
        return $this->hasOne('App\Models\UserAddress', 'id', 'inviation_id')->select(array('*'));
    }
    
    public function sentUser() {
        return $this->hasOne('App\Models\User', 'id', 'inviation_id')->select(array('image', 'gender', 'username', 'id'));
    }

//    public function MingleFollowing() {
//
//        return $this->hasMany('App\Models\MingleFollowing', 'user_id', 'inviation_id')->select(array('id', 'user_id', 'following_id'))->where('user_id','=',\Auth::id());
//    }

    public function getAllUser($type, $skip = 0, $take = '') {

        //return $this->select('*')->where('status', '=', 'Active')->with('addressDetail.city.state.country')->skip($skip)->take($take)->get();
        if ($type == 'sent') {
            // return $this->select('*')
            return $this->select('mingle_invitations.*','mingle_followings.id as mingle_following_id','mingle_followings.user_id as mingle_following_user_id','mingle_followings.following_id as mingle_following_following_id','mingle_followers.following_id as mingle_followers_id')
                            ->where('mingle_invitations.user_id', '=', \Auth::id())
                            ->where('mingle_invitations.status', '!=', 'accept')
                            ->with(['sentAddressBillingDetail.city', 'sentAddressBillingDetail.state', 'sentAddressBillingDetail.country', 'sentUser'], function($query) {
                                $query->where('address_type', '=', 'Billing');
                            })
                            ->leftjoin('mingle_followings',function($q){
                                $q->on('mingle_followings.user_id','=','mingle_invitations.user_id');
                                $q->on('mingle_followings.following_id','=','mingle_invitations.inviation_id');
                                $q->whereNull('mingle_followings.deleted_at');
                            })
                            ->leftjoin('mingle_followings as mingle_followers',function($q){
                                $q->on('mingle_followers.user_id','=','mingle_invitations.inviation_id');
                                $q->on('mingle_followers.following_id','=','mingle_invitations.user_id');
                                $q->whereNull('mingle_followers.deleted_at');
                            })
                            ->skip($skip)
                            ->take($take)
                            ->get();
        }else if($type == 'pending'){
            
            return $this->select('mingle_invitations.*','mingle_followings.id as mingle_following_id','mingle_followings.user_id as mingle_following_user_id','mingle_followings.following_id as mingle_following_following_id','mingle_followers.following_id as mingle_followers_id')
                            ->where('mingle_invitations.inviation_id', '=', \Auth::id())
                            ->where('mingle_invitations.status', '=', $type)
                            //->with(['addressBillingDetail.city', 'addressBillingDetail.state', 'addressBillingDetail.country', 'user'], function($query) {
                            ->with(['sentAddressBillingDetail.city', 'sentAddressBillingDetail.state', 'sentAddressBillingDetail.country', 'sentUser'], function($query) {
                                $query->where('address_type', '=', 'Billing');
                            })
                            ->leftjoin('mingle_followings',function($q){
                                $q->on('mingle_followings.user_id','=','mingle_invitations.inviation_id');
                                $q->on('mingle_followings.following_id','=','mingle_invitations.user_id');
                                $q->whereNull('mingle_followings.deleted_at');
                            })
                            ->leftjoin('mingle_followings as mingle_followers',function($q){
                                $q->on('mingle_followers.user_id','=','mingle_invitations.user_id');
                                $q->on('mingle_followers.following_id','=','mingle_invitations.inviation_id');
                                $q->whereNull('mingle_followers.deleted_at');
                            })
                            ->skip($skip)
                            ->take($take)
                            ->get();
        }
        
        else {
            
            return $this->select('mingle_invitations.*','mingle_followings.id as mingle_following_id','mingle_followings.user_id as mingle_following_user_id','mingle_followings.following_id as mingle_following_following_id','mingle_followers.following_id as mingle_followers_id')
                            ->where('mingle_invitations.user_id', '=', \Auth::id())
                            ->where('mingle_invitations.status', '=', $type)
                            //->with(['addressBillingDetail.city', 'addressBillingDetail.state', 'addressBillingDetail.country', 'user'], function($query) {
                            ->with(['sentAddressBillingDetail.city', 'sentAddressBillingDetail.state', 'sentAddressBillingDetail.country', 'sentUser'], function($query) {
                                $query->where('address_type', '=', 'Billing');
                            })
                            ->leftjoin('mingle_followings',function($q){
                                $q->on('mingle_followings.user_id','=','mingle_invitations.user_id');
                                $q->on('mingle_followings.following_id','=','mingle_invitations.inviation_id');
                                $q->whereNull('mingle_followings.deleted_at');
                            })
                            ->leftjoin('mingle_followings as mingle_followers',function($q){
                                $q->on('mingle_followers.user_id','=','mingle_invitations.inviation_id');
                                $q->on('mingle_followers.following_id','=','mingle_invitations.user_id');
                                $q->whereNull('mingle_followers.deleted_at');
                            })
                            ->skip($skip)
                            ->take($take)
                            ->get();
        }
    }

    public function getAllUserCount($type) {
        return $this->select('id')->where('status', '=', $type)->where('inviation_id', '=', \Auth::id())->count();
    }
    
    public function getPendingUserCount($type) {
        return $this->select('id')->where('status', '=', $type)->where('inviation_id', '=', \Auth::id())->count();
    }

}
