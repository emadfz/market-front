<?php

namespace App\Http\Controllers\Front;
use Cart;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\AdvertisementHome;
use App\Models\Product;
use App\Donations;
use App\Models\ProductSku;
use App\Models\Shoppingcart;
use App\Http\Controllers\Controller;

class ShoppingcartController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $AdvertisementHome;
    public $Product;
    public $ProductSku;

    public function __construct() {
        $this->AdvertisementHome = new AdvertisementHome();
        $this->Product = new Product();
        $this->ProductSku = new ProductSku();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('front.cart.cart');
    }
    public function cartSku(Request $request) {
        $request->input('name');
        
    //increment the quantity
         if ($request->product_ids){
             for($i=0;$i<count($request->product_ids);$i++){
                $rowId=$request->product_ids[$i];
                Cart::remove($rowId);
             } 
        }
        $ID=$request->product_id;
       
    if ($request->product_id && $request->increment == 1) {
        $item = \Cart::get($ID);
        \Cart::update($ID, $item->qty + 1);
    } else
    if ($request->product_id && $request->decrease == 1) {
        
        $item = \Cart::get($ID);

        if($item){
            \Cart::update($ID, $item->qty - 1);
        }
    } else if($request->product_id){
            $product_id = $request->product_id;
            $productSku=ProductSku::find($product_id);
            $product = Product::find($productSku->product_id);
            if(isset($product->files[0]) && $product->files[0]->path != ''){
               $image= $product->files[0]->path;
            }else{
                $image= '2016-10-07-12-17-07-featured-img-4.jpg';
            }
            
            $cartno=\Cart::instance('shopping')->search(function ($cartItem, $rowId) use ($product_id){
                return $cartItem->id === $product_id;
            });
            
            if($cartno->count() > 0){
                \Cart::instance('shopping')->add(array('id' => $product_id, 'name' => $product->name, 'qty' => 1, 'price' => $productSku->final_price, 'options' => ['image' => $image, 'manufacturer' => $product->manufacturer]));    
            }else{
                \Cart::instance('default')->add(array('id' => $product_id, 'name' => $product->name, 'qty' => 1, 'price' => $productSku->final_price, 'options' => ['image' => $image, 'manufacturer' => $product->manufacturer]));            
            } 
        }else if($request->favorite_product_id){
            $product_id = $request->favorite_product_id;
            $productSku=ProductSku::find($product_id);
            $product = Product::find($productSku->product_id);
            
            if(isset($product->files[0]) && $product->files[0]->path != ''){
                $image= $product->files[0]->path;
            }else{
                $image= '2016-10-07-12-17-07-featured-img-4.jpg';
            }
                \Cart::instance('favorite')->add(array('id' => $product_id, 'name' => $product->name, 'qty' => 1, 'price' => $productSku->final_price, 'options' => ['image' => $image, 'manufacturer' => $product->manufacturer]));            
        }
        $cart = \Cart::content();
        
        if ($request->ajax()) {
            return response()->json([
                        'status' => 'success',
                        'count' => count(\Cart::content())
            ]);
        } else {
            return view('front.cart.cart', array('cart' => $cart, 'title' => 'Welcome', 'description' => '', 'page' => 'home'));
        }
        
    }
    public function cart(Request $request) {
        $request->input('name');
        
    //increment the quantity
         if ($request->product_ids){
             for($i=0;$i<count($request->product_ids);$i++){
                $rowId=$request->product_ids[$i];
                Cart::remove($rowId);
             } 
        }
        $ID=$request->product_id;
       
    if ($request->product_id && $request->increment == 1) {
        $item = \Cart::get($ID);
        \Cart::update($ID, $item->qty + 1);
    } else
    if ($request->product_id && $request->decrease == 1) {
        
        $item = \Cart::get($ID);

        if($item){
            \Cart::update($ID, $item->qty - 1);
        }
    } else if($request->product_id){
        
            /*$product_id = $request->product_id;
            $product = Product::find($product_id);
            */
        /*
        $rowId=$request->product_ids[$i];
                $cart_default=\Cart::get($rowId);
                //dd($cart_default);
                $product_id = $cart_default->id; */
                $product_id = $request->product_id;
                $productSku=ProductSku::find($product_id);
                $product = Product::find($productSku->product_id);
       
        
        
        if(isset($product->files[0]) && $product->files[0]->path != ''){
               $image= $product->files[0]->path;
            }else{
                $image= '2016-10-07-12-17-07-featured-img-4.jpg';
            }
            
            $cartno=\Cart::instance('shopping')->search(function ($cartItem, $rowId) use ($product_id){
                return $cartItem->id === $product_id;
            });
            
            if($cartno->count() > 0){
                \Cart::instance('shopping')->add(array('id' => $product_id, 'name' => $product->name, 'qty' => 1, 'price' => $product->base_price, 'options' => ['image' => $image, 'manufacturer' => $product->manufacturer]));    
            }else{
                \Cart::instance('default')->add(array('id' => $product_id, 'name' => $product->name, 'qty' => 1, 'price' => $product->base_price, 'options' => ['image' => $image, 'manufacturer' => $product->manufacturer]));            
            } 
        }else if($request->favorite_product_id){
             $product_id = $request->favorite_product_id;
            $productSku=ProductSku::find($product_id);
            $product = Product::find($productSku->product_id);
            
            if(isset($product->files[0]) && $product->files[0]->path != ''){
                $image= $product->files[0]->path;
            }else{
                $image= '2016-10-07-12-17-07-featured-img-4.jpg';
            }
                \Cart::instance('favorite')->add(array('id' => $product_id, 'name' => $product->name, 'qty' => 1, 'price' => $productSku->final_price, 'options' => ['image' => $image, 'manufacturer' => $product->manufacturer]));            
        }
        $cart = \Cart::content();
        
        if ($request->ajax()) {
            return response()->json([
                        'status' => 'success',
                        'count' => count(\Cart::content())
            ]);
        } else {
            return view('front.cart.cart', array('cart' => $cart, 'title' => 'Welcome', 'description' => '', 'page' => 'home'));
        }
        
    }
    public function removefromwishlist(Request $request) {
        $request->input('name');
        
    //increment the quantity
         if ($request->product_id){
                $rowId=$request->product_id;
                \Cart::instance('favorite')->remove($rowId);
        }
        $cart = \Cart::instance('favorite')->content();
        
        if ($request->ajax()) {
            return response()->json([
                        'status' => 'success',
                        'html' => view('front.cart.wishlist', compact('cart'))->render(),
            ]);
        } else {
            return view('front.cart.wishlist', array('cart' => $cart, 'title' => 'Welcome', 'description' => '', 'page' => 'home'));
        }
        
    }
    
    public function removefromcart(Request $request) {
        $request->input('name');
        
    //increment the quantity
         if ($request->product_id){
                $rowId=$request->product_id;
                \Cart::instance('default')->remove($rowId);
        }
        $cart = \Cart::instance('default')->content();
        
        if ($request->ajax()) {
            return response()->json([
                        'status' => 'success',
                        'html' => view('front.cart.bascket', compact('cart'))->render(),
            ]);
        } else {
            return view('front.cart.bascket', array('cart' => $cart, 'title' => 'Welcome', 'description' => '', 'page' => 'home'));
        }
        
    }
    public function removefromwatchlist(Request $request) {
        $request->input('name');
        
    //increment the quantity
         if ($request->product_id){
                $rowId=$request->product_id;
                \Cart::instance('watchlist')->remove($rowId);
        }
        $cart = \Cart::instance('watchlist')->content();
        
        if ($request->ajax()) {
            return response()->json([
                        'status' => 'success',
                        'html' => view('front.cart.watchlist', compact('cart'))->render(),
            ]);
        } else {
            return view('front.cart.watchlist', array('cart' => $cart, 'title' => 'Welcome', 'description' => '', 'page' => 'home'));
        }
        
    }    
    public function favorite(Request $request) {
        $request->input('name');

        $ID=$request->product_id;
       
    if($request->product_id){
            $product_id = $request->product_id;
                $productSku=ProductSku::find($product_id);
                $product = Product::find($productSku->product_id);;
            if(isset($product->files[0]) && $product->files[0]->path != ''){
                $image= $product->files[0]->path;
            }else{
                $image= '2016-10-07-12-17-07-featured-img-4.jpg';
            }
            
            $cartno=\Cart::instance('favorite')->search(function ($cartItem, $rowId) use ($product_id){
                return $cartItem->id === $product_id;
            });
            
            if($cartno->count() > 0){
                $rowId=$request->product_ids[$i];
                \Cart::instance('favorite')->remove($rowId);
            }else{
                \Cart::instance('favorite')->add(array('id' => $product_id, 'name' => $product->name, 'qty' => 1, 'price' => $productSku->final_price, 'options' => ['image' => $image, 'manufacturer' => $product->manufacturer]));            
            } 
        }else if($request->favorite_product_id){
           $product_id = $request->favorite_product_id;
                $productSku=ProductSku::find($product_id);
                //dd($productSku);
                $product = Product::find($productSku->product_id);
            if(isset($product->files[0]) && $product->files[0]->path != ''){
                $image= $product->files[0]->path;
            }else{
                $image= '2016-10-07-12-17-07-featured-img-4.jpg';
            }
                \Cart::instance('favorite')->add(array('id' => $product_id, 'name' => $product->name, 'qty' => 1, 'price' => $productSku->final_price, 'options' => ['image' => $image, 'manufacturer' => $product->manufacturer]));            
        }
        $cart = \Cart::content();
        
        if ($request->ajax()) {
            return response()->json([
                        'status' => 'success',
                        'count' => count(\Cart::content())
            ]);
        } else {
            return view('front.cart.wishlistcart', array('cart' => $cart, 'title' => 'Welcome', 'description' => '', 'page' => 'home'));
        }
        
    }
    public function watchlist(Request $request) {
        $request->input('name');

        $ID=$request->product_id;
       
    if($request->product_id){
            $product_id = $request->product_id;
                $productSku=ProductSku::find($product_id);
                $product = Product::find($productSku->product_id);;
            if(isset($product->files[0]) && $product->files[0]->path != ''){
                $image= $product->files[0]->path;
            }else{
                $image= '2016-10-07-12-17-07-featured-img-4.jpg';
            }
            /* remove from favorite Note: if product already available in favorite , Remove it*/
            $cartno=\Cart::instance('favorite')->search(function ($cartItem, $rowId) use ($product_id){
                return $cartItem->id === $product_id;
            });
            
            if($cartno->count() > 0){
                $rowId=$request->rowId;
                \Cart::instance('favorite')->remove($rowId);
            } 
            /* remove from favorite ends */
            $cartno=\Cart::instance('watchlist')->search(function ($cartItem, $rowId) use ($product_id){
                return $cartItem->id === $product_id;
            });
            
            if($cartno->count() > 0){
                $rowId=$request->product_ids[$i];
                \Cart::instance('watchlist')->remove($rowId);
            }else{
                \Cart::instance('watchlist')->add(array('id' => $product_id, 'name' => $product->name, 'qty' => 1, 'price' => $productSku->final_price, 'options' => ['image' => $image, 'manufacturer' => $product->manufacturer]));            
            } 
        }else if($request->favorite_product_id){
            $product_id = $request->favorite_product_id;
            $product = Product::find($product_id);
            if(isset($product->files[0]) && $product->files[0]->path != ''){
                $image= $product->files[0]->path;
            }else{
                $image= '2016-10-07-12-17-07-featured-img-4.jpg';
            }
                \Cart::instance('watchlist')->add(array('id' => $product_id, 'name' => $product->name, 'qty' => 1, 'price' => $product->base_price, 'options' => ['image' => $image, 'manufacturer' => $product->manufacturer]));            
        }
        $cart = \Cart::content();
        
        if ($request->ajax()) {
            return response()->json([
                        'status' => 'success',
                        'count' => count(\Cart::content())
            ]);
        } else {
            return view('front.cart.watchlistcart', array('cart' => $cart, 'title' => 'Welcome', 'description' => '', 'page' => 'home'));
        }
        
    }
    public function incrementcart(Request $request) {
        $request->input('name');
        $ID=$request->product_id;
        if ($request->product_id && $request->increment == 1) {
        $item = \Cart::get($ID);
        \Cart::update($ID, $item->qty + 1);
    }
    $cart = \Cart::content();
        if ($request->ajax()) {
                return response()->json([
                            'status' => 'success',
                            'html' => view('front.cart.bascket', compact('cart'))->render(),
                ]);
        }
    }    
    public function decrease(Request $request) {
        $request->input('name');
        $ID=$request->product_id;
        if ($request->product_id && $request->decrease == 1) {
        $item = \Cart::get($ID);
        if($item){
            \Cart::update($ID, $item->qty - 1);
        }
    }
    $cart = \Cart::content();
        if ($request->ajax()) {
                return response()->json([
                            'status' => 'success',
                            'html' => view('front.cart.bascket', compact('cart'))->render(),
                ]);
        }
    }
    public function removecart(Request $request) {
        $request->input('name');
        if ($request->product_ids){
             for($i=0;$i<count($request->product_ids);$i++){
                $rowId=$request->product_ids[$i];
                Cart::remove($rowId);
             } 
        }
    $cart = \Cart::content();
        if ($request->ajax()) {
                return response()->json([
                            'status' => 'success',
                            'html' => view('front.cart.bascket', compact('cart'))->render(),
                ]);
        }
    }
    public function removefavorite(Request $request) {
        $request->input('name');
        if ($request->product_ids){
             for($i=0;$i<count($request->product_ids);$i++){
                $rowId=$request->product_ids[$i];
                Cart::instance('favorite')->remove($rowId);
             } 
        }
    $cart = \Cart::instance('favorite')->content();
        if ($request->ajax()) {
                return response()->json([
                            'status' => 'success',
                            'html' => view('front.cart.wishlist', compact('cart'))->render(),
                ]);
        }
    }    
    public function cart_to_order(Request $request) {
        
     
        $request->input('name');
        //dd($request);
        if ($request->product_ids){
             for($i=0;$i<count($request->product_ids);$i++){
                $rowId=$request->product_ids[$i];
                $cart_default=\Cart::get($rowId);
                //dd($cart_default);
                $product_id = $cart_default->id;
                $productSku=ProductSku::find($product_id);
                $product = Product::find($productSku->product_id);
                
                $image= $product->files[0]->path;
                \Cart::instance('shopping')->add(array('id' => $product_id, 'name' => $product->name, 'qty' => $cart_default->qty, 'price' => $productSku->final_price, 'options' => ['image' => $image, 'manufacturer' => $product->manufacturer]));
                \Cart::instance('default')->remove($rowId);
             } 
        }
    $cart = \Cart::content();
        if ($request->ajax()) {
                return response()->json([
                            'status' => 'success',
                            'html' => view('front.cart.updatebascket', compact('cart'))->render(),
                ]);
        }
    }
    public function remove_bascket(Request $request) {
        $request->input('name');
        if ($request->product_ids){
             for($i=0;$i<count($request->product_ids);$i++){
                $rowId=$request->product_ids[$i];
                Cart::remove($rowId);
             } 
        }
    $cart = \Cart::content();
        if ($request->ajax()) {
                return response()->json([
                            'status' => 'success',
                            'html' => view('front.cart.updatebascket', compact('cart'))->render(),
                ]);
        }
    }    
