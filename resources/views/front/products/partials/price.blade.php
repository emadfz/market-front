<div class="price-btn">
    <div class="price-col">
        <h5>Price:</h5>
        <div class="price-show"><del><?php print(convert_currency( 650 , session()->get('currency_rate')));?></del><span id='final_price'><?php print(convert_currency( $product->base_price , session()->get('currency_rate')));?></span><small>(10% Off)</small></div>
    </div>
    <div class="button-col" id="btns_and_links">
        <div class="sellerlink clearfix">
            <a href="#" title="View Bulk Buy Option">View Bulk Buy Option</a>
            <a href="#" title="Add to Compare">Add to Compare</a>
        </div>
        <div class="clearfix">
            @if(Auth::check() && ($product['mode_of_selling'] == 'Make an offer' || $product['mode_of_selling'] == 'Buy it now and Make an offer' ))
            <a data-target="#offer-modal" href="{{URL('/getMakeAnOffer/'.encrypt($product->id).'/'.encrypt($product->user_id))}}"  class="makeModel btn btn-primary" title="Make An Offer">Make An Offer</a>
                
                <div class="modal in" role="dialog" id="offer-modal" tabindex="-1" aria-hidden="true" >
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            </div>
                            <div class="modal-body">
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <a href="#" class="btn btn-primary" title="Request A Preview">Request A Preview</a>
            @if(isset($product->auction) && \Auth::check())
                <a  data-toggle="modal" class="placeabid btn btn-primary" data-target="#bid-modal" href="{{URL('/getBids/'.encrypt($product->id).'/'.encrypt($product->user_id))}}"  title="Place Bid">Place Bid</a>
            @endif            
            <a href="#" class="btn btn-success" title="Make An Offer">Buy Now</a>                                        
            <span id="add_cart_btn"><a href="#" class="btn btn-primary" title="Add to cart" 
            onclick="addtocartSku({{@$product->productSkusVariantAttribute->first()->id}},1);">Add to cart</a></span>
        </div>
        <ul class="print-mail clearfix">
            <li class="print"><a href="#" title="Print"><span></span>print</a></li>
            <li class="email"><a href="#" title="Email"><span></span>email</a></li>
        </ul>                                                            
    </div>
</div>

    <div class="modal" id="bid-modal" tabindex="-1">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-inner clearfix">
                    <a href="#" class="close" data-dismiss="modal">close</a>                    
                            <div class="modal-body">
                                
                            </div>
                </div>
            </div>
        </div>
    </div>

 

@push('scripts')
<script>
    
    $(".ajaxModal").click(function(ev) {    
    if($('#pincode').val()==''){
        $('#pincode').css('border','solid red thin');        
        return false;
    }
    $('#pincode').css('border','');        
    //$.blockUI({ message: '<h3><img src="'+assetsPath+'assets/front/img/loading-spinner-grey.gif" /> Please Wait...</h3>' });
    ev.preventDefault();
    var target = $(this).attr("href");
    var modalId = $(this).data("target");
    
    //$(modalId+" .modal-body").html('<h3><img src="'+assetsPath+'assets/front/img/loading-spinner-grey.gif" /> Please Wait...</h3>');
    $("#shippingCalculator .modal-body").html('<h3><img src="'+assetsPath+'assets/front/img/loading-spinner-grey.gif" /> Please Wait...</h3>');
    // load the url and show modal on success
    $(modalId+" .modal-body").load(target, function() { 
         $(modalId).modal("show"); 
         //$.unblockUI();
    });
    });
    
    
    $(".makeModel").click(function(ev) {            
    
    //$.blockUI({ message: '<h3><img src="'+assetsPath+'assets/front/img/loading-spinner-grey.gif" /> Please Wait...</h3>' });
    ev.preventDefault();
    var target = $(this).attr("href");
    var modalId = $(this).data("target");
    
    //$(modalId+" .modal-body").html('<h3><img src="'+assetsPath+'assets/front/img/loading-spinner-grey.gif" /> Please Wait...</h3>');
    $("#shippingCalculator .modal-body").html('<h3><img src="'+assetsPath+'assets/front/img/loading-spinner-grey.gif" /> Please Wait...</h3>');
    // load the url and show modal on success
    $(modalId+" .modal-body").load(target, function() { 
         $(modalId).modal("show"); 
         //$.unblockUI();
    });
});    


    $(".placeabid").click(function(ev) {        
        if($('#expiration')){
            $('#expiration').countdown('toggle');
        }
        ev.preventDefault();
        var target = $(this).attr("href");
        var modalId = $(this).data("target");
        
        $(modalId+" .modal-body").html('<h3><img src="'+assetsPath+'assets/front/img/loading-spinner-grey.gif" /> Please Wait...</h3>');
        // load the url and show modal on success
        $(modalId+" .modal-body").load(target, function() { 
             $(modalId).modal("show"); 
             //$.unblockUI();
        });


        
    });
    


