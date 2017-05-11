<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
//use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Jenssegers\Mongodb\Model as Moloquent;


class MingleMessage extends Moloquent
{
  //   use HybridRelations;

    protected $connection = 'mongodb';    
    protected $collection = 'message';

    public static function getMessages($fromUserId,$toUserId){
        return self::orWhere(function($query) use($fromUserId,$toUserId){
            $query->where('fromUserId',$fromUserId)->where('toUserId',$toUserId);
        })
        ->orWhere(function($subquery) use($fromUserId,$toUserId){
            $subquery->where('fromUserId',$toUserId)->where('toUserId',$fromUserId);
        })
        ->orderBy('createdAt','asc')
        ->get();
    }
    
    public static function getUserMessagesCount($ids){
        return self::orWhere(function($query) use($ids)
            {
                $query->whereIn('fromUserId',$ids);
            })
            ->orWhere(function($query) use($ids)
            {
                $query->orwhereIn('toUserId',$ids);
            })
        ->get();
    }
    public static function getMessageUser($userId){
        return self::orWhere('fromUserId',$userId)->orWhere('toUserId',$userId)->orderBy('datetime','asc')->get();
    }
    
}
