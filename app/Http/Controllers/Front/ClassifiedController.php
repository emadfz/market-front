<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ClassifiedProduct;
use App\Models\UserAddress;
use App\Models\ClassifiedDayTime;
use App\Models\ClassifiedRelatedProduct;
use App\Models\ProductConditions;
use App\Models\Files;
use App\Models\RequestPreview;
use App\Models\MessageList;
use App\Models\SenderMessageList;
use App\Models\ReceiverMessageList;
use App\Models\MemberFeedback;

use DB;
use Datatables;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\classifiedProductRequest;
use App\Http\Requests\SendBuyerMessageRequest;

class ClassifiedController extends Controller
{
	public $classifiedProduct;
    public $category;

	public function __construct()
    {
		$this->classifiedProduct 	= new ClassifiedProduct();
        $this->category 			= new Category();
        $this->productConditions    = new ProductConditions();
        $this->messagelist          = new MessageList();
        $this->sendermessagelist    = new SenderMessageList();
        $this->receivermessagelist  = new ReceiverMessageList();
        $this->memberFeedback       = new MemberFeedback();
    }

    public function index()
    {
    	$productStatus 	= getMasterEntityOptions('product_status');
        $periodTime 	= config('offer.period_array');
        $categories 	= $this->category->getNestedData();

        $totalClassified       = ClassifiedProduct::getCountClassifiedProduct();
        $totalRequester        = RequestPreview::getCountClassifiedProduct();

        return view('front.sell.classified_product.index', compact('productStatus', 'periodTime', 'categories','totalClassified','totalRequester'));
    }

    public function datatableList(Request $request) {
        
        $search     = $request->input();
        $IsSearch   = array();

        if(!empty($search['updated_at']) && isset($search['updated_at']))
        {
            $IsSearch['updated_at']     = $search['updated_at'];
        }
		
        $products = ClassifiedProduct::getAllClassifiedProducts($IsSearch);
        
		return Datatables::of($products)
                ->addColumn('product_name', function ($product) {
                    $product_name = '';
                    $product_name .= '<a href="' . route("productSlugUrl",[$product->product_slug]) . '" class="table-link" data-toggle="tooltip" data-placement="top" title="View Product Details">'.$product->name.'</a>';
                    return $product_name;
                })
                ->addColumn('action', function ($product) {
                    $action = '';
                    $action .= '<a href="' . route('editClassifiedProduct', [encrypt($product->id)]) . '" class="table-link" data-toggle="tooltip" data-placement="top" title="Edit Classified Product">Edit</a>';
                    return $action;
                })
                ->addColumn('No_of_requester', function ($product) {
                    $No_of_requester = '';
                    if($product->requestPreview->count() > 0)
                    {
                        $No_of_requester .= '<a href="' . route("classifiedRequester",[encrypt($product->id)]) . '" class="table-link" data-toggle="tooltip" data-placement="top" title="View Buyer Details">View Buyer Details ('.$product->requestPreview->count().')</a>';
                    }
                    return $No_of_requester;
                })
                ->addColumn('expiration', function ($product) {
                    $expiration = '';
                    $date1   = strtotime("+".config('project.classified_expiration_day')." days", strtotime($product->created_at));
                    //$e      = date("Y-m-d", $date);
                    $seconds = $date1 - strtotime(date('Y-m-d H:i:s')) ;

                    $days = floor($seconds / 86400);
                    $seconds %= 86400;

                    $hours = floor($seconds / 3600);
                    $seconds %= 3600;

                    $minutes = floor($seconds / 60);
                    $seconds %= 60;

                    /*$days = round(($date1 - time()) / 86400);*/

                    $expiration = $days .'d '.$hours .'h '.$minutes.'m';

                    if($days <= 0)
                    {
                        $expiration = 'Expired';
                    }

                    return $expiration;
                })

                ->editColumn('description', '{!! str_limit($description, 40) !!}')
                ->filter(function ($query) use ($request) {
                    if ($request->has('status') && trim($request->input('status')) != "") {
                        if ($request->input('status') != "All")
                            $query->where('status', trim($request->input('status')));
                    }

                    if ($request->has('category_id') && $request->input('category_id') != "") {
                        if ($request->input('category_id') != 0)
                            $query->where('category_id', $request->input('category_id'));
                    }
                    if ($request->has('name') && $request->input('name') != "") {
                        $query->where('name','like', '%'.$request->input('name').'%');
                    }
                })
                ->make(true);
	}

