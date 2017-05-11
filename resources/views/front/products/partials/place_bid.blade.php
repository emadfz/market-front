<!--Bids Modal Start-->
      
      	<div class="clearfix">
            <div class="col-md-5 col-sm-5">
            	<h6>Confirm Your Bid</h6>                
                <h5 class="bidprice">
                    Seller Bid Offer:
                    <span> <span id="seller_bid_offer"><?php print(convert_currency($product->auction->min_reserved_price, session()->get('currency_rate')));?></span>
                    </span>
                </h5>
                <h5>Shipping:<span>Free</span></h5>
                {{ !!$auction_ended=0}}
                {{ !$is_max_product_price_reached="no"}}
                @if($product->auction->mode=='By price' && 
                    $product->auction->max_product_price!=0 && 
                    (@$auctionBids->sortbyDesc('createdAt')->first()->bid_amount)>@$product->auction->max_product_price)
                    {{ !$is_max_product_price_reached="yes"}}
                @endif
                
                @if(\Carbon\Carbon::now() <= $product->auction->end_datetime && $is_max_product_price_reached=="no")
                    <div class="form-horizontal" id="make_new_bid">
                            <h3>Make a new bid</h3>
                            <div class="form-group">
                                <label class="control-label col-sm-3">US$</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="bid_amount" placeholder="XXXX">
                                </div>
                            </div>
                    </div>
                    {{ ! $auction_ended=1}}
                @endif
            </div>            
            <div class="col-md-7 col-sm-7 verticalline">
            	<div class="innerbid">
            	<h6>Bid History</h6>
            	<div class="table-responsive" style="height:540px;overflow:auto;">
              <ul class="order-review">
                <li>Bidders:<span id="bidders">{{$auctionBids->groupBy('user_id')->count()}}</span></li>
                <li>Bids:<span id='total_bids'>{{$auctionBids->count()}}</span></li>
                <li>Expiration:<span id='expiration'></span></li>
              </ul> 	
              <table class="table table-bordered biduser-table">
                <thead>
                  <tr>
                    <th class="col1">bidder</th>
                    <th class="col2">Bid amount</th>
                    <th class="col3">Bid Date and Time</th>
                  </tr>
                </thead>
                <tbody id="bid_row">
                    @foreach($auctionBids as $auctionBid)
                            <tr data-id="{{$auctionBid->user_id}}">
                            <td class="col1">{{$auctionBid->username}}</td>
                            <td class="col2">$<span class="bid_price">{{$auctionBid->bid_amount}}</span></td>
                            <td class="col3">{{$auctionBid->datetime}}</td>
                            </tr>
                    @endforeach                    
                </tbody>
              </table>
              
            </div>
                </div>
            </div>
        </div>
			
           
        
            <hr>
            <p>By Confirming Bid, You are entering into a legally binding agreement as described in 
                <a href="{{URL('/general-terms-and-conditions')}}" class="readmore">Terms and Conditions,</a>
                and committing to purchase the item(s), is bid is accepted. 
<!--                if made a mistake,you can retract your bid within 15 minutes.-->
            </p>
        @if($auction_ended==1)
            <div class="form-btnblock clearfix text-right">            	
                <input type="submit" title="Submit Bid" id="place-bid" class="btn btn-primary" value="Submit Bid">
            </div>
        @endif

<script>    
      
                
        
        
        var auction_end_time=new Date('{{ date("Y/m/d H:i:s",  strtotime($product->auction->end_datetime)) }}');
        
        var auction_ended = {{$auction_ended}}
        
        if(auction_ended==1){
    
            var myvar =$('#expiration').countdown(
                { 
                    until: auction_end_time,
                    timezone: 0,
                    format: 'd H M S',
                    layout: '{dn} {dl}  {hn} {hl} {mn} {ml} {sn} {sl} ',
                    onExpiry: function(){
                            $('#expiration').html('<b style="color:blue">Auction ended</b>');
                            $('#place-bid').remove();
                            $('#make_new_bid').remove();
                            liftOff('Auction Timeout!!');
                        },
                }
            );
    
        }
        else{
            $('#expiration').html('<b style="color:blue">Auction ended</b>');
        }
        
        
</script>