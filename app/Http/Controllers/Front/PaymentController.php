<?php

namespace App\Http\Controllers\Front;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\AdvertisementHome;
use App\Models\Product;
use App\Models\shoppingcart;
use App\Models\OrderBillingDetails;
use App\Models\OrderPaymentDetails;
use App\Models\OrderShipmentDetails;
use App\Models\Order;
use App\Http\Controllers\Controller;
use VinceG\FirstDataApi\FirstData;
use Cart;
class PaymentController extends Controller 
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public $AdvertisementHome;
    public $Product;

    public function __construct() 
    {
        $this->AdvertisementHome = new AdvertisementHome();
        $this->Product = new Product();
    } 

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        $advertisements['Main_Box']=$this->AdvertisementHome->getAdvertisements('Main_Box');

        $advertisements['Banner']=$this->AdvertisementHome->getAdvertisements('Banner');
        $products['Featured_Products']=$this->Product->getFeaturedProducts();
        $products['PopularProducts']=$this->Product->getPopularProducts();
        return view('front.home.index', compact('advertisements','products'));

    }

    public function charge(Request $request) 
    { 

        $API_LOGIN = 'GC4746-35';
        $API_KEY = 'iAYlGXNs99ifGzRTeywnK5v30QinjGX5';
        $data=$request->all();
        // return $data;
        // return $data;
        $firstData = new FirstData($API_LOGIN, $API_KEY, true);

        // Charge
       
        $data['amount']=str_replace(",","",Cart::instance('shopping')->subtotal());
        $firstData->setTransactionType(FirstData::TRAN_PURCHASE);

        $firstData->setCreditCardType($data['card_type'])
                    ->setCreditCardNumber($data['card_number'])
                    ->setCreditCardName($data['card_name'])
                    ->setCreditCardExpiration(sprintf("%02d", $data['expiration_month']).$data['expiration_year'])
                    ->setAmount($data['amount'])
                    ->setReferenceNumber($data['ref_no']);

        if($data['zip']) 
        {
            $firstData->setCreditCardZipCode($data['zip']);
        }

        if($data['cvv']) 
        {
            $firstData->setCreditCardVerification($data['cvv']);
        }

        if($data['address1']) 
        {
            $firstData->setCreditCardAddress($data['address1']." ".$data['address2']." ".$data['city']." ".$data['state']." ".$data['country']);
        }

        $firstData->process();
        // Check
        
        if($firstData->isError()) {
            \Flash::error($firstData->getResponse());
           return redirect()->back();
        } 
        else 
        {
            // TURN TO ELOQUENT --EMAD ZAKI
            $content = \Cart::instance('shopping')->content();
            
            // function to save data to the shopping card and return the new record's id --Emad Zaki
            $shoppingcart = new shoppingcart;
            $shoppingcart_id = $shoppingcart->saveShoppingcart($data, $content);

            // function to save data to the order table and return the new record's id --Emad Zaki
            $order = new Order;
            $order_id = $order->saveOrder($data);

            //here insert the shipment details 
            $shipmentDetails = new OrderShipmentDetails;
            $shipmentDetails->saveShipmentDetails($data , $content ,$order_id);
            // here inset the order billing 
            $billingDetails = new OrderBillingDetails;
            $billingDetails->saveBillingDetails($data ,$order_id);
            // here inset the order payment 
            $paymentDetails = new OrderPaymentDetails;
            $paymentDetails->savePaymentDetails($data ,$order_id);
            
            \Cart::instance('shopping')->destroy();
            \Cart::instance('default')->destroy();            
            \Flash::success(trans('Success Payment'));
            return view('front.cart.success', compact($firstData->getResponse()));
        }    
    }
}