
      
      	<div class="clearfix">
            <div class="col-md-5 col-sm-5">
     
                
                    <div class="form-horizontal" id="make_new_bid">
                        <h3> advertisement info.</h3> 
                        <p>Add Name : {{$ad->advr_name}}</p>
						<p>Type : {{ $ad->type}}  </p> 
						<p>Location :  {{$ad->location }} </p> 
 
                             
                    </div>
                    
                
            </div>            
            <div class="col-md-7 col-sm-7 verticalline">
            	<div class="innerbid">
            	<h6>Adv. History</h6>
              
              <table class="table table-bordered aidhistory-table">
                <thead>
                  <tr>
                    <th class="col1">Id</th>
                    <th class="col2">From</th>
                    <th class="col3">To</th>
                    <th class="col4">Time</th>
                        
{{--     <th> $log->new_status  </th>
    <th> $log->created_at  </th> --}}
                  </tr>
                </thead>
                <tbody id="bid_row">
                   @foreach($log as $log_s)
                            <tr data-id="{{$log_s->id}} ">
                            <td class="col1"> {{$log_s->id}}</td>
                            <td class="col2">{{$log_s->old_status}} </td>
                            <td class="col3">{{$log_s->new_status}}</td>
                            <td class="col4">{{$log_s->created_at}}</td>
                            </tr>
                   @endforeach()
                            
                </tbody>
              </table>
              
           
                </div>
            </div>
        </div>
			
           
    
        
<script>
        var auction_end_time=new Date('  date("Y/m/d H:i:s",  strtotime($product->auction->end_datetime))   ');                            
        var auction_ended =  $auction_ended  
        if(auction_ended==1){
            $('#expiration').countdown(
                { 
                    until: auction_end_time,
                    timezone: 0,
                    format: 'd H M S',
                    layout: '{dn} {dl}  {hn} {hl} {mn} {ml} {sn} {sl} ',
                    onExpiry: function(){
                            $('#expiration').html('<b style="color:blue">Auction ended</b>');
                            $('#place-bid').remove();
                            $('#make_new_bid').remove();
                        },
                }
            );
        }
        else{
            $('#expiration').html('<b style="color:blue">Auction ended</b>');
        }
</script>