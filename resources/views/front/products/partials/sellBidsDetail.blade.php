@extends('front.sell.layout')

@section('pageContent')
<!--Bids Modal Start-->
      
      	<div class="clearfix">
            <div class="col-md-3 col-sm-3">
            	
            </div>            
            <div class="col-md-9 col-sm-9 verticalline">
            	<div class="innerbid">
            	<h6>Auction Activity</h6>
            	<div class="table-responsive">
              <ul class="order-review">
                  <li>Bidders:<span id="bidders">{{$auctionBids->groupBy('user_id')->count()}}</span></li>
                <li>Bids:<span id='total_bids'>{{$auctionBids->count()}}</span></li>
                <li>Expiration:
                    <span id='expiration'>
                        {{ !!$auction_ended=0}}
                        @if( \Carbon\Carbon::now()->format('Y-m-d H:i:s') >= date("Y-m-d H:i:s",  strtotime($product->auction->end_datetime)) )
                        <span style="color:blue">Auction ended</span>
                            {{ ! $auction_ended=1}}
                        @endif
                    </span>
                </li>
              </ul> 	
              <table class="table table-bordered biduser-table">
                <thead>
                  <tr>
                    <th class="col1">bidder</th>
                    <th class="col2">Bid amount</th>
                    <th class="col3">bid time</th>
                    <th class="col3">Status</th>
                  </tr>
                </thead>
                <tbody id="bid_row">
                    @foreach($auctionBids as $key=>$auctionBid)
                            <tr data-id="{{$auctionBid->user_id}}">
                                <td class="col1">{{$auctionBid->username}}</td>
                                                             
                                <td class="col2"><span class="bid_price"><?php print(convert_currency($auctionBid->bid_amount, session()->get('currency_rate')));?> </span></td>
                                <td class="col3">{{$auctionBid->datetime}}</td>                                
                                @if( $key==0 && \Carbon\Carbon::now()->format('Y-m-d H:i:s') >= date("Y-m-d H:i:s",  strtotime($product->auction->end_datetime)) )
                                    <td class="col3" style="color:blue">Won</td>
                                @elseif( $key==0)
                                    <td class="col3">Top Bid</td>
                                @else   
                                    <td class="col3">Lose</td>
                                @endif
                            </tr>
                    @endforeach                    
                </tbody>
              </table>
              
            </div>
                </div>
            </div>
        </div>
			
           
            <hr>
            
      
<?php //Config::get('app.timezone') ?>

<!--Bids Modal End-->
@endsection
@push('scripts')
<script src="{{ asset('assets/front/js/moment.min.js') }}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.10/moment-timezone.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.10/moment-timezone-utils.js" type="text/javascript"></script>
<script src="{{ asset('assets/front/js/jquery.plugin.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/front/js/jquery.countdown.js') }}" type="text/javascript"></script>




<script>    
            var auction_end_time=new Date('{{ date("Y/m/d H:i:s",  strtotime($product->auction->end_datetime)) }}');
            var auction_ended = {{$auction_ended}}  
            //alert(moment.tz(auction_end_time, "America/Sao_Paulo"));
            //zone="America/Los_Angeles";
            //moment.tz(auction_end_time,"YYYY/MM/DD",zone);
            
            if(auction_ended==0){
                $('#expiration').countdown(
                     { 
                        until: auction_end_time,
                        timezone: 0,
                        format: 'd H M S',
                        layout: '{dn} {dl}  {hn} {hl} {mn} {ml} {sn} {sl} ',
                        onExpiry: function(){
                                $('#expiration').html('<b style="color:blue">Auction ended</b>');
                                window.location.reload();
                            },
                    }
                );
            }
            
</script>
@endpush