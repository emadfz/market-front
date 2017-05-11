<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {      
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    #protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    //protected $guarded = ['is_email_verified', 'is_phone_verified', 'facebook_id', 'google_id', 'twitter_id', 'linkedin_id'];
    protected $guarded = ['country_code', 'secret_question', 'confirm_password', 'agree_and_accept_terms_condition_and_privacy_policy'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        /* 'password', */ 'remember_token',
    ];

    public static function createUser($request) {
        $data = $request;
        $data['password'] = bcrypt($request['password']);
        $data['secret_question_id'] = $request['secret_question'];
        $data['date_of_birth'] = convertToDateFormat($request['date_of_birth']);
        $data['status'] = 'Pending';
        $data['user_type'] = $request['user_type'];
        unset($data['secret_question']);
        return self::create($data);
    }

    public static function checkActivationCode($activationCode) {
        $result = self::select('id', 'is_email_verified', 'status')->where(['activation_code' => $activationCode])->first();
        return (!is_null($result)) ? $result : FALSE;
    }

    public static function activateUser($userId) {
        return self::where(['id' => $userId])->update(['is_email_verified' => 1, 'status' => 'Active', 'activation_datetime' => getCurrentDatetime()]);
    }

    public static function checkUserData($where) {
        $result = self::select('id', 'first_name', 'last_name', 'date_of_birth', 'is_email_verified', 'username', 'email', 'password', 'status','image')->where($where)->first();
        return (!is_null($result)) ? $result->toArray() : FALSE;
    }

    public static function updateUser($where, $data) {
        return self::where($where)->update($data);
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    /* public function getAuthPassword()
      {
      return $this->Password;
      } */

    /**
     * Get the seller detail record associated with the user.
     */
    public function sellerDetail() {
        return $this->hasOne('App\Models\SellerDetail','user_id' , 'id'); //->select('user_id', 'business_name');
    }

    /**
     * Get the address detail record associated with the user.
     */
    public function addressDetail() {
        return $this->hasMany('App\Models\UserAddress');
    }  
     
    public function membersFeedback() {
        return $this->hasMany('App\Models\MemberFeedback', 'receiver_id', 'id');
    }

    /**
     * Get the address detail record associated with the user.
     */
    public function addressBillingDetail() {
        return $this->hasOne('App\Models\UserAddress');
    }

    
    public function MingleFollowing() {

        return $this->hasOne('App\Models\MingleFollowing', 'following_id', 'id')->select(array('id', 'user_id', 'following_id'))->where('user_id', '=', \Auth::id())->whereNull('deleted_at');
    }
    public function MingleFollower() {
     
 
      return $this->hasOne('App\Models\MingleFollowing', 'user_id', 'id')->select(array('id', 'user_id', 'following_id'))
      ->where('following_id', '=', \Auth::id())
      ->whereNull('deleted_at');
    }
    public function MingleFollowers()
    {
      return $this->hasMany('App\Models\MingleFollowing', 'following_id', 'id')
      ->whereNull('deleted_at');
    }

    public function MingleInvitation() {

        return $this->hasOne('App\Models\MingleInvitation', 'inviation_id', 'id')->select(array('id', 'user_id', 'inviation_id'))->where('user_id', '=', \Auth::id());
    }

    /**
     * Get the payment card detail record associated with the user.
     */
    public function paymentCardDetail() {
        return $this->hasMany('App\Models\UserPaymentCardDetail');
    }

    public function getAllUser($skip = 0, $take = '') {

        //return $this->select('*')->where('status', '=', 'Active')->with('addressDetail.city.state.country')->skip($skip)->take($take)->get();

        return $this->select('*')
                        ->where('status', '=', 'Active')
                        ->where('is_mingle_sync', '=', 1)
                        ->where('id', '!=', \Auth::id())
                        ->with(['addressBillingDetail.city', 'addressBillingDetail.state', 'addressBillingDetail.country', 'MingleFollowing', 'MingleInvitation'], function($query) {
                            $query->where('address_type', '=', 'Billing');
                        })
                        ->skip($skip)
                        ->take($take)
                        ->get();
    }

    public function getAllUserCount() {
        return $this->select('id')->where('status', '=', 'Active')->where('is_mingle_sync', '=', 1)->where('id', '!=', \Auth::id())->count();
    }
    
    public function getAllUserOnSiteCount(){
        return $this->select('id')->where('status', '=', 'Active')->count();
    }

    //for pivot table
    public function hobbies() {
        return $this->belongsToMany('App\Models\Hobby');
    }

    //end

    public function addressPersonalDetail() {

        $return = $this->hasOne('App\Models\UserAddress')->where('address_type', '=', 'Personal');
        if (!empty(request()->country_id))
            $return->where('country_id', '=', request()->country_id);
        if (!empty(request()->state_id))
            $return->where('state_id', '=', request()->state_id);
        if (!empty(request()->city_id))
            $return->where('city_id', '=', request()->city_id);

        $return->with(['country', 'state', 'city']);

        return $return;
    }

    public function hobbiesDetails() {
        return $this->belongsToMany('App\Models\Hobby', 'hobby_user');
    }

    public function SellerDetails() {
        return $this->hasOne('App\Models\SellerDetail');
    }

    public function getSearchUser($skip = 0, $take = '') {
        
        $return = $this->select('*')
                ->where('status', '=', 'Active')
                ->where('is_mingle_sync', '=', 1)
                ->where('id', '!=', \Auth::id())
                ->has('addressPersonalDetail');
        if (!empty(request()->hobbies)) {
            $return->whereHas('hobbiesDetails', function ($q) {
                $q->whereIn('hobby_id', request()->hobbies);
            });
        }
        if (!empty(request()->industries)) {
            $return->whereHas('SellerDetails', function ($q) {
                $q->whereIn('industry_type_id', request()->industries);
            });
        }
        
        if (!empty(request()->ages)) {
           
           $return->where(function($q){
               foreach (request()->ages as $age){
                   $q->orWhereBetween(\DB::Raw("TIMESTAMPDIFF( YEAR, date_of_birth , CURDATE( ) )"),  explode('-', $age));
                }
           });
           
        }
        
        if(!empty(request()->search)){
            $return->where(function($q){
                   $q->where('username', 'like','%'.request()->search.'%');
                   $q->orWhere('email', 'like','%'.request()->search.'%');
           });
        }
        
        $return->with(['addressBillingDetail.city', 'addressBillingDetail.state', 'addressBillingDetail.country', 'MingleFollowing', 'MingleInvitation'])
                ->skip($skip)
                ->take($take);

        if (!empty(request()->withphoto))
            $return->where('image', '<>', '', 'and'); // is not null

        return $return->get();
    }

    public function getSearchUserCount($skip = 0, $take = '') {
        $return = $this->select('*')
                ->where('status', '=', 'Active')
                ->where('is_mingle_sync', '=', 1)
                ->where('id', '!=', \Auth::id())
                ->has('addressPersonalDetail');

        if (!empty(request()->hobbies)) {
            $return->whereHas('hobbiesDetails', function ($q) {
                $q->whereIn('hobby_id', request()->hobbies);
            });
        }

        if (!empty(request()->industries)) {
            $return->whereHas('SellerDetails', function ($q) {
                $q->whereIn('industry_type_id', request()->industries);
            });
        }
        
        if (!empty(request()->ages)) {
           $return->where(function($q){
               foreach (request()->ages as $age){
                   $q->orWhereBetween(\DB::Raw("TIMESTAMPDIFF( YEAR, date_of_birth , CURDATE( ) )"),  explode('-', $age));
                }
           });
        }
        
        if(!empty(request()->search)){
           $return->where(function($q){
                   $q->where('username', 'like','%'.request()->search.'%');
                   $q->orWhere('email', 'like','%'.request()->search.'%');
           });
        }
        
        $return->with(['addressBillingDetail.city', 'addressBillingDetail.state', 'addressBillingDetail.country', 'MingleFollowing', 'MingleInvitation'])
                ->skip($skip)
                ->take($take);

        if (!empty(request()->withphoto))
            $return->where('image', '<>', '', 'and'); // is not null

        return $return->count();
    }
}
