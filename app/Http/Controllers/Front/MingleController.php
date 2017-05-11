<?php

namespace App\Http\Controllers\Front;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AdvertisementMingle;
use App\Models\MingleFollowing;
use App\Models\MingleInvitation;
use App\Models\UserAddress;
use App\Models\IndustryType;
use Illuminate\Support\Facades\DB;

class MingleController extends Controller {

    public function __construct() {
        $this->User = new User();
        $this->AdvertisementMingle = new AdvertisementMingle();
        $this->MingleFollowing = new MingleFollowing();
        $this->MingleInvitation = new MingleInvitation();
        $this->UserAddress = new UserAddress();
        $this->IndustryType = new IndustryType();
    }

    public function mingleSync() {
        $userId = Auth::id();
        $getUserData = UserAddress::select('*')->Where('user_id', '=', $userId)->Where('address_type', '=', 'Personal')->with('user', 'country', 'state', 'city')->first();
        $countries = getAllCountries()->pluck('country_name', 'id')->toArray();
        $hobbies = getAllHobbies()->pluck('name', 'id')->toArray();
        return view('front.mingle.sync', compact('Id', 'countries', 'hobbies', 'getUserData'));
    }

    public function searchMingle(Request $request, $page = 1, $limit = 12) {

        if ($limit > config('project.mingle_listing_limit')) {
            $limit = $limit;
        } elseif ($limit <= config('project.mingle_listing_limit')) {
            $limit = config('project.mingle_listing_limit');
        }

        //DB::enableQueryLog();
        $users = $this->User->getSearchUser(($page - 1) * $limit, $limit);
        //dd(DB::getQueryLog());
        // dd($users->toArray());
//        echo '<pre>';
//        print_r($users->toArray());
//        echo '</pre>';
//        exit;
        $usersCount = count($users->toArray());

        if ($usersCount > $limit) {
            $pageData = $page * $limit;
        } else {
            $pageData = $usersCount;
        }
        $totalPages = ceil($usersCount/$limit);
        
        if ($request->ajax()) {
            return response()->json([
                        'status' => 'success',
                        'listview_html' => view('front.mingle.partials.list_view', compact('users', 'usersCount'))->render(),
                        'gridview_html' => view('front.mingle.partials.grid_view', compact('users', 'usersCount'))->render(),
                        'nextPage' => $page + 1,
                        'previousPage' => $page - 1,
                        'pageData' => $pageData,
                        'usersCount'=>$usersCount,
                        'totalPages'=>$totalPages
            ]);
        }
    }

