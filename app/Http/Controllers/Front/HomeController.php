<?php

namespace App\Http\Controllers\Front;
use Cart;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\AdvertisementHome;
use App\Models\Product;
use App\Models\occasions;

use App\Http\Controllers\Controller;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $AdvertisementHome;
    public $Product;

    public function __construct() {
        $this->AdvertisementHome = new AdvertisementHome();
        $this->Product = new Product();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       
        $where = ['status' => 'Active'];
        $favorite_items=array();
        $advertisements['Main_Box']=$this->AdvertisementHome->getAdvertisements('Main_Box');
        $advertisements['Banner']=$this->AdvertisementHome->getAdvertisements('Banner');
        $products['Featured_Products']=$this->Product->getFeaturedProducts($where)->shuffle();
        $products['PopularProducts']=$products['Featured_Products']->shuffle();
        $cart = \Cart::instance('favorite')->content();
        foreach(Cart::instance('favorite')->content() as $row){
            $favorite_items[]=$row->id;
        }

        //dd($products);

        return view('front.home.index', compact('advertisements','products','favorite_items'));
    }
    public function getproducts() {
            $products=$this->Product->getPopularProducts();
            return view('front.cart.product', compact('products'));
    }
}
