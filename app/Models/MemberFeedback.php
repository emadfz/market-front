<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Datatables;
use Carbon\Carbon;
use DB;

class MemberFeedback extends Model {
/**
* The database table used by the model.
*
* @var string
*/
// use SoftDeletes;
// protected $dates = ['deleted_at'];

protected $table = 'member_feedback';

/**
* The database primary key value.
*
* @var string
*/
protected $primaryKey = 'id';

/**
* Attributes that should be mass-assignable.
*
* @var array
*/
protected $fillable = [
    'sender_id',
    'receiver_id',
    'rating',
    'description',
    'status',
    'created_at',
    'updated_at',
];

}