public function remove_bascket_shopping(Request $request) {
        $request->input('name');
        if ($request->product_ids){
             for($i=0;$i<count($request->product_ids);$i++){
                $rowId=$request->product_ids[$i];
                Cart::instance('shopping')->remove($rowId);
             } 
        }
    $cart = \Cart::instance('shopping')->content();
        if ($request->ajax()) {
                return response()->json([
                            'status' => 'success',
                            'html' => view('front.cart.updatebascket', compact('cart'))->render(),
                ]);
        }
    }    
    
    public function updateqtycart(Request $request) {
        $request->input('name');
           $ID=$request->product_id;
        if ($request->product_id && $request->qty) {
        $item = \Cart::get($ID);
        if($item){
            \Cart::update($ID, $request->qty);
        }
    }
    $cart = \Cart::content();
        if ($request->ajax()) {
                return response()->json([
                            'status' => 'success',
                            'html' => view('front.cart.bascket', compact('cart'))->render(),
                ]);
        }
    }
public function updateqtyshoppingcart(Request $request) {
        $request->input('name');
           $ID=$request->product_id;
        if ($request->product_id && $request->qty) {
        $item = \Cart::instance('shopping')->get($ID);
        if($item){
            \Cart::instance('shopping')->update($ID, $request->qty);
        }
    }
    $cart = \Cart::instance('shopping')->content();
        if ($request->ajax()) {
                return response()->json([
                            'status' => 'success',
                            'html' => view('front.cart.updatebascket', compact('cart'))->render(),
                ]);
        }
    }    

    public function checkout(Request $request) {
        $request->input('name');
        if($request->product_id){
            $product_id = $request->product_id;
            $product = Product::find($product_id);
            $image= $product->files[0]->path;
            \Cart::add(array('id' => $product_id, 'name' => $product->name, 'qty' => 1, 'price' => $product->price, 'options' => ['image' => $image, 'manufacturer' => $product->manufacturer]));
        }
        
//increment the quantity
    if ($request->product_id && $request->increment == 1) {
        $rowId = \Cart::search(array('id' => Request::get('product_id')));
        $item = \Cart::get($rowId[0]);

        \Cart::update($rowId[0], $item->qty + 1);
    }

    //decrease the quantity
    if ($request->product_id && $request->decrease == 1) {
        $rowId = \Cart::search(array('id' => $request->product_id));
        $item = \Cart::get($rowId[0]);

        \Cart::update($rowId[0], $item->qty - 1);
    }
        $cart = \Cart::content();
        $donations = Donations::get();

        return view('front.cart.updatecart', array('cart' => $cart, 'title' => 'Welcome', 'description' => '', 'page' => 'home', 'donations' => $donations));
    }
    public function add() {
        
        \Cart::add(['id' => $id, 'name' => $name, 'qty' => $qty, 'price' => $price, 'options' => ['size' => 'large']]);
    } 
  
    public function payment() {
        
        return view('front.cart.payment');
    }
    public function update() {
        
        \Cart::add(['id' => $id, 'name' => $name, 'qty' => $qty, 'price' => $price, 'options' => ['size' => 'large']]);
    }
}