    public function storeMingleSync(Request $request) {

        $requestInput = $request->only('country_id', 'state_id', 'city_id');
        $requestHobbies = $request->only('hobbies');

        $this->validate($request, [
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'hobbies' => 'required',
            'agree_and_accept_terms_condition_and_privacy_policy' => 'accepted',
                ], ['country_id.required' => trans("mingle.validation_message.country_id"),
            'state_id.required' => trans("mingle.validation_message.state_id"),
            'city_id.required' => trans("mingle.validation_message.city_id")]);

        DB::beginTransaction();
        try {
            $requestInput['user_id'] = Auth::id();
            $requestInput['address_type'] = 'Personal';
            $Id = UserAddress::create($requestInput);

            //for pivot table
            $user = User::find(Auth::id());
            $user->hobbies()->sync($request->input('hobbies'));
            //end
            //for update in pivot table
            /* $user = User::find(Auth::id());
              $user->update($data);
              if (count($request->input('hobbies')) > 0) {
              $user->hobbies()->sync($request->input('hobbies'));
              } else {
              // no category set, detach all
              $user->hobbies()->detach();
              } */
            //end
            //change in db for sync mingle
            $Id = User::updateUser(['id' => Auth::id()], array('is_mingle_sync' => 1));
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $e->getMessage()]]);
            //return response()->json(['status' => 'error', 'messages' => ['global_form_message' => trans('message.failure')]]);
        }

        // If we reach here, then data is valid and working. Commit the queries!
        DB::commit();
        \Flash::success(trans('mingle.validation_message.mingle_sync'));
        return ($request->ajax()) ? response()->json(['status' => 'success', 'redirectUrl' => route('getConnect')]) : redirect()->route('getConnect');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $page = 1, $limit = 12) {

        $userId = Auth::id();
        $getUserData = UserAddress::select('*')->Where('user_id', '=', $userId)->Where('address_type', '=', 'Personal')->with('user', 'country', 'state', 'city')->first();
        $countries = getAllCountries()->pluck('country_name', 'id')->toArray();
        $hobbies = getAllHobbies()->pluck('name', 'id')->toArray();
        $industries = IndustryType::getIndustryTypes('', true);
        unset($industries['']);
        $pendingCount = $this->MingleInvitation->getPendingUserCount('pending');

        $advertisements = $this->AdvertisementMingle->getAdvertisements('Main_Box');

        if ($limit > config('project.mingle_listing_limit')) {
            $limit = $limit;
        } elseif ($limit <= config('project.mingle_listing_limit')) {
            $limit = config('project.mingle_listing_limit');
        }

        $users = $this->User->getAllUser(($page - 1) * $limit, $limit);

        $usersCount = $this->User->getAllUserCount();
        $usersOnSiteCount = $this->User->getAllUserOnSiteCount();
        if ($usersCount > $limit) {
            $pageData = $page * $limit;
        } else {
            $pageData = $usersCount;
        }
        
        $totalPages = ceil($usersCount/$limit);

        if ($request->ajax()) {
            return response()->json([
                        'status' => 'success',
                        'listview_html' => view('front.mingle.partials.list_view', compact('users', 'usersCount', 'pendingCount', 'advertisements', 'pageData', 'hobbies', 'countries', 'getUserData', 'industries', 'usersOnSiteCount','totalPages','page'))->render(),
                        'gridview_html' => view('front.mingle.partials.grid_view', compact('users', 'usersCount', 'pendingCount', 'advertisements', 'pageData', 'hobbies', 'countries', 'getUserData', 'industries', 'usersOnSiteCount','totalPages','page'))->render(),
                        'nextPage' => $page + 1,
                        'previousPage' => $page - 1,
                        'pageData' => $pageData,
                        'totalPages'=>$totalPages
            ]);
        }

        return view('front.mingle.index', compact('users', 'usersCount', 'pendingCount', 'advertisements', 'pageData', 'hobbies', 'countries', 'getUserData', 'industries', 'usersOnSiteCount','totalPages','page'));
    }

    public function type(Request $request, $type = '', $page = 1) {

        $userId = Auth::id();
        $getUserData = UserAddress::select('*')->Where('user_id', '=', $userId)->Where('address_type', '=', 'Personal')->with('user', 'country', 'state', 'city')->first();
        $countries = getAllCountries()->pluck('country_name', 'id')->toArray();
        $hobbies = getAllHobbies()->pluck('name', 'id')->toArray();
        $industries = IndustryType::getIndustryTypes('', true);
        unset($industries['']);
        $pendingCount = $this->MingleInvitation->getPendingUserCount('pending');

        //DB::enableQueryLog();
        $users = $this->MingleInvitation->getAllUser($type, ($page - 1) * config('project.mingle_listing_limit'), config('project.mingle_listing_limit'));                                
        $messages=\App\Models\MingleMessage::getUserMessagesCount($users->pluck('inviation_id'));        
        //$users = $this->User->getAllUserByType($type, ($page - 1) * config('project.mingle_listing_limit'), config('project.mingle_listing_limit'));

        $usersCount = $this->MingleInvitation->getAllUserCount($type);
        //$usersCount = $this->User->getAllUserByTypeCount($type);
        if ($usersCount > config('project.mingle_listing_limit')) {
            $pageData = $page * config('project.mingle_listing_limit');
        } else {
            $pageData = $usersCount;
        }

        if ($request->ajax()) {
            return response()->json([
                        'status' => 'success',
                        'html' => view('front.mingle.partials.type_right', compact('users', 'usersCount', 'pendingCount', 'type', 'hobbies', 'countries', 'getUserData', 'industries','messages'))->render(),
                        'nextPage' => $page + 1,
                        'previousPage' => $page - 1,
            ]);
        }

        return view('front.mingle.type', compact('users', 'usersCount', 'pendingCount', 'type', 'hobbies', 'countries', 'getUserData', 'industries','messages'));
    }

    public function mingleFollow(Request $request) {
        $data = $request->only('following_id', 'following_type');

        $saveData = array();
        $saveData['following_id'] = $data['following_id'];
        $saveData['user_id'] = Auth::id();

        $checkExist = $this->MingleFollowing->getExistData($saveData);

        if (!$checkExist && $data['following_type'] == 'following') {
            $id = $this->MingleFollowing->create($saveData);
        } else {
            $this->MingleFollowing->where('following_id', $data['following_id'])->where('user_id', Auth::id())->delete();
        }

        return JsonResponse::create(array('success' => 1));
    }
    
    public function mingleUnFollow(Request $request) {

        $data = $request->only('following_id', 'following_type');
        
        $id = $data['following_id'];
        $this->MingleFollowing->where('id', $id)->delete();
                
        //for profile
        $userId = Auth::id();
        $following = MingleFollowing::Where('user_id','=',$userId)->with('followUser')->get();
        $followingCount = MingleFollowing::Where('user_id','=',$userId)->with('followUser')->count();
        $followers = MingleFollowing::Where('following_id','=',$userId)->with('user')->get();
        $followersCount = MingleFollowing::Where('following_id','=',$userId)->with('user')->count();
        //end
        
        if ($request->ajax()) {
            return response()->json([
                        'status' => 'success',
                        'html' => view('front.profile.myprofile.partial._formfollowers', compact('following','followers','followingCount','followersCount'))->render(),
            ]);
        }
        //return JsonResponse::create(array('success' => 1));
    }

    public function mingleInvitation(Request $request) {

        $data = $request->only('inviation_id', 'inviation_type');

        $saveData = array();
        $saveData['inviation_id'] = $data['inviation_id'];
        $saveData['user_id'] = Auth::id();
        $saveData['status'] = $data['inviation_type'];

        $checkExist = $this->MingleInvitation->getExistData($saveData);

        if (!$checkExist) {
            $id = $this->MingleInvitation->create($saveData);
        } else {
            //$this->MingleFollowing->where('inviation_id', $data['inviation_id'])->where('user_id', Auth::id())->delete();
        }

        return JsonResponse::create(array('success' => 1));
    }

    public function mingleStatus(Request $request, $page = 1) {
        $data = $request->only('id', 'status', 'type');

        $id = $data['id'];
        $type = $data['type'];

        if ($data['status'] == 'accept') {

            $fecthData = $this->MingleInvitation->find($id);

            if ($fecthData->status == 'pending') {
                $addData = array();
                $addData['user_id'] = $fecthData->inviation_id;
                $addData['inviation_id'] = $fecthData->user_id;
                $addData['status'] = 'accept';

                $addedId = $this->MingleInvitation->create($addData);
            }
        }
        
        $saveData = array();
        $saveData['status'] = $data['status'];
        $updated_id = $this->MingleInvitation->updateData($id, $saveData);
        
        $users = $this->MingleInvitation->getAllUser($type, ($page - 1) * config('project.mingle_listing_limit'), config('project.mingle_listing_limit'));

        $usersCount = $this->MingleInvitation->getAllUserCount($type);

        if ($usersCount > config('project.mingle_listing_limit')) {
            $pageData = $page * config('project.mingle_listing_limit');
        } else {
            $pageData = $usersCount;
        }


        return response()->json([
                    'status' => 'success',
                    'html' => view('front.mingle.partials.type_right', compact('users', 'usersCount', 'type'))->render(),
                    'nextPage' => $page + 1,
                    'previousPage' => $page - 1,
        ]);
    }

    
    public function messages($touser=''){     
        
        $sentUser=array();
        $userId = Auth::id();
        if( isset($touser) && !empty($touser) ){
            if(decrypt($touser)==$userId){
                return redirect('/mingle/messages');
            }
            $sentUser=User::where('id',decrypt($touser))->with('MingleFollowing','MingleFollower')->first();                        
            $touser=decrypt($touser);
            $messages=\App\Models\MingleMessage::getMessages($userId,$touser); 
            
            $unReadMessages=$messages->where('is_read','No');
            //dd($unReadMessages);
            $unReadMessagesIds=array_column($unReadMessages->toArray(),'_id');            
            \App\Models\MingleMessage::whereIn('_id',$unReadMessagesIds)->update(['is_read'=>'Yes']); 
            
            //\App\Models\MingleMessage::get();
            
            $countries = getAllCountries()->pluck('country_name', 'id')->toArray();
            $hobbies = getAllHobbies()->pluck('name', 'id')->toArray();
            $industries = IndustryType::getIndustryTypes('', true);
            unset($industries['']);
            $pendingCount = $this->MingleInvitation->getPendingUserCount('pending');                                                            
            return view('front.mingle.messages',compact('pendingCount', 'hobbies', 'countries',  'industries','sentUser','messages'));
            
        }                    
        else{
            $messages=\App\Models\MingleMessage::getMessageUser($userId);
            $allUsers=$messages->pluck('fromUserId');
            $allUsers=$allUsers->merge($messages->pluck('toUserId'))->unique();            
            $allUsers->search(function ($item, $key) use($allUsers,$userId) {
                if($item == $userId){                    
                    unset($allUsers[$key]);
                }
            });            
            
            $allUsers=User::whereIn('id',$allUsers)->get();
            
            
            $countries = getAllCountries()->pluck('country_name', 'id')->toArray();
            $hobbies = getAllHobbies()->pluck('name', 'id')->toArray();
            $industries = IndustryType::getIndustryTypes('', true);
            unset($industries['']);
            $pendingCount = $this->MingleInvitation->getPendingUserCount('pending');
            return view('front.mingle.messages',compact('pendingCount', 'hobbies', 'countries',  'industries','sentUser','messages','allUsers'));
            
        }
        
        
        
        
        
        
        
    }

}
