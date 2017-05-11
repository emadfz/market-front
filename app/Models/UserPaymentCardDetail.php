<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPaymentCardDetail extends Model {

    use SoftDeletes;

    protected $guarded = [''];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public static function createPaymentCard($request) {
        $data = $request;
        $data['card_number'] = '****';
        return self::create($data);
    }

    /**
     * Get the user that owns the payment card detail.
     */
    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
