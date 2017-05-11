<?php

namespace App\Http\Controllers\Front;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Hash;
use Mail;
use Datatables;
use DB;
use App\Models\Advertisement;
use App\Models\AdvertisementCatMap;
use App\Models\AdvertisementHome;
use App\Models\AdvertisementCategory;
use App\Models\AdvertisementMingle;
use App\Models\AdLog;
use App\Models\Files;
use App\Models\Category;

class AdvertisementController extends Controller
{
    public function __construct()
    {
        $this->Advertisement_obj 			= new Advertisement();
        $this->AdvertisementHome_obj 		= new AdvertisementHome();
        $this->AdvertisementCategory_obj 	= new AdvertisementCategory();
        $this->AdvertisementMingle_obj 		= new AdvertisementMingle();
        $this->AdvertisementCatMap_obj 		= new AdvertisementCatMap();
        $this->Files_obj                    = new Files();
    }
    
    public function index($id='')
    {
        if( isset($id)  && !empty($id)){
            $log = AdLog::where('ad_id', $id)->get();
            
            $ad = Advertisement::find($id);
 
                return view('front.buy.advertisement.details', compact('ad', 'log'))->render();
        }
        else{
                return view('front.buy.advertisement.index');
        }
        
    }
    
    public function datatableList(Request $request) {
               
        $advertisement = $this->Advertisement_obj->getalladvertisement();
        //dd($advertisement);
        return Datatables::of($advertisement)
            ->addColumn('action', function ($add) {
                
                $extra_column = '';

                if($add->status == 3)
                {
                    $extra_column .= '<a href="javascript:void(0)" onclick="pause_add('.$add->id.',\'remove\');" class="table-link" data-toggle="tooltip" data-placement="top">Remove Ad</a>';
                    $extra_column .= '<a href="javascript:void(0)" onclick="pause_add('.$add->id.',\'pause\');"  class="table-link" data-toggle="tooltip" data-placement="top">Pauese</a>';
                    $extra_column .= '<a href="javascript:void(0)" onclick="pause_add('.$add->id.',\'renew\');" class="table-link" data-toggle="tooltip" data-placement="top">Renew Ad</a>';
                   
                }else if($add->status == 1)
                {
                    $extra_column .= '<a href="javascript:void(0)" onclick="pause_add('.$add->id.',\'pause\');"  class="table-link" data-toggle="tooltip" data-placement="top">Pauese</a>';
                    $extra_column .= '<a href="javascript:void(0)" onclick="pause_add('.$add->id.',\'renew\');" class="table-link" data-toggle="tooltip" data-placement="top">Renew Ad</a>';
                    $extra_column .= '<a href="javascript:void(0)" onclick="pause_add('.$add->id.',\'remove\');" class="table-link" data-toggle="tooltip" data-placement="top">Remove Ad</a>';

                }else if($add->status == 2)
                {
                    $extra_column .= '<a href="javascript:void(0)" onclick="pause_add('.$add->id.',\'resume\');" class="table-link" data-toggle="tooltip" data-placement="top">Resume</a>';
                }else if($add->status == 0)
                {
                    $extra_column .= '<a href="'.route('editAd',[encrypt($add->id)]).'" class="table-link" data-toggle="tooltip" data-placement="top">Edit</a>';
                }

                if(!empty($add->home_image) || !empty($add->cat_image) || !empty($add->min_image) )
                {
                    $extra_column .= '<a href="javascript:void(0)" onclick="ad_preview('.$add->id.',\''.$add->type.'\')" class="table-link preview_ad" data-toggle="modal" data-placement="top" title="View Ad">View Ad</a>';
                }
                 
                return $extra_column;
            })
            ->addColumn('paid', function ($add) {
                $paid = '';
                return $paid;
            })
            ->addColumn('id', function ($add) {
                $id = '<a data-toggle="modal" href="'.route('advertisements', $add->id).'" class="advertisement btn btn-link" data-target="#advertisement_detail_modal">'.$add->id.'</a>';
                return $id;
            })
            ->addColumn('add_status', function ($add) {
                $add_status = 'Pending';
                if($add->status == 1)
                {
                    $add_status = 'Approved';
                }
                elseif($add->status == 2)
                {
                    $add_status = 'Paused';
                } elseif($add->status == 3)
                {
                    $add_status = 'Resume';
                }
                return $add_status;
            })
            ->addColumn('add_name', function ($add) {
                $add_name = $add->advr_name;
                if( $add->location == 'Home' )
                {
                    $add_name = '<div class="thumbbox-table"><img id="image_'.$add->id.'" full_image_path ="'.getImageFullPath($add->home_image,'buyer_purchase_ads/'.$add->user_id,'small').'" width="46" height="46" src="'.getImageFullPath($add->home_image,'buyer_purchase_ads/'.$add->user_id,'thumbnail').'"/></div><span>'.' '.$add->advr_name.'</span>';
                }
                if( $add->location == 'Category' || $add->location == 'SubCategory' )
                {
                    $add_name = '<div class="thumbbox-table"><img id="image_'.$add->id.'" full_image_path ="'.getImageFullPath($add->cat_image,'buyer_purchase_ads/'.$add->user_id,'small').'" width="46" height="46" src="'.getImageFullPath($add->cat_image,'buyer_purchase_ads/'.$add->user_id,'thumbnail').'"/></div><span>'.' '.$add->advr_name.'</span>';
                }
                if( $add->location == 'Mingle' )
                {
                    $add_name = '<div class="thumbbox-table"><img id="image_'.$add->id.'" full_image_path ="'.getImageFullPath($add->min_image,'buyer_purchase_ads/'.$add->user_id,'small').'" width="46" height="46" src="'.getImageFullPath($add->min_image,'buyer_purchase_ads/'.$add->user_id,'thumbnail').'"/></div><span>'.' '.$add->advr_name.'</span>';
                }
                return $add_name;
            })     
            ->make(true);
    }

