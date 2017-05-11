<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\OrderShipmentDetails;
use App\Models\Order;
use App\Models\Auction;
use App\Models\Product;
use App\Models\Advertisement;
use App\Http\Controllers\Controller;

class DashboardController extends Controller {
    //
    public $OrderShipmentDetails;
    public $Order;
    public $Auction;
    public $Product;    
    public $Advertisement;    
    
    public function __construct() {
        $this->OrderShipmentDetails = new OrderShipmentDetails();
        $this->Order = new Order();
        $this->Auction = new Auction();        
        $this->Product = new Product();                
        $this->Advertisement = new Advertisement();                        
    }
    public function seller(){
        
        return view('front.sell.dashboard.dashboard');
    }
     public function buyer()
    {
        $recent_products=$this->OrderShipmentDetails->recently_purchased_product();
        $return_product_delivered=$this->OrderShipmentDetails->recently_purchased_product('return_product_delivered');
        $recent_AuctionBids=$this->Auction->recent_AuctionBids()->toArray();
        $productIds=array_column($recent_AuctionBids,'productId');
        $Product=$this->Product->getProductsByIds($productIds);
        $advertisement=$this->Advertisement->getAdvertisement();
        return view('front.buy.dashboard.dashboard', compact('recent_products','return_product_delivered','recent_AuctionBids','Product','advertisement'));
    }
}
