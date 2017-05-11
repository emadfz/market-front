<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPasswordReset extends Model {

    protected $fillable = [
        'user_id', 'email', 'token', 'is_used', 'created_at', 'updated_at'
    ];

    public static function updateUserPasswordReset($request, $token) {
        $data = $request;
        return self::where('token', $token)->update($data);
    }

    public static function checkTokenExists($token) {
        return self::where(['token' => $token])->first();
    }

}