    public function changeAddStatus(Request $request)
    {
        $pauseData = $request->all();
        
        try{    
                $addId                  = $pauseData['id'];
                $where                  = ['id' => $addId];
                
                $old_log = AdLog::where('ad_id', $addId)->orderBy('id', 'desc')->first();

                $log = new AdLog;
                $log->ad_id = $addId;
                $log->old_status =$old_log->new_status;


 
                
                if( $pauseData['type'] == 'pause' )
                {
                    $data['status']         = 2;
                    $updateData['status']   = 3;
                    $log->new_status = 2;
                    $log->save();

                }else if( $pauseData['type'] == 'resume' )
                {
                    $data['status']         = 3;
                    $updateData['status']   = 3;
                    
                    $log->new_status = 3;
                    $log->save();

                }
                else if( $pauseData['type'] == 'remove' )
                {
                    $data['status']             = 4;
                    $data['deleted_at']         = date('Y-m-d H:i:s');
                    $updateData['status']       = 4;
                    $updateData['deleted_at']   = date('Y-m-d H:i:s');
                    
                    $log->new_status = 4;
                    $log->save();
                }

                $pausethisAdd           = Advertisement::updateAdvertisement($where,$data);
                /* Get Location */
                $location               = Advertisement::getAddDetails($where);
                
                $childWhere             = ['advertisement_id' => $addId];
                
                if( isset($location[0]['location']) && strtolower($location[0]['location']) == "home" )
                {
                    $pauseChildAdd     = AdvertisementHome::updateAdvertisementHome($childWhere,$updateData);
                }else if(  isset($location[0]['location']) && strtolower($location[0]['location']) == 'category'  )
                {
                    $pauseChildAdd     = AdvertisementCategory::updateAdvertisementCategory($childWhere,$updateData);
                }else if(  isset($location[0]['location']) && strtolower($location[0]['location']) == 'mingle'  )
                {
                    $pauseChildAdd     = AdvertisementMingle::updateAdvertisementMingle($childWhere,$updateData);
                }
    
                \Flash::success("Your add has been ".$pauseData['type']." successfully!");
        }
        catch(\Exception $e){
            \Flash::error("There is some error");
        }

        return response()->json(['status' => 'success', 'redirectUrl' => route("advertisements")]);

        //return redirect()->back();
        //return redirect()->route('advertisements');
        //return redirect()->route('advertisements'); 
    
    }