</script>   




<script src="{{ asset('assets/front/js/socket.io.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/front/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/front/js/jquery.plugin.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/front/js/jquery.countdown.js') }}" type="text/javascript"></script>

<script>    
                var min_bid_increment={{isset($product->auction->min_bid_increment)?$product->auction->min_bid_increment:''}}                
                var socket = io.connect("http://<?php $_SERVER['HTTP_HOST'] ?>:3005");
                socket.emit('loadAuction',{{@$product->id}});
                var obj = {};
                var last_bid='';
                $('body').delegate('#place-bid','click',function(){                            
                        if(checkBidValidation()){
                            var data = new Object();
                            data.bid_amount=$('#bid_amount').val();
                            data.user_id={{ @\Auth::id() }}
                            data.username='{{  @\Auth::user()->first_name  }}'
                            data.email='{{  @\Auth::user()->email  }}'
                            data.datetime = moment.utc().format('h:mm:s, DD MMM YYYY');
                            data.productId = {{@$product->id}}
                            data.sellerId = {{@$product->user_id}}                            
                            data.createdAt = Math.round(moment.utc(new Date()).valueOf()/ 1000);                            
                            socket.emit('place-bid', data);
                            $('#bid_amount').val('');
                        }
                });
                
                
                
                var  max_product_price=0;
                @if(isset($product->auction->mode) && $product->auction->mode=='By price')
                            max_product_price={{isset($product->auction->max_product_price)?$product->auction->max_product_price:''}}
                @endif    
                
                
                socket.on('getNewBid', function (data) {        
                    
                    //when auction is by price it should stop when bid read to particular max product price amount
                    if(max_product_price!=0 && parseFloat(data.bid_amount).toFixed(2)>max_product_price){
                        liftOff('Auction Max Price Reached!!');
                    }
        
                    html='';
                    html='<tr data-id="'+data.user_id+'">'+
                      '<td class="col1">'+data.username+'</td>'+
                      '<td class="col2">$<span class="bid_price">'+parseFloat(data.bid_amount).toFixed(2)+'</span></td>'+
                      '<td class="col3">'+data.datetime+'</td>'+
                      '</tr>';
                    $('#bid_row').prepend(html);
                
                    $('#total_bids').html($('#bid_row tr').length);
                    if(data.user_id){
                        obj[data.user_id] = data.user_id;
                        $('#bidders').html(Object.keys(obj).length);
                    }
                });
                
                
                function liftOff(msg){
                    toastr.error(msg);
                    $('#place-bid,.placeabid,#make_new_bid').remove();                    
                }
                function checkBidValidation(){                  
                        last_bid = parseFloat($('#bid_row tr ').first().find('.col2 .bid_price').html());
                        if(last_bid){
                            min_current_minimum_bid = last_bid +(last_bid*(min_bid_increment/100));
                        }
                        else{                            
                            last_bid={{@$product->auction->min_reserved_price}};
                            min_current_minimum_bid = last_bid +(last_bid*(min_bid_increment/100));
                        }
                        
                        
                        if( $('#bid_amount').val() < min_current_minimum_bid ){
                            toastr.error('Minimum Bid Increment is '+min_bid_increment+'% so you have to bid atleast '+min_current_minimum_bid);
                            return false;
                        }
                            
                        if($('#bid_amount').val()==''){
                            toastr.error('Please Enter Bid First!!');
                            return false;
                        }
                    
                        if(!$.isNumeric($('#bid_amount').val()))
                        {
                            toastr.error('Please Enter Only Numeric bid value!!');
                            return false;
                        }



                        if( parseFloat($('#bid_amount').val())<= parseFloat($('#seller_bid_offer').html()) ){
                            toastr.error('Bid price must be greater than seller bid offer!!');
                            return false;
                        }


                        
                        if(last_bid !='' &&  parseFloat($('#bid_amount').val()) <=  last_bid ){
                            toastr.error('Bid price can not be less than or equal to the current bid!!');
                            return false;
                        }
                        return true;
                }
                

                
</script>



@endpush
