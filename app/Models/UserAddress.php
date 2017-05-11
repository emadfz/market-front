<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model {

    use SoftDeletes;

    protected $guarded = [''];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public static function createAddress($request) {
        $data = $request;
        return self::create($data);
    }

    /**
     * Get the user that owns the address detail.
     */
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function country() {
        return $this->hasOne('App\Models\Country', 'id', 'country_id');
    }

    public function state() {
        return $this->hasOne('App\Models\State', 'id', 'state_id');
    }

    public function city() {
        return $this->hasOne('App\Models\City', 'id', 'city_id');
    }

    public static function updateUserAddress($where, $data) {
        return self::where($where)->update($data);
    }

    public static function getUserAddress($where = []) {
        return self::where(['user_id' => loggedinUserId()]+$where)->first();
    }

}
