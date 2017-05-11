<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginHistoryUser extends Model {

    protected $table = 'login_history_users';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'email', 'attempts', 'status', 'login_from', 'ip_address', 'user_agent', 'created_at'];
    
     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * Store the login attempt in the database
     *
     * @param  Request $request, $status
     * @return Response
     */
    public static function saveAttemptRecord($request, $status) {
        $data = $request;
        $data['user_id'] = (isset($status) && $status == 'success') ? loggedinUserId() : 0;
        $data['status'] = $status;
        return self::create($data);
    }

}
