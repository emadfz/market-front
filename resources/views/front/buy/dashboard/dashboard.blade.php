@extends('front.buy.layout')

@section('pageContent')

<!--Leftside End -->
<!--Rightside Start -->
<div class="rightcol-bg buy-dashboard clearfix">
    <div class="equal-column">
        <h4>Dashboard</h4>
        <!--Buy manage order Table Start-->
        <div class="panel-group witharrow" id="accordion" role="tablist" aria-multiselectable="true">
            <!--Buy Recently Purchase item Start-->
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title"> <a role="button" data-toggle="collapse" data-parent="#accordion" href="#buyPurchase" aria-expanded="true"> Recently Purchased Items<small>({{count($recent_products)}})</small><span>view all</span></a> </h4>
                </div>
                <div id="buyPurchase" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered buyPurchasetable mobile-table">
                                <thead>
                                <tr>
                                    <th class="col1">Product</th>
                                    <th class="col2">Status</th>
                                    <th class="col3">Price</th>
                                </tr>
                                </thead>
                                @if(isset($recent_products) && !empty($recent_products))
                                <tbody>
                                    @foreach($recent_products as $order)
                                     <tr>
                                         <td class="col1" data-title="Product">
                                             <div class="thumbbox-table"><img src="bootstrap/img/xs-thumb.jpg" width="46" height="46" alt=""> </div>
                                             <p class="thumbbox-name"> {{@$order->ProductSku->product->name}}</p>
                                         </td>
                                         <td class="col2" data-title="Status">{{@$order->order_status}}</td>
                                         <td class="col3" data-title="Price">{{@$order->ProductSku->final_price}}</td>
                                     </tr>
                                     @endforeach
                                </tbody>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--Buy Recently Purchase item End-->
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#buyreturn" aria-expanded="false">Recently Returned Items<small>({{count($return_product_delivered)}})</small><span>view all</span> </a> </h4>
                </div>
                <div id="buyreturn" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="panel-body"> 
                    
                    <table class="table table-bordered buyPurchasetable mobile-table">
                                <thead>
                                <tr>
                                    <th class="col1">Product</th>
                                    <th class="col2">Status</th>
                                    <th class="col3">Price</th>
                                </tr>
                                </thead>
                                <tbody>
                               @foreach($return_product_delivered as $order)
                                <tr>
                                    <td class="col1" data-title="Product">
                                        <div class="thumbbox-table"><img src="bootstrap/img/xs-thumb.jpg" width="46" height="46" alt=""> </div>
                                        <p class="thumbbox-name"> {{$order->ProductSku->product->name}}</p>
                                    </td>
                                    <td class="col2" data-title="Status">{{$order->order_status}}</td>
                                    <td class="col3" data-title="Price">{{$order->ProductSku->final_price}}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingThree">
                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#buyFavorite" aria-expanded="false">Recent Favorite Items<small>(32)</small><span>view all</span> </a> </h4>
                </div>
                <div id="buyFavorite" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                    <div class="panel-body"> 32 </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingFour">
                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#buyBids" aria-expanded="true">Recently Placed Bids<small>({{count($Product)}})</small><span>view all</span></a> </h4>
                </div>
                <div id="buyBids" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                    <div class="panel-body"> 
                    <table class="table table-bordered buyPurchasetable mobile-table">
                                <thead>
                                <tr>
                                    <th class="col1">Product</th>
                                    <th class="col3">Bid</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php $i=0; ?>
                               @foreach($Product as $prd)
                                <tr>
                                    <td class="col1" data-title="Product">
                                        <div class="thumbbox-table"><img src="bootstrap/img/xs-thumb.jpg" width="46" height="46" alt=""> </div>
                                        <p class="thumbbox-name"> {{$prd->name}}</p>
                                    </td>
                                    <td class="col3" data-title="Price">{{$recent_AuctionBids[$i]['bid_amount']}}</td>
                                </tr>
                                <?php $i++;?>
                                @endforeach
                                </tbody>
                            </table>
                    
                    
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingFive">
                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#buyOffers" aria-expanded="false">Recently Made Offers<small>(5)</small><span>view all</span> </a> </h4>
                </div>
                <div id="buyOffers" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                    <div class="panel-body"> 45 </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingSix">
                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#buyPreviews" aria-expanded="false">Recently Requested Previews<small>(5)</small><span>view all</span> </a> </h4>
                </div>
                <div id="buyPreviews" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
                    <div class="panel-body"> 45 </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingSeven">
                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#buyAdvertising" aria-expanded="false">Recently Purchased Advertising<small>({{count($advertisement)}})</small><span>view all</span> </a> </h4>
                </div>
                <div id="buyAdvertising" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
                    <div class="panel-body"> 
                    <table class="table table-bordered buyPurchasetable mobile-table">
                                <thead>
                                <tr>
                                    <th class="col1">Advertising</th>
                                    <th class="col3">Location</th>
                                </tr>
                                </thead>
                                <tbody>
                               @foreach($advertisement as $advr)
                                <tr>
                                    <td class="col1" data-title="Product">
                                        <div class="thumbbox-table"><img src="bootstrap/img/xs-thumb.jpg" width="46" height="46" alt=""> </div>
                                        <p class="thumbbox-name"> {{$advr->advr_name}}</p>
                                    </td>
                                    <td class="col3" data-title="Price">{{$advr->location}}</td>
                                </tr>
                                <?php $i++;?>
                                @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
        <!--Buy manage order Table End-->
    </div>
</div>

@endsection
