<?php

namespace App\Http\Controllers\Front;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use DB;
use Datatables;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $Order;

    public function __construct() {
        $this->Order = new Order();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $all_orders['orders']=$this->Order->getOrders();
        // return $all_orders;
        return view('front.buy.order.index', compact('all_orders'));
    }
    public function datatableList(Request $request) {
        $all_orders = $this->Order->getOrders();
        
        $hasPermission['update'] = TRUE; // FALSE;
        $hasPermission['delete'] = TRUE; // FALSE;
        return Datatables::of($all_orders)
                         ->addColumn('action', function ($order) {
                            return '<a href="javascript: void(0);" onclick="cancel_order('.$order->id.');" title="Cancel Order" class="table-link">Cancel Order</a>
                                    <a href="#" title="Return" class="table-link">Return</a>
                                    <a href="#" title="Feedback Seller" class="table-link">Feedback Seller</a>
                                    <a href="#" title="Review Products" class="table-link">Review Products</a>';
                                })
                         ->editColumn('shoppingcart_identifier', function ($order) {
                            return '<a href="' . route('orderdetail', encrypt($order->shoppingcart_identifier)) . '"  data-toggle="tooltip" data-placement="top" title="Edit">'.$order->shoppingcart_identifier.'</a>';
                                })       
                         ->editColumn('order_status', function ($order) {
                            return '<span id="status_id_'.$order->id.'">'.$order->order_status.'</span>';
                                })       
                        ->make(true);
    }

   
    public function orderdetail(Request $request,$id) {
        // return "order detail page";  

        $id = decrypt($id);
        $order =Order::where('shoppingcart_identifier', $id)->first();
        $order_billing  = $order->billingDetails;
        $order_payment  = $order->paymentDetails;
        $order_shipment = $order->shipmentDetails;
        
        return view('front.buy.order.orderdetail', compact('order','order_billing'
            , 'order_payment','order_shipment'));        
    }
    public function orderInvoice(Request $request,$id) {
        // return "order detail page";  

        $id = decrypt($id);
        $order =Order::where('shoppingcart_identifier', $id)->first();
        $order_billing  = $order->billingDetails;
        $order_payment  = $order->paymentDetails;
        $order_shipment = $order->shipmentDetails;
        $title = 'Inspree - Order Invoice';
        return view('front.buy.order.invoice', compact('order','order_billing'
            , 'order_payment','order_shipment', 'title'));        
    }




    public function cancel_order(Request $request) {
        
         if ($request->id){
             $this->Order->cancel_order($request->id);
        }
        if ($request->ajax()) {
            \Flash::success(trans('Order Cancelled'));
        }
        return response()->json([
                            'status' => 'success',
                ]);
    }
}