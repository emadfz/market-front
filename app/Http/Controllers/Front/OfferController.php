<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Offers;
use App\Models\OfferDetails;
use App\Models\Product;
use App\Models\Category;
use Datatables;

class OfferController extends Controller
{
    public $offer;
    public $offerDetails;

    public function __construct() {
        $this->offer 		= new Offers();
        $this->offerDetails = new OfferDetails();
        $this->category 	= new Category();
    }

    public function index() {
		$productStatus 	= getMasterEntityOptions('product_status');
		$periodTime 	= config('offer.period_array');
		$categories 	= $this->category->getNestedData();

		return view('front.sell.offer.sell_offers', compact('productStatus','periodTime','categories'));
	}

	public function datatableList(Request $request) {
        
        $created_at = $request->input('created_at');
        $search     = $request->input('search');
        $IsSearch   = array();
        
        if(!empty($search['value']))
        {
            $IsSearch['search']     = $search['value'];
        }else if(!empty($created_at))
        {
            $IsSearch['created_at'] = $created_at;
        }
		
        $products = Product::getAllProductsWithOffer($IsSearch);
        //dd($request->input('name'));
        return Datatables::of($products)
                ->addColumn('product_id', function ($product) {
                    $product_id = '';
                    $product_id .= '<a href="' . route("productSlugUrl",[$product->product_slug]) . '" class="table-link" data-toggle="tooltip" data-placement="top" title="View Product Details">'.$product->id.'</a>';
                    return $product_id;
                })
                ->addColumn('buyer_count', function ($product) {
                    $buyer_count = '';
                    $buyer_count .= '<a href="' . route("offerDetails",[encrypt($product->id)]) . '" class="table-link" data-toggle="tooltip" data-placement="top" title="View Buyer Details">View Buyer Details ('.$product->offers->count().')</a>';
                    return $buyer_count;
                })
                ->addColumn('product_sold', function ($product) {
                    $product_sold = '';
                    $product_sold .= ($product->orderShipmentDetails->count() > 0?'Yes':'No');
                    return $product_sold;
                })
                ->editColumn('description', '{!! str_limit($description, 40) !!}')
                ->filter(function ($query) use ($request) {
                    
                    if ($request->has('status') && trim($request->input('status')) != "") {
                        if ($request->input('status') != "All")
                            $query->where('status', trim($request->input('status')));
                    }

                    if ($request->has('mode_of_selling') && $request->input('mode_of_selling') != "") {
                        if ($request->input('mode_of_selling') != "All")
                            $query->where('mode_of_selling', $request->input('mode_of_selling'));
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

    public function offerDetails(Request $request,$id)
    {
        $details                = array();
        $details['product_id']  = decrypt($id);

        $where = ['id' => $details['product_id']];
        $offers_details = Product::getProductDetailsWithBidders($where);
        
        return view('front.sell.offer.offers_details', compact('offers_details'));
    }

    public function datatableOfferList(Request $request,$product_id)
    {
        $where  = ['product_id' => $product_id];
        
        $offers = Offers::getProductOfferDetails($where);
        
        return Datatables::of($offers)
                ->addColumn('offer_status', function ($offer) {
                    $offer_status = '';
                    if($offer->status == 'open')
                    {
                        $offer_status .= '<a href="' . route("sellerAcceptResponce",encrypt([$offer->id])) . '" class="ajax table-link" data-toggle="tooltip" data-placement="top" title="Accept Offer">Accept</a><br/><br/>';
                        $offer_status .= '<a href="' . route("getCounterOffer",encrypt([$offer->id])) . '" class="ajax table-link" id="counterModel" data-toggle="tooltip" data-placement="top"  data-target="#offer-modal" title="Send Counter Offe">Send Counter Offer</a><br/><br/>';
                        $offer_status .= '<a href="' . route("sellerRejectResponce",encrypt([$offer->id])) . '" class="ajax table-link" data-toggle="tooltip" data-placement="top" title="Reject Offer">Reject</a>';
                    }else if($offer->status == 'accept'){
                        $offer_status = 'Waiting for buyer accept the offer';
                    }else if($offer->status == 'reject'){
                        $offer_status = 'Offer rejected';
                    }
                    return $offer_status;
                })
                ->addColumn('offer_price', function ($offer) {
                    $offer_price = '';
                    $e = end($offer->offerDetails);
                    $a = end($e);
                    $offer_price .= '$'.$a['amount'];
                    return $offer_price;
                })
                ->addColumn('buyer_offer_price', function ($offer) {
                    $buyer_offer_price = '';
                    $buyer_offer_price .= ucwords($offer->status);
                    return $buyer_offer_price;
                })
                ->addColumn('counter_offer', function ($offer) {
                    $counter_offer = '';
                    $e = end($offer->offerDetails);
                    $a = end($e);
                    if(!empty($a['counter_offer']))
                    {
                        $counter_offer .= '$'.$a['counter_offer'];
                    }else{
                        $counter_offer .= '-';
                    }
                    
                    return $counter_offer;
                })
                ->addColumn('buyer_name', function ($offer) {
                    $buyer_name = '';
                    $buyer_name .= ucwords($offer->userBuyerRelation['first_name']).' '.ucwords($offer->userBuyerRelation['last_name']);
                    return $buyer_name;
                })
                ->make(true);
    }

    public function offerAcceptSeller(Request $request,$offerID)
    {
        $return_data['status']    = 'error';
        
        try{    
            $offer_id               = decrypt($offerID);
            $where                  = ['id' => $offer_id];
            $offerDetails           = Offers::getOfferById($where);
            $pID                    = encrypt($offerDetails->product_id);

            $data['accepted_by']    = 'Seller';
            $data['status']         = 'accept';

            $dataOfferresult        = Offers::updateOffer($where,$data);
            
            /* Send offer accepted mail to buyer */
            $buyerDetails           = \App\Models\User::checkUserData(['id'=>$offerDetails->buyer_id]);

            $toEmail    = 'bahadur.deol@indianic.com';//$buyerDetails['email'];
            $toName     = $buyerDetails['first_name'].' '.$buyerDetails['last_name'];
            $fromName   = 'InSpree';
            $fromEmail  = 'offer@inspree.com';
            $subject    = 'InSpree - Your offer accepted by seller';
            $body       = 'Dear Buyer,<br/><br/> Your offer has been accepted by seller.<br/><br/> Thanks,<br/>InSpree Team';

            sendOfferNotification($toEmail,$toName,$fromName,$fromEmail,$subject,$body);

            \Flash::success("Offer accepted successfully!");
            //$return_data['status']      = 'success';
        }
        catch(\Exception $e){
            \Flash::error("There is some error");
        }

        return redirect()->route('offerDetails', [$pID]);
    }

    public function offerRejectSeller(Request $request,$offerID)
    {
        try{    
            $offer_id               = decrypt($offerID);
            $where                  = ['id' => $offer_id];
            $offerDetails           = Offers::getOfferById($where);
            $pID                    = encrypt($offerDetails->product_id);

            $data['status']         = 'reject';
            $data['accepted_by']    = '';
            $data['remaining_offer']  = config('project.offer_available_limit');
            $dataOfferresult        = Offers::updateOffer($where,$data);

            $buyerDetails           = \App\Models\User::checkUserData(['id'=>$offerDetails->buyer_id]);

            $toEmail    = 'bahadur.deol@indianic.com';//$buyerDetails['email'];
            $toName     = $buyerDetails['first_name'].' '.$buyerDetails['last_name'];
            $fromName   = 'InSpree';
            $fromEmail  = 'offer@inspree.com';
            $subject    = 'InSpree - Your offer rejected by seller';
            $body       = 'Dear Buyer,<br/><br/> Your offer has been rejected by seller.<br/><br/> Thanks,<br/>InSpree Team';

            sendOfferNotification($toEmail,$toName,$fromName,$fromEmail,$subject,$body);
            
            \Flash::success("Offer rejected successfully!");
        }
        catch(\Exception $e){
            \Flash::error("There is some error");
        }

        return redirect()->route('offerDetails', [$pID]);
    }

    public function getCounterOffer($offerID)
    {
        $offer_id     = decrypt($offerID);
        $where        = ['id' => $offer_id];
        $offer        = Offers::getOfferCommunication($where);

        //dd($offer);
        //dd($offer[0]->offerAllDetails);

        if(!empty(\Auth::user()->image))
        {
            $loginSellerUserData['login_image'] = URL("/assets/front/img/upload/".\Auth::user()->id.'/'.\Auth::user()->image );
        }else if(\Auth::user()->gender == 'Male')
        {
            $loginSellerUserData['login_image'] = URL("/assets/front/img/upload/user-male.png" );
        }else if(\Auth::user()->gender == 'Female')
        {
            $loginSellerUserData['login_image'] = URL("/assets/front/img/upload/user-female.png" );
        }

        $sellerData = \App\Models\User::checkUserData(['id'=>$offer[0]->seller_id]);
        //dd($sellerData);
        if(!empty($sellerData['image']))
        {
            $loginSellerUserData['seller_image'] = URL("/assets/front/img/upload/".$sellerData['id'].'/'.$sellerData['image'] );
        }else if(\Auth::user()->gender == 'Male')
        {
            $loginSellerUserData['seller_image'] = URL("/assets/front/img/upload/user-male.png" );
        }else if(\Auth::user()->gender == 'Female')
        {
            $loginSellerUserData['seller_image'] = URL("/assets/front/img/upload/user-female.png" );
        }

        return view('front.sell.offer.counter_offers', compact('offer','loginSellerUserData','sellerData'));
    }

    public function sendCounterOffer(Request $request,$offerID)
    {
        $amount     = $request->input('amount');
        $offer_id   = decrypt($offerID);
        
        $customValidationMessage = [];

        /* ---------- Validation check ---------- */
        $validations = [
            'amount' => 'required|numeric',
        ];

        /* ---------- Validates inputs ---------- */
        $this->validate($request, $validations, $customValidationMessage);

        try{
            $where      = ['id' => $offer_id];
            
            $offer              = Offers::getProductOfferDetails($where);
            $a                  = end($offer[0]->offerDetails);
            $lastOfferDetailsID = end($a);
            
            /* Update remaining_offer value */

            $offerUpdatedata['remaining_offer']     = config('project.offer_available_limit');
            
            $dataOfferUpdateResult                  = Offers::updateOffer($where,$offerUpdatedata);

            /* Update Offer Details */
            $WhereDetails = ['id' => $lastOfferDetailsID->id];
            
            $data['counter_offer']  = $amount;
            
            $dataOfferresult        = offerDetails::updateOfferDetails($WhereDetails,$data);

            /* Send buyer counter offer notification */
            $buyerDetails           = \App\Models\User::checkUserData(['id'=>$offer[0]->buyer_id]);
            
            $toEmail    = 'bahadur.deol@indianic.com';//$buyerDetails['email'];
            $toName     = $buyerDetails['first_name'].' '.$buyerDetails['last_name'];
            $fromName   = 'InSpree';
            $fromEmail  = 'offer@inspree.com';
            $subject    = 'InSpree - New offer send by seller';
            $body       = 'Dear Buyer,<br/><br/> New offer send by seller.<br/><br/> Thanks,<br/>InSpree Team';

            sendOfferNotification($toEmail,$toName,$fromName,$fromEmail,$subject,$body);

            \Flash::success("Counter offer send successfully!");

            return response()->json(['status' => 'success', 'redirectUrl' => route("offerDetails",[encrypt($offer[0]->product_id)])]);
        }catch(\Exception $e){
            \Flash::error("There is some error");
            return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $e->getMessage()]]);
        }
    }

    public function buyerIndex()
    {   
        $periodTime     = config('offer.period_array');
        $offerstatus    = config('offer.offer_status_array');
        
        return view('front.buy.offer.buyer_offers', compact('periodTime','offerstatus'));
    }

    public function buyerOfferDatatableList(Request $request) 
    {
        $created_at     = $request->input('created_at');
        $search_status  = $request->input('status');
        $name           = $request->input('name');

        $status = '';
        $where = ['buyer_id' => \Auth::user()->id];
        if(!empty($search_status) && $search_status != 'All')
        {
            $status = $search_status;
            $where  = ['buyer_id' => \Auth::user()->id,'offers.status' => $status];
        }

        if(!empty($created_at) && $created_at != 0)
        {
           if(!empty($status))
           {
                $where = array(array('buyer_id',\Auth::user()->id) ,array('offers.status',$status), array('updated_at' ,'>=',$created_at));
           }else{
                $where = array(array('buyer_id',\Auth::user()->id) , array('updated_at' ,'>=',$created_at));
           }
        }
        
        $whereIn = array();
        if(!empty($name))
        {   
            $name = trim($name);
            $productId = Product::productIdByName($name);
            if($productId->count() > 0)
            {
                foreach ($productId as $key => $value) {
                    $whereIn[] =  $value->id;   
                }
            }
        }

        $offers = Offers::getBuyerOffer($where,$whereIn);
        return Datatables::of($offers)
                ->addColumn('action', function ($offer) {
                    $action  = '';
                    $action .= '<a href="' . route("getBuyerCounterOffer",['id'=>encrypt([$offer->id]),'type'=>'view']) . '" class="ajax counterModel table-link" data-toggle="tooltip" data-placement="top"  data-target="#offer-modal" a_type="view" title="View offer history">View</a>';
                    if($offer->status == 'open')
                    {
                        $action .= '<a href="' . route("getBuyerCounterOffer",['id'=>encrypt([$offer->id]),'type'=>'offer']) . '" class="ajax counterModel table-link" data-toggle="tooltip" data-placement="top"  data-target="#offer-modal" a_type="bid" title="Increase max Bid">Increase max Bid</a>';
                    }

                    $action .= '<a href="' . route("productSlugUrl",[$offer->products['product_slug']]) . '" class="ajax table-link" data-toggle="tooltip" data-placement="top" title="Pay Now">Pay Now</a>';
                    return $action;
                })
                ->addColumn('offer_price', function ($offer) {
                    $offer_price = '';
                    $e = end($offer->offerDetails);
                    $a = end($e);
                    $offer_price .= $a['amount'];
                    return $offer_price;
                })
                ->addColumn('offer_status', function ($offer) {
                    $offer_status = 'Open';
                    if($offer->status == 'accept')
                    {
                      $offer_status = 'Accepted';
                    }
                    if($offer->status == 'reject')
                    {
                      $offer_status = 'Rejected';
                    }
                    
                    return $offer_status;
                })
                ->addColumn('name', function ($offer) {
                    $name           = '';
                    $image          = '<div class="thumbbox-table"><img src="'.getImageFullPath($offer->products['file'][0]['path'],'products','thumbnail').'"/></div>';
                    $seller_name    = $offer->users['first_name'].' '.$offer->users['last_name'];

                    $name .= $image.'<p class="thumbbox-name"><span> '.$offer->products['name'].'</span> <span>'.$seller_name.'</span></p>';
                    return $name;
                })
                ->addColumn('base_price', function ($offer) {
                    $base_price = '';
                    $base_price .= $offer->products['base_price'];
                    return $base_price;
                })
                ->addColumn('number_of_attempted', function ($offer) {
                    $number_of_attempted = '';
                    $number_of_attempted .= $offer->offerDetails->count();
                    return $number_of_attempted;
                })
                ->addColumn('counter_offer', function ($offer) {
                    $counter_offer = '';
                    $e = end($offer->offerDetails);
                    $a = end($e);
                    if(!empty($a['counter_offer']))
                    {
                        $counter_offer .= '$'.$a['counter_offer'];
                    }else{
                        $counter_offer .= '-';
                    }
                    return $counter_offer;
                })
                ->addColumn('offer_quantity', function ($offer) {
                    $offer_quantity = '';
                    $e = end($offer->offerDetails);
                    $a = end($e);
                    $offer_quantity .= $a['offer_quantity'];
                    return $offer_quantity;
                })
                ->make(true);
    }

    public function getBuyerCounterOffer($offerID,$type)
    {
        $offer_id     = decrypt($offerID);
        $where        = ['id' => $offer_id];
        $offer        = Offers::getOfferCommunication($where);

        //dd($offer);
        //dd($offer[0]->offerAllDetails);

        if(!empty(\Auth::user()->image))
        {
            $loginSellerUserData['login_image'] = URL("/assets/front/img/upload/".\Auth::user()->id.'/'.\Auth::user()->image );
        }else if(\Auth::user()->gender == 'Male')
        {
            $loginSellerUserData['login_image'] = URL("/assets/front/img/upload/user-male.png" );
        }else if(\Auth::user()->gender == 'Female')
        {
            $loginSellerUserData['login_image'] = URL("/assets/front/img/upload/user-female.png" );
        }

        $sellerData = \App\Models\User::checkUserData(['id'=>$offer[0]->seller_id]);
        //dd($sellerData);
        if(!empty($sellerData['image']))
        {
            $loginSellerUserData['seller_image'] = URL("/assets/front/img/upload/".$sellerData['id'].'/'.$sellerData['image'] );
        }else if(\Auth::user()->gender == 'Male')
        {
            $loginSellerUserData['seller_image'] = URL("/assets/front/img/upload/user-male.png" );
        }else if(\Auth::user()->gender == 'Female')
        {
            $loginSellerUserData['seller_image'] = URL("/assets/front/img/upload/user-female.png" );
        }

        return view('front.buy.offer.counter_offers', compact('offer','loginSellerUserData','sellerData','type'));
    }

    public function buyerSendCounterOffer(Request $request,$offerID)
    {

        $amount     = $request->input('amount');
        $customValidationMessage = ['amount.min'=>'amount must be grater than your last offer amount.'];
        $offer_id   = decrypt($offerID);
        $where      = ['id' => $offer_id];

        $offer              = Offers::getProductOfferDetails($where);
        $a                  = end($offer[0]->offerDetails);
        $lastOfferDetailsID = end($a);

        
        /* ---------- Validation check ---------- */
        $validations = [
            'amount' => 'required|numeric|min:'.$lastOfferDetailsID->amount,
        ];

        /* ---------- Validates inputs ---------- */
        $this->validate($request, $validations, $customValidationMessage);

        try{

            
            
            
            $offer_details_data['offers_id']         =   $offer_id;
            $offer_details_data['user_type']         =   'buyer';
            $offer_details_data['offer_quantity']    =   $lastOfferDetailsID->offer_quantity;
            $offer_details_data['note']              =   '';
            $offer_details_data['amount']            =   $amount;
            $offer_details_data['offer_status']      =   'Submitted';
            
            $offerData   =  \App\Models\offerDetails::create($offer_details_data);
                        
            /* Send buyer counter offer notification */
            $sellerDetails   = \App\Models\User::checkUserData(['id'=>$offer[0]->seller_id]);
            
            $toEmail    = 'bahadur.deol@indianic.com';//$sellerDetails['email'];
            $toName     = $sellerDetails['first_name'].' '.$sellerDetails['last_name'];
            $fromName   = 'InSpree';
            $fromEmail  = 'offer@inspree.com';
            $subject    = 'InSpree - New offer send by buyer';
            $body       = 'Dear Seller,<br/><br/> New offer send by buyer.<br/><br/> Thanks,<br/>InSpree Team';

            sendOfferNotification($toEmail,$toName,$fromName,$fromEmail,$subject,$body);

            \Flash::success("Counter offer send successfully!");
            return response()->json(['status' => 'success', 'redirectUrl' => route("buyerOffers")]);

        } catch(\Exception $e){
            \Flash::error("There is some error");
            return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $e->getMessage()]]);
        }

    }
}