    public function postAdd(Request $request)
    {
        $allCategories = Category::where('parent_id', 0)->pluck('text', 'id')->all();
        $productData['category_id'] = NULL;
        $categoryIds = [];
        $updateFlag = FALSE;

        return view('front.buy.advertisement.post_add',compact('allCategories','productData','categoryIds','updateFlag'));
    }

    public function storeAdd(Request $request)
    {
        $customValidationMessage = [];
       
         /* ---------- Validation check ---------- */
        $validations = [
            'advertisement_name'    => 'required|unique:advertisement,advr_name',
            'location'              => 'required',
            'start_date'            => 'required',
            'no_of_days'            => 'required',
            'advertisement_link'    => 'required',
            'type'                  => 'required',
        ];

        if(empty($request->file()))
        {
            $validations += [ 'ad_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg' ];
        }

        /* ---------- Validates inputs ---------- */
        $this->validate($request, $validations, $customValidationMessage);

        DB::beginTransaction();
        try {
            $inputdata = $request->all();
            
            $addData                = [];
            $addData['advr_name']   = $inputdata['advertisement_name'];
            $addData['status']      = 0;
            $addData['type']        = $inputdata['type'];
            $loc = '';
            if( $inputdata['location'] == 'adhomepage' )
            {
                $addData['location']    = 'Home';
                $loc = 1;
            }else if( $inputdata['location'] == 'adcategory' )
            {
                $addData['location']    = 'Category';
                $loc = 2;
            }else if( $inputdata['location'] == 'admingle' )
            {
                $addData['location']    = 'Mingle';
                $loc = 3;
            }

            $addData['user_id']  = loggedinUserId();
            
            $addID  = Advertisement::create($addData);
            $lastId = $addID->id;
            
            $addHomeData = [];
            $addHomeData['advertisement_id']    = $lastId;
            $addHomeData['advr_url']            = $inputdata['advertisement_link'];
            $addHomeData['start_date']          = date('Y-m-d',strtotime($inputdata['start_date']));
            $addHomeData['end_date']            = date('Y-m-d',strtotime("+".$inputdata['no_of_days']." day",strtotime($inputdata['start_date'])));
            $addHomeData['no_of_days']          = $inputdata['no_of_days'];
            $addHomeData['status']              = 0;
            $addHomeData['type']                = $inputdata['type'];

            if($loc == 1)
            {
                $addChildID = AdvertisementHome::create($addHomeData);
            }else if($loc == 2)
            {
                $addChildID = AdvertisementCategory::create($addHomeData);
                
                if(!empty($inputdata['category_id']) && !empty($inputdata['category_id'][0]))
                {
                    foreach ($inputdata['category_id'] as $key => $value) {
                        $addCatData                 = [];
                        $addCatData['advertise_id'] = $lastId;
                        $addCatData['cat_id']       = $value;
                        $addChildCatID              = AdvertisementCatMap::create($addCatData);
                    }
                }
            }else if($loc == 3)
            {
                $addChildID = AdvertisementMingle::create($addHomeData);
            }

            $name = uploadImage($request->file(), true, '', '', 'buyer_purchase_ads/'.loggedinUserId());
            // $addChildID->Files()->create($name);

            //dd($addChildID);
            // If we reach here, then data is valid and working. Commit the queries!
            DB::commit();
            \Flash::success(trans('message.advertisement.update_success'));
    

            $log = new AdLog;
            $log->ad_id = $lastId;
            $log->old_status = 0;
            $log->new_status = 0;
            $log->save();

            $user = \Auth::user();

        Mail::send('front.email_templates.reminder', ['user' => $user], function ($m) use ($user) {
                $m->from('emad.keriakos@indianic.com', 'Your Application');

                $m->to('emad.keriakos@indianic.com', 'Emad Zaki')->subject('New Advertisment Request');
            }); 

            return response()->json(['status' => 'success', 'redirectUrl' => route("advertisements")]);
            
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $e->getMessage()]]);
        }
    }

    public function editAd(Request $request, $id)
    {
        try {
            $adID       = decrypt($id);
            $addDetails = $this->Advertisement_obj->getalladvertisement($adID);
            $image = '';
            
            if(!empty($addDetails[0]->cat_image))
            {
                $image =  $addDetails[0]->cat_image = getImageFullPath($addDetails[0]->cat_image,'buyer_purchase_ads/'.$addDetails[0]->user_id,'thumbnail');
                
            }else if(!empty($addDetails[0]->home_image))
            {
                $image =  $addDetails[0]->home_image = getImageFullPath($addDetails[0]->home_image,'buyer_purchase_ads/'.$addDetails[0]->user_id,'thumbnail');
            }else if(!empty($addDetails[0]->min_image))
            {
                $image = $addDetails[0]->min_image = getImageFullPath($addDetails[0]->min_image,'buyer_purchase_ads/'.$addDetails[0]->user_id,'thumbnail');
            }

            $allCategories              = Category::where('parent_id', 0)->pluck('text', 'id')->all();
            $productData['category_id'] = NULL;
            $categoryIds                = [];

            $updateFlag = TRUE;

            $adCategory = AdvertisementCatMap::where(['advertise_id' => $adID])->pluck('cat_id', 'cat_id')->toArray();


            $intersection = array_intersect_key($adCategory, $allCategories);

            if (count($intersection) > 0)
            {
                // Get and display the matching keys.
                $matchingKeys = array_keys($intersection);
                
                $categoryIds[0] = $matchingKeys[0];

                $i = 1;
                /* category sub category drodowns */
                $categories = $categories = Category::where(['id' => $matchingKeys[0]])->first()->ancestorsAndSelf()->get();
                foreach ($adCategory AS $category) {
                    $categoryIds[$i] = $category;
                    $i++;
                }
                arsort($categoryIds);
                array_pop($categoryIds);
                asort($categoryIds);
                $categoryIds = array_values($categoryIds);
                
                $productData['category_id'] = $matchingKeys[0];
                
            }

            return view('front.buy.advertisement.edit_ad',compact('addDetails','allCategories','productData','categoryIds','updateFlag','image'));
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $e->getMessage()]]);
        }
    }

    public function storeEdit(Request $request, $id)
    {
        $customValidationMessage = [];
       
         /* ---------- Validation check ---------- */
        $validations = [
            'advertisement_name'    => 'required',
            'location'              => 'required',
            'start_date'            => 'required',
            'no_of_days'            => 'required',
            'advertisement_link'    => 'required',
            'type'                  => 'required',
        ];

        /*if(empty($request->file()))
        {
           $validations += [ 'ad_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg' ];
        }*/

        /* ---------- Validates inputs ---------- */
        $this->validate($request, $validations, $customValidationMessage);
        DB::beginTransaction();

        try {
            $adID       = decrypt($id);
            
            $addDetails = $this->Advertisement_obj->getalladvertisement($adID);
            $image  = '';
            $fileID = '';

            if(!empty($addDetails[0]->cat_image))
            {
                $image  =   $addDetails[0]->cat_image ;
                $fileID =   $addDetails[0]->cat_image_id;

            }else if(!empty($addDetails[0]->home_image))
            {
                $image  =   $addDetails[0]->home_image ;
                $fileID =   $addDetails[0]->home_image_id;

            }else if(!empty($addDetails[0]->min_image))
            {
                $image  =   $addDetails[0]->min_image ;
                $fileID =   $addDetails[0]->min_image_id;
            }
            
            $inputdata                 = $request->all();
            $updateData                = [];
            $updateData['advr_name']   = $inputdata['advertisement_name'];
            $updateData['type']        = $inputdata['type'];
            $loc = '';
            if( $inputdata['location'] == 'adhomepage' )
            {
                $updateData['location']    = 'Home';
                $loc = 1;
            }else if( $inputdata['location'] == 'adcategory' )
            {
                $updateData['location']    = 'Category';
                $loc = 2;
            }else if( $inputdata['location'] == 'admingle' )
            {
                $updateData['location']    = 'Mingle';
                $loc = 3;
            }

            //dd($fileID);
            $updateData['user_id']  = loggedinUserId();
            $where = ['id' => $adID];
            
            Advertisement::updateAdvertisement($where,$updateData);
            //$lastId = $addID->id;
            
            $updateHomeData = [];
            //$updateHomeData['advertisement_id']    = $lastId;
            $updateHomeData['advr_url']            = $inputdata['advertisement_link'];
            $updateHomeData['start_date']          = date('Y-m-d',strtotime($inputdata['start_date']));
            $updateHomeData['end_date']            = date('Y-m-d',strtotime("+".$inputdata['no_of_days']." day",strtotime($inputdata['start_date'])));
            $updateHomeData['no_of_days']          = $inputdata['no_of_days'];
            $updateHomeData['type']                = $inputdata['type'];
            $whereChild                            = ['advertisement_id' => $adID];
            
            /*Remove Ad and category association*/
            $removeAdCategory = $this->AdvertisementCatMap_obj->deleteAdCategoryAssociation($adID);

            if($loc == 1)
            {
                if($addDetails[0]->location == 'Home')
                {
                    AdvertisementHome::updateAdvertisementHome($whereChild,$updateHomeData);
                    $imageable_type = 'App\Models\AdvertisementHome';
                    $updatedID      = AdvertisementHome::getIdByAdID($whereChild);
                }else{
                    $updateHomeData['advertisement_id']    = $adID;
                    $addChildID = AdvertisementHome::create($updateHomeData);
                    $this->AdvertisementCategory_obj->deleteAdCat($adID);
                    $this->AdvertisementMingle_obj->deleteAdMingle($adID);
                }
                
            }else if($loc == 2)
            {
                
                if($addDetails[0]->location == 'Category')
                {
                    AdvertisementCategory::updateAdvertisementCategory($whereChild,$updateHomeData);
                    $imageable_type = 'App\Models\AdvertisementCategory';
                    $updatedID      = AdvertisementCategory::getIdByAdID($whereChild);
                    
                }else{
                    $updateHomeData['advertisement_id']    = $adID;
                    $addChildID = AdvertisementCategory::create($updateHomeData);

                    $this->AdvertisementHome_obj->deleteAdHome($adID);
                    $this->AdvertisementMingle_obj->deleteAdMingle($adID);
                }

                if(!empty($inputdata['category_id']) && !empty($inputdata['category_id'][0]))
                {
                    foreach ($inputdata['category_id'] as $key => $value) {
                        $addCatData                 = [];
                        $addCatData['advertise_id'] = $adID;
                        $addCatData['cat_id']       = $value;
                        $addChildCatID              = AdvertisementCatMap::create($addCatData);
                    }
                }
            }else if($loc == 3)
            {
                if($addDetails[0]->location == 'Mingle')
                {
                    AdvertisementMingle::updateAdvertisementMingle($whereChild,$updateHomeData);
                    $imageable_type = 'App\Models\AdvertisementMingle';
                    $updatedID      = AdvertisementMingle::getIdByAdID($whereChild);
                }else{
                    $updateHomeData['advertisement_id']    = $adID;
                    $addChildID = AdvertisementMingle::create($updateHomeData);

                    $this->AdvertisementHome_obj->deleteAdHome($adID);
                    $this->AdvertisementCategory_obj->deleteAdCat($adID);
                }
            }
            
            if(!empty($request->file()))
            {
                $name       = uploadImage($request->file(), true, $image, '', 'buyer_purchase_ads/'.loggedinUserId());
                if(isset($addChildID))
                {
                    $deleteFile = $this->Files_obj->deleteAdfile($fileID);
                    $addChildID->Files()->create($name);    
                }else{
                    $whereFile  = ['id' => $fileID];
                    $updateFileData['path']             = $name['path'];
                    $updateFileData['imageable_type']   = $imageable_type;
                    $updateFileData['imageable_id']     = $updatedID->id;
                    Files::updateAdfile($whereFile,$updateFileData);
                }
            }

            
            //dd($addChildID);
            // If we reach here, then data is valid and working. Commit the queries!
            DB::commit();
            \Flash::success(trans('message.advertisement.update_success'));

            return response()->json(['status' => 'success', 'redirectUrl' => route("advertisements")]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $e->getMessage()]]);
        }
    }
}