	public function create($product_type='classified') 
	{
		$allCategories 			    = Category::where('parent_id', 0)->pluck('text', 'id')->all();
        $productData 			    = [];
        $productData['category_id'] = NULL;
        $categoryIds 			    = [];
        $updateFlag 			    = FALSE;
        $countries 				    = getAllCountries()->pluck('country_name', 'id')->toArray();
        $productConditions          = 	['' => 'Select product condition'];
        $productId 				    = '';
        $fileArray				    = '';

        $classifiedRelatedProduct   = ClassifiedProduct::select('id', 'name')->where('user_id', loggedinUserId())->get()->toArray();
        //dd($classifiedRelatedProduct);
        return view('front.sell.classified_product.create',compact('allCategories','productData','categoryIds','updateFlag','countries','productImages','productId','fileArray','productConditions','classifiedRelatedProduct','product_type'));
	}

	public function store(classifiedProductRequest $request)
	{
        //dd($request->all());
        DB::beginTransaction();
        try {

			$data = $request->all();

			$addData 								= [];
			$addData['user_id'] 					= loggedinUserId();
			$addData['system_generated_product_id'] = generateToken($data['name']);
			$addData['category_id'] 				= end($data['category_id']);
			$addData['product_conditions_id'] 		= $data['product_condition_id'];
			$addData['name'] 						= $data['name'];
			$addData['video_link'] 					= $data['video_link'];
			$addData['description'] 				= $data['ckeditor'];
			$addData['product_origin'] 				= $data['product_origin'];
			$addData['meta_tag'] 					= $data['meta_tag'];
			$addData['meta_keyword'] 				= $data['meta_keyword'];
			$addData['meta_description'] 			= $data['meta_description'];
			$addData['product_slug'] 				= str_slug($data['name']);
			$addData['status'] 						= 'Active';

			if($data['submit_type']== 'Save as draft')
			{
				$addData['status'] 					= 'Draft';
			}
			$addData['product_listing_price'] 		= '';
			$addData['base_price'] 					= '';

			if($data['product_origin'] == 'No')
			{
				$requestAddress = [
	                'address_1' 	=> $data['billing_address_1'],
	                'address_2' 	=> $data['billing_address_2'],
	                'country_id' 	=> $data['billing_country'],
	                'postal_code' 	=> $data['billing_postal_code'],
	                'state_id' 		=> $data['billing_state'],
	                'city_id' 		=> $data['billing_city'],
	                'user_id' 		=> loggedinUserId(),
	                'address_type' 	=> 'Billing'
	            ];

	            $userAddress = UserAddress::createAddress($requestAddress);
	            $addData['user_address_id'] = $userAddress->id;
			}

			$addData['user_address_id'] = '';

			$classified = ClassifiedProduct::create($addData);

            if(!empty($data['related_product']))
            {   
                foreach ($data['related_product'] as $key => $value) 
                {
                    $relatedProduct = [
                        'classified_product_id' => $classified->id,
                        'related_products_id'   => $value,
                        'type'                  => 'classified',
                    ];

                    $ClassifiedRelatedProduct = ClassifiedRelatedProduct::create($relatedProduct);
                }
            }


            $upload_file = $request->file('product_files');

            if(isset($data['remove_image_id']) && !empty($data['remove_image_id']))
            {
                foreach ($data['remove_image_id'] as $key => $value) {
                    unset($upload_file[$value]);                        
                }
            }

            if(!empty($upload_file))
            {
                $name = uploadImage($upload_file, true, '', '', 'classified_products/'.loggedinUserId());
            
                if(isset($name[0]))
                    foreach ($name as $key => $value)
                    {
                        $fileID = $classified->Files()->create($value);

                        if($data['baseimage'] == $key)
                        {
                            $updateData['default_image_id'] = $data['baseimage'];    
                            ClassifiedProduct::updateClassifiedProduct($where,$updateData);
                        }
                    }
                else
                {
                    $fileID = $classified->Files()->create($name);
                    if(!isset($updateData['default_image_id']))
                    {
                        $updateData['default_image_id']  = $fileID->id;
                        $where = ['id' => $classified->id];
                        ClassifiedProduct::updateClassifiedProduct($where,$updateData);
                    }
                }
            }
                
            if(!empty($request->file('uploadvideo')))
            {
                $a[0] = $request->file('uploadvideo');
                $name = uploadImage($a, true, '', '', 'classified_products/'.loggedinUserId());
                $classified->Files()->create($name);
            }
            

            foreach ($data['available_date'] as $key => $value)
            {
                $availableDate = [
                    'classified_product_id' => $classified->id,
                    'available_date'        => date('Y-m-d',strtotime($value)),
                    'from_time'             => $data['from_time'][$key],
                    'to_time'               => $data['to_time'][$key],
                ];

                $ClassifiedDayTime = ClassifiedDayTime::create($availableDate);
            }

            DB::commit();
	        \Flash::success(trans('message.classified.update_success'));

	        return response()->json(['status' => 'success', 'redirectUrl' => route('listingClassifiedProduct')]);

    	} catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $e->getMessage()]]);
        }
    }

	public function edit($classifiedID)
    {
        $classifiedID               = decrypt($classifiedID);
        $allCategories              = Category::where('parent_id', 0)->pluck('text', 'id')->all();
        $productData                = [];
        $productData['category_id'] = NULL;
        $categoryIds                = [];
        $updateFlag                 = TRUE;
        $countries                  = getAllCountries()->pluck('country_name', 'id')->toArray();
        $productConditions          =   ['' => 'Select product condition'];
        $productId                  = '';
        $fileArray                  = '';
        
        $where                      = [ 'id' => $classifiedID ];
        $classifiedDetails          = ClassifiedProduct::getProductDetails($where);

        //dd($classifiedDetails);
        if ($updateFlag) {
            $productConditions += $this->productConditions->getProductConditionsByCategory($classifiedDetails['category_id'])->pluck('name', 'id')->toArray();
            //dd($classifiedDetails);
            /* category sub category drodowns */
            $categories = Category::where(['id' => $classifiedDetails['category_id']])->first()->ancestorsAndSelf()->get();
            foreach ($categories AS $category) {
                $categoryIds[] = $category['id'];
            }
            arsort($categoryIds);
            array_pop($categoryIds);
            asort($categoryIds);
            $categoryIds                = array_values($categoryIds);
            $productData['category_id'] = $classifiedDetails['category_id'];
        }

        $productData['description'] = $classifiedDetails['description'];
        $productId = $classifiedID;

        $productData['productOriginAddress'] = array();

        if($classifiedDetails['product_origin'] == 'No')
        {
            $productData['productOriginAddress'] = $classifiedDetails->productOriginAddress;
        }

        /*Classified Related Product*/
        $classifiedRelatedAllProduct   = ClassifiedProduct::select('id', 'name')->where('user_id' , loggedinUserId())->where('id','!=', $classifiedID)->get()->toArray();

        $classifiedRelatedProduct   = ClassifiedRelatedProduct::where('classified_product_id', $classifiedID)->pluck('related_products_id','related_products_id')->toArray();

        

        return view('front.sell.classified_product.edit',compact('allCategories','productData','categoryIds','updateFlag','countries','productImages','productId','fileArray','productConditions','classifiedRelatedAllProduct','classifiedDetails','categoryIds','classifiedRelatedProduct'));
    }

    public function editStore(classifiedProductRequest $request,$classifiedID)
    {
        $classifiedID  = decrypt($classifiedID);
        DB::beginTransaction();
        try {

            $data                                   = $request->all();
            $addData                                = [];
            $addData['user_id']                     = loggedinUserId();
            $addData['category_id']                 = end($data['category_id']);
            $addData['product_conditions_id']       = $data['product_condition_id'];
            $addData['name']                        = $data['name'];
            $addData['video_link']                  = $data['video_link'];
            $addData['description']                 = $data['ckeditor'];
            $addData['product_origin']              = $data['product_origin'];
            $addData['meta_tag']                    = $data['meta_tag'];
            $addData['meta_keyword']                = $data['meta_keyword'];
            $addData['meta_description']            = $data['meta_description'];
            $addData['product_slug']                = str_slug($data['name']);
            $addData['status']                      = 'Active';

            if($data['submit_type']== 'Save as draft')
            {
                $addData['status']                  = 'Draft';
            }

            $addData['product_listing_price']       = '';
            $addData['base_price']                  = '';

            if (strpos($data['baseimage'], 'new_') !== false)
            {
                $data['baseimage'] = str_replace('new_', '', $data['baseimage']);
            }else{
                $addData['default_image_id'] = $data['baseimage'];
            }

            if($data['product_origin'] == 'No')
            {
                $requestAddress = [
                    'address_1'     => $data['billing_address_1'],
                    'address_2'     => $data['billing_address_2'],
                    'country_id'    => $data['billing_country'],
                    'postal_code'   => $data['billing_postal_code'],
                    'state_id'      => $data['billing_state'],
                    'city_id'       => $data['billing_city'],
                    'user_id'       => loggedinUserId(),
                    'address_type'  => 'Billing'
                ];
                $where = ['id' => decrypt($data['user_address_id'])];

                $userAddress = UserAddress::updateUserAddress($where,$requestAddress);
                /*$addData['user_address_id'] = $userAddress->id;*/
            }else{
                //$user_address_id = decrypt($data['user_address_id']);
                //$files = UserAddress::find($user_address_id);
                //$files->delete();
                $addData['user_address_id'] = '';
            }

            $whereClassified = ['id' => $classifiedID];

            
            if(!empty($data['related_product']))
            {   
                ClassifiedRelatedProduct::where('classified_product_id','=',$classifiedID)->delete();
                
                foreach ($data['related_product'] as $key => $value) {
                    $relatedProduct = [
                        'classified_product_id' => $classifiedID,
                        'related_products_id'   => $value,
                        'type'                  => 'classified',
                    ];

                    $ClassifiedRelatedProduct = ClassifiedRelatedProduct::create($relatedProduct);
                }
            }

            $classified = ClassifiedProduct::getOnlyProductDetails($whereClassified);

            if(!empty($request->file('update_product_files')))
            {
                $upload_file = $request->file('update_product_files');

                if(isset($data['remove_image_id']) && !empty($data['remove_image_id']))
                {
                    foreach ($data['remove_image_id'] as $key => $value) {
                        unset($upload_file[$value]);                        
                    }
                }

                if(!empty($upload_file))
                {
                    $name = uploadImage($upload_file, true, '', '', 'classified_products/'.loggedinUserId());
                
                    if(isset($name[0]))
                        foreach ($name as $key => $value)
                        {
                            $fileID = $classified->Files()->create($value);

                            if($data['baseimage'] == $key)
                            {
                               $addData['default_image_id']  = $fileID->id;
                            }
                        }
                    else
                    {
                        $fileID = $classified->Files()->create($name);

                        if(!isset($addData['default_image_id']))
                        {
                            $addData['default_image_id']  = $fileID->id;
                        }
                    } 
                }
            }

            if(!empty($request->file('uploadvideo')))
            {
                if(isset($data['upload_video_id']) && !empty($data['upload_video_id']))
                {
                    $upload_video_id = decrypt($data['upload_video_id']);
                    $files = Files::find($upload_video_id);
                    $files->delete();
                }
                $a[0] = $request->file('uploadvideo');
                $name = uploadImage($a, true, '', '', 'classified_products/'.loggedinUserId());
                $classified->Files()->create($name);
            }

            ClassifiedProduct::updateClassifiedProduct($whereClassified,$addData);
            
            if(!empty($data['available_date']))
            { 
                ClassifiedDayTime::where('classified_product_id','=',$classifiedID)->delete();

                foreach ($data['available_date'] as $key => $value)
                {
                    $availableDate = [
                        'classified_product_id' => $classifiedID,
                        'available_date'        => date('Y-m-d',strtotime($value)),
                        'from_time'             => $data['from_time'][$key],
                        'to_time'               => $data['to_time'][$key],
                    ];

                    $ClassifiedDayTime = ClassifiedDayTime::create($availableDate);
                }
            }

            DB::commit();
            \Flash::success(trans('message.classified.update_success'));

            return response()->json(['status' => 'success', 'redirectUrl' => route('listingClassifiedProduct')]);

        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $e->getMessage()]]);
        }
    }

    public function removeimage(Request $request)
    {
        $data = $request->all();
        
        try
        {   
            if(!empty($data['image_id']))
            {
                
                $image_id   = decrypt($data['image_id']);
                $user_id    = decrypt($data['user_id']);

                $where      = ['id' => $image_id];
                $image_name = Files::getfile($where);
                
                if(!empty($image_name))
                {
                    $path       = public_path() . '/images/classified_products/'.$user_id;
                    $old_image  = $image_name[0]['path'];
                    $main       = $path . '/main/' . $old_image;
                    $small      = $path . '/small/' . $old_image;
                    $thumbnail  = $path . '/thumbnail/' . $old_image;
                    
                    if (file_exists($main)) {
                        unlink($main);
                    }

                    if (file_exists($small)) {
                        unlink($small);
                    }
                    if (file_exists($thumbnail)) {
                        unlink($thumbnail);
                    }

                    if(!file_exists($main) && !file_exists($small) && !file_exists($thumbnail))
                    {
                        $files = Files::find($image_id);
                        $files->delete();

                        \Flash::success("Your image remove successfully!");
                        return response()->json(['status' => 'success']);
                    }else
                    {
                        \Flash::error("There is some error");
                        return response()->json(['status' => 'error']);
                    }
                }else
                {
                    \Flash::error("There is some error");
                    return response()->json(['status' => 'error']);
                }
            }else
            {
                \Flash::error("There is some error");
                return response()->json(['status' => 'error']);
            }
        }
        catch(\Exception $e){
            \Flash::error("There is some error");
            return response()->json(['status' => 'error']);
        }

        exit;
    }

    public function removevideo(Request $request)
    {
        $data = $request->all();
        try
        {
            if(!empty($data['video_id']))
            {
                $video_id   = decrypt($data['video_id']);
                $user_id    = decrypt($data['user_id']);

                $where      = ['id' => $video_id];
                $video_name = Files::getfile($where);
                
                if(!empty($video_name))
                {
                    $path       = public_path() . '/images/classified_products/'.$user_id;
                    $old_image  = $video_name[0]['path'];
                    $video      = $path . '/video/' . $old_image;

                    if (file_exists($video)) {
                        unlink($video);
                    }
                    
                    if(!file_exists($video))
                    {
                        $files = Files::find($video_id);
                        $files->delete();

                        \Flash::success("Your video remove successfully!");
                        return response()->json(['status' => 'success']);
                    }else
                    {
                        \Flash::error("There is some error");
                        return response()->json(['status' => 'error']);
                    }
                }else
                {
                    \Flash::error("There is some error");
                    return response()->json(['status' => 'error']);
                }
            }else
            {
                \Flash::error("There is some error");
                return response()->json(['status' => 'error']);
            }
        }
        catch(\Exception $e){
            \Flash::error("There is some error");
            return response()->json(['status' => 'error']);
        }

        exit;
    }

    public function classifiedRequester($classifiedID)
    {
        $classifiedID   = decrypt($classifiedID);
        $where          = ['id' => $classifiedID];
        $products       = ClassifiedProduct::getProductDetails($where);

        return view('front.sell.classified_product.classified_requester', compact('classifiedID','products'));
    }

    public function datatableRequesterList(Request $request,$classifiedID)
    {
        $classifiedID   = decrypt($classifiedID);
        $where          = ['classified_products_id' => $classifiedID];

        $buyers = RequestPreview::getRequestBuyerDetails($where);
        
        return Datatables::of($buyers)
            ->addColumn('action', function ($buyer) {
                $action = '';
                $action .= '<a href="' . route('messageBuyer', ['email'=>encrypt($buyer->user['email']),'id' => encrypt($buyer->id)]) . '" class="table-link ajax counterModel" data-toggle="tooltip"  data-target="#message-modal" data-placement="top">Message Buyer</a>';
                $action .= '<a href="' . route('feedbackBuyer', ['cid'=>encrypt($buyer->id),'bid' => encrypt($buyer->user['id'])]) . '" class="table-link ajax counterModel" data-toggle="tooltip" data-placement="top" data-target="#feedback-modal" >Feedback Buyer</i></a>';
                return $action;
            })
            ->addColumn('preview_date', function ($buyer) {
                $preview_date = date('d M, Y',strtotime($buyer->preview_date)).'<br/> '.$buyer->preview_from_time.' to '.$buyer->preview_to_time;
                return $preview_date;
            })
            ->addColumn('contact', function ($buyer) {
                $contact = $buyer->user['first_name'].' '.$buyer->user['last_name'].'<br/>'.$buyer->user['email'].'<br/>'.$buyer->user['phone_number'];
                return $contact;
            })
            ->make(true);
    }

    public function messageBuyer($email,$classifiedID)
    {
        $email = decrypt($email);
        return view('front.sell.classified_product.message', compact('email','classifiedID'));
    }

    public function sendMessageBuyer(SendBuyerMessageRequest $request,$email,$classifiedID)
    {
        $data = $request->all();
        try
        {
            /* Enter data in message table */
            $msg_data                = [];
            $msg_data['msg_subject'] = $data['subject'];
            $msg_data['msg_content'] = $data['ckeditor'];
            $msg_data['msg_status']  = 'New';
            
            $msg_res    = $this->messagelist->send_message($msg_data);
            
            /* Enter data in msgs_sender table */

            $sender_data                            = [];
            $sender_data['messages_id']             = $msg_res->id ;
            $sender_data['msg_status']              = 'New';
            $sender_data['msg_type']                = 'Classified';
            $sender_data['sender_member_msgs_id']   = loggedinUserId();

            $res_sender = $this->sendermessagelist->store_sender_detail($sender_data);

            /* Buyer Details */

            $buyerDetails   = \App\Models\User::checkUserData(['email'=>decrypt($email)]);

            /* Enter data in msgs_receiver table */

            $receiver_data                              = [];
            $receiver_data['messages_id']               = $msg_res->id ;
            $receiver_data['msgs_sender_id']            = $res_sender->id;
            $receiver_data['msg_status']                = 'New';
            $receiver_data['msg_type']                  = 'Classified';
            $receiver_data['receiver_member_msgs_id']   = $buyerDetails['id'];
            
            $res = $this->receivermessagelist->store_receiver_detail( $receiver_data );

            $toEmail    = 'bahadur.deol@indianic.com';//$data['to'];
            $toName     = $buyerDetails['first_name'].' '.$buyerDetails['last_name'];
            $fromName   = 'InSpree';
            $fromEmail  = 'message@inspree.com';
            $subject    = $data['subject'];
            $body       = $data['ckeditor'];

            sendOfferNotification($toEmail,$toName,$fromName,$fromEmail,$subject,$body);
            
            \Flash::success("Your message has been send successfully!");
            return response()->json(['status' => 'success', 'redirectUrl' => route("classifiedRequester",[$classifiedID])]);
        }
        catch(\Exception $e){
            \Flash::error("There is some error");
            return response()->json(['status' => 'error', 'redirectUrl' => route("classifiedRequester",[$classifiedID])]);
        }
    }

    public function feedbackBuyer($classifiedID,$buyerID)
    {
        return view('front.sell.classified_product.feedback', compact('buyerID','classifiedID'));
    }

    public function sendFeedbackBuyer(Request $request,$classifiedID,$buyerID)
    {   
        $customValidationMessage = [
            'ckeditor.required' => 'The feedback field is required.'
        ];

        $validations = [
            'ckeditor' => 'required',
        ];

        $this->validate($request, $validations, $customValidationMessage);

        try
        {
            $data = $request->all();

            $feedbackData['sender_id']      = loggedinUserId();
            $feedbackData['receiver_id']    = decrypt($buyerID);
            $feedbackData['rating']         = '0';
            if(!empty($data['start_val']))
            {
                $feedbackData['rating']         = $data['start_val'];    
            }
            
            $feedbackData['description']        = $data['ckeditor'];

            $feedback = MemberFeedback::create($feedbackData);

            if($feedback->id)
            {
                 \Flash::success("Your message has been send successfully!");
                return response()->json(['status' => 'success', 'redirectUrl' => route("classifiedRequester",[$classifiedID])]);
            }else{
                
                \Flash::error("There is some error");
                return response()->json(['status' => 'error', 'redirectUrl' => route("classifiedRequester",[$classifiedID])]);
            }

           
        }
        catch(\Exception $e){
            \Flash::error("There is some error");
            return response()->json(['status' => 'error', 'redirectUrl' => route("classifiedRequester",[$classifiedID])]);
        }
    }

    public function changeProductStatus(Request $request,$type = 'Draft')
    {   
        $updateProductId = $request['product_select_multiple'];
        if(!empty($updateProductId))
        {
            $updateProductData = [
                'status' => $type,
            ];
            ClassifiedProduct::updateClassifiedProduct($updateProductId, $updateProductData,'update');
            \Flash::success('Product status update successfully');
            return response()->json(['status' => 'success']);
        }else{
            \Flash::error(trans('message.failure'));
            return response()->json(['status' => 'error']);
        }
        exit;
    }
}