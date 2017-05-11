<?php

namespace App\Http\Controllers\Front;

use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\IndustryType;
use App\Models\SellerDetail;
use App\Models\UserAddress;
use App\Models\MingleFollowing;
use App\Models\MingleInvitation;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller {

    public function __construct() {
        $this->User = new User();
        $this->SellerDetail = new SellerDetail();
        $this->MingleFollowing = new MingleFollowing();
        $this->MingleInvitation = new MingleInvitation();
        $this->UserAddress = new UserAddress();
        $this->IndustryType = new IndustryType();
 

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $userID = Auth::id();
        
        $nameTitle = getMasterEntityOptions('name_title');
        $gender = getMasterEntityOptions('gender');
        $getUserData = DB::table('users')->Where('id', '=', $userID)->first();

        return view('front.profile.myprofile.index', compact('getUserData', 'nameTitle', 'gender', 'position'));
    }

    public function business() {
        $userID = Auth::id();
        $position = getMasterEntityOptions('position');
        $cardTypes = getMasterEntityOptions('payment_card_type');
        $industryTypes = IndustryType::getIndustryTypes('', true);
        $getUserData = SellerDetail::Where('user_id', '=', $userID)->first();

        return view('front.profile.myprofile.business', compact('position', 'cardTypes', 'industryTypes', 'getUserData'));
    }

    public function storeBusinessInfo(Request $request) {

        $this->validate($request, [
            'business_name' => 'required|max:50',
            'industry_type_id' => 'required',
            'tax_id_number' => 'required|max:50',
            'business_details' => 'required|max:500',
            'phone_number' => 'numeric', //|regex:/(01)[0-9]{9}/ //
            'website' => 'url|max:100',
            'position_id' => 'required',
            'position_other' => 'required_if:position_id,5',
            'file.0'=>'mimes:jpg,png,jpeg,gif',
            //'file.1'=>'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4,video/3gp|max:25000'
            'file.1'=>'mimes:avi,mpeg,quicktime,mp4,3gp|max:25000'
                ], ['position_id.required' => trans("profile.validation_custom.position"),
            'industry_type_id.required' => trans("profile.validation_custom.industry_type"),
            'position_other.required_if' => trans("profile.validation_custom.position_other"),
        ]);

        // Start transaction!
        DB::beginTransaction();
        try {
            
            $file ='';
            $flg = '';
            $update = array();
            if ($request->hasFile('file')) {
              
                if (isset($request->old_image) && !empty($request->old_image)) {
                    $flg = 'update';
                    foreach (array_keys($request->file('file')) as $key => $val){
                        if(array_key_exists($val, $request->old_image) && array_key_exists($val, $request->file_id)){
                            $update[$key]['image_name'] = $request->old_image[$val];
                            $update[$key]['id'] = $request->file_id[$val];
                        }
                    }
                    
                    //multiple file unlink logic
                    foreach ($update as $old_image){
                        if (isset($old_image['image_name']) && !empty($old_image['image_name'])) {
                            $moduleName = getCurrentModuleName();
                            $path = public_path() . '/images/' . $moduleName . '/';
                            $main = $path . '/main/' . $old_image['image_name'];
                            $small = $path . '/small/' . $old_image['image_name'];
                            $thumbnail = $path . '/thumbnail/' . $old_image['image_name'];
                            $documents = $path . '/documents/' . $old_image['image_name'];
                            $video = $path . '/video/' . $old_image['image_name'];
                            if (file_exists($main)) {
                                unlink($main);
                            }
                            if (file_exists($small)) {
                                unlink($small);
                            }
                            if (file_exists($thumbnail)) {
                                unlink($thumbnail);
                            }
                            if (file_exists($documents)) {
                                unlink($documents);
                            }
                            if (file_exists($video)) {
                                unlink($video);
                            }
                        }
                    }
        
                    //$file = uploadImage($request->file('file'),true,$request->old_image);
                    $file = uploadImage($request->file('file'),true);
                }
                else{
                    
                    $file = uploadImage($request->file('file'),true);
                }


            }
        
            $userID = Auth::id();
            $requestSellerDetail = $request->only([
                'business_name', 'industry_type_id', 'business_details',
                'tax_id_number', 'business_reg_number', 'business_phone_number',
                'website', 'position_id', 'position_other','video_link'
            ]);

            if ($requestSellerDetail['position_id'] != 5) {
                $requestSellerDetail['position_other'] = '';
            }
            
            //$userId = SellerDetail::updateSellerDetail(['user_id' => $userID], $requestSellerDetail);
            $userId=$this->SellerDetail->saveSellerDetail($requestSellerDetail,$userID,$file,$flg,$update);
            
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $e->getMessage()]]);
            //return response()->json(['status' => 'error', 'messages' => ['global_form_message' => trans('message.failure')]]);
        }

        // If we reach here, then data is valid and working. Commit the queries!
        DB::commit();
        \Flash::success(trans('profile.validation_custom.profile_business_update'));
        return ($request->ajax()) ? response()->json(['status' => 'success', 'redirectUrl' => route('getBusiness')]) : redirect()->route('getBusiness');
    }

    public function address() {
        $userID = Auth::id();
        $getUserBillingData = UserAddress::select('*')->Where('user_id', '=', $userID)->Where('address_type', '=', 'Billing')->with('user', 'country', 'state', 'city')->get();
        $getUserShippingData = UserAddress::select('*')->Where('user_id', '=', $userID)->Where('address_type', '=', 'Shipping')->with('user', 'country', 'state', 'city')->get();

        return view('front.profile.myprofile.address', compact('getUserBillingData', 'getUserShippingData'));
    }

    public function getEditAddressPopup($Id = '') {
        $getUserData = UserAddress::select('*')->Where('id', '=', $Id)->with('user', 'country', 'state', 'city')->first();
        $countries = getAllCountries()->pluck('country_name', 'id')->toArray();
        return view('front.profile.myprofile.partial.edit_address', compact('Id', 'countries', 'getUserData'));
    }

    public function storeEditAddress(Request $request) {

        $requestInput = $request->only('Id', 'address_1', 'address_2', 'country_id', 'postal_code', 'state_id', 'city_id');

        $this->validate($request, [
            'address_1' => 'required|max:100',
            'address_2' => 'required|max:100',
            'country_id' => 'required',
            'postal_code' => 'required|max:10',
            'state_id' => 'required',
            'city_id' => 'required'
                ], ['country_id.required' => trans("profile.validation_custom.country_id"),
            'state_id.required' => trans("profile.validation_custom.state_id"),
            'city_id.required' => trans("profile.validation_custom.city_id")]);

        // Start transaction!
        DB::beginTransaction();
        try {
            if (!empty($requestInput['Id'])) {
                $userId = UserAddress::updateUserAddress(['id' => $requestInput['Id']], $requestInput);
            } else {
                $requestInput['user_id'] = Auth::id();
                $requestInput['address_type'] = 'Shipping';
                $userId = UserAddress::create($requestInput);
            }
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $e->getMessage()]]);
            //return response()->json(['status' => 'error', 'messages' => ['global_form_message' => trans('message.failure')]]);
        }

        // If we reach here, then data is valid and working. Commit the queries!
        DB::commit();
        if (!empty($requestInput['Id'])) {
            \Flash::success(trans('profile.validation_custom.edit_address'));
            return ($request->ajax()) ? response()->json(['status' => 'success', 'redirectUrl' => route('getAddress')]) : redirect()->route('getAddress');
        } else {
            \Flash::success(trans('profile.validation_custom.add_address'));
            return ($request->ajax()) ? response()->json(['status' => 'success', 'redirectUrl' => route('getAddress')]) : redirect()->route('getAddress');
        }
    }
    
    public function deleteAddress($id, Request $request){
        $id = decrypt($id);

        try {
            $data = UserAddress::findOrFail($id)->delete();
            if ($request->ajax()) {
                return ($request->ajax()) ? response(['msg' => trans('profile.validation_custom.delete_success'), 'success' => 1, 'redirectUrl' => route('getAddress')]) : redirect()->route('getAddress');
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            return response(['msg' => $ex->errorInfo, 'success' => 0]);//$ex->getMessage()
            //return response(['msg' => trans('message.failure'), 'success' => 0]);
        }
    }

    public function followers() {
        $userId = Auth::id();
        $following = MingleFollowing::Where('user_id','=',$userId)->with('followUser')->get();
        $followingCount = MingleFollowing::Where('user_id','=',$userId)->with('followUser')->count();
        $followers = MingleFollowing::Where('following_id','=',$userId)->with('user')->get();
        $followersCount = MingleFollowing::Where('following_id','=',$userId)->with('user')->count();
       
        return view('front.profile.myprofile.followers', compact('following','followers','followingCount','followersCount'));
    }

    public function rating() {
        return view('front.profile.myprofile.rating', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('front.profile.myprofile.create', compact('', ''));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $requestInput = $request->only('title', 'first_name', 'last_name', 'gender', 'date_of_birth', 'phone_number', 'country_code');

        $this->validate($request, [
            'title' => 'required',
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'gender' => 'required',
            'date_of_birth' => 'required|date|before:-18 years',
            'phone_number' => 'required|numeric', //|regex:/(01)[0-9]{9}/ //
                ], ['date_of_birth.before' => trans("validation_custom.age_should_be_more_than_18_years")]);

        // Start transaction!
        DB::beginTransaction();
        try {

            $userID = Auth::id();
            $requestInput['phone_number'] = $requestInput['country_code'] != "" ? $requestInput['country_code'] . " " . $requestInput['phone_number'] : $requestInput['phone_number'];

            unset($requestInput['country_code']);
            $requestInput['date_of_birth'] = convertToDateFormat($requestInput['date_of_birth']);

            $userId = User::updateUser(['id' => $userID], $requestInput);
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $e->getMessage()]]);
            //return response()->json(['status' => 'error', 'messages' => ['global_form_message' => trans('message.failure')]]);
        }

        // If we reach here, then data is valid and working. Commit the queries!
        DB::commit();
        \Flash::success(trans('profile.validation_custom.profile_update'));
        return ($request->ajax()) ? response()->json(['status' => 'success', 'redirectUrl' => route('getProfile')]) : redirect()->route('getProfile');
    }

    public function storeImage(Request $request) {

        // Start transaction!
        DB::beginTransaction();
        try {
            $userID = Auth::id();

            //for unlink
            $documents = public_path() . '/assets/front/img/upload/' . $userID . '/*';
            $files = glob($documents); // get all file names
            foreach ($files as $file) { // iterate files
                if (is_file($file))
                    unlink($file); // delete file
            }
            //end

            $requestInput = $request->only('image');

            list($type, $requestInput['image']) = explode(';', $requestInput['image']);
            list(, $requestInput['image']) = explode(',', $requestInput['image']);

            $data = base64_decode($requestInput['image']);

            $imageName = time() . '.png';

            $path = public_path() . '/assets/front/img/upload/' . $userID . '/';

            if (!is_dir($path)) {
                mkdir($path);
                chmod($path, 0777);
            }

            file_put_contents($path . $imageName, $data);
            $userId = User::updateUser(['id' => $userID], array('image' => $imageName));
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $e->getMessage()]]);
            //return response()->json(['status' => 'error', 'messages' => ['global_form_message' => trans('message.failure')]]);
        }

        // If we reach here, then data is valid and working. Commit the queries!
        DB::commit();
        \Flash::success(trans('profile.validation_custom.profile_image_update'));
        return ($request->ajax()) ? response()->json(['status' => 'success', 'redirectUrl' => route('getProfile')]) : redirect()->route('getProfile');
    }

    public function changePassword($userId = '') {
        return view('front.profile.myprofile.partial.change_password', compact('userId'));
    }

    public function storeChangePassword(Request $request) {

        $requestInput = $request->only('user_id', 'current_password', 'new_password', 'confirm_new_password');

        \Validator::extend('checkcurrentpassword', function ($attribute, $value, $parameters) {
            return \Hash::check($value, Auth::user()->password);
        });

        $this->validate($request, [
            'current_password' => 'required|checkcurrentpassword',
            'new_password' => 'required|Between:7,14',
            'confirm_new_password' => 'required|same:new_password',
                ], array('checkcurrentpassword' => trans('profile.validation_custom.current_password_not_match')));

        try {

            // update password in users table
            User::updateUser(['id' => $requestInput['user_id']], ['password' => bcrypt($requestInput['new_password'])]);

            \Flash::success(trans('profile.validation_custom.password_reset_success'));
            return response()->json(['status' => 'success', 'redirectUrl' => route('getProfile')]);
        } catch (\Exception $e) {
            //return response()->json(['status' => 'error', 'messages' => ['global_form_message' => trans('message.failure')]]);
            return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $e->getMessage()]]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    public function sellerProfile($id)
    {
        $user           = User::where('id',$id)->first();
        $usersCount = $this->User->getAllUserCount();
 
        if(!empty($user) && $user->user_type == 'Business Seller')
        {
            $user_followers = $user->MingleFollowers;
            // foreach ($user_followers as $follower) {
            //     echo $follower->user->image;
            // }die;
            $user_details   = $user->sellerDetail;
            $feedbacks      = $user->membersFeedback;

            return view('front.seller_store.profile',compact('user', 'user_details','user_followers', 'feedbacks','usersCount'));
        }   
        else
        {
            return view('errors.404');
        }
    }


}
