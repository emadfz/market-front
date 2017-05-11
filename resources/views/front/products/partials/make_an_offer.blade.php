<!--Make Offer Modal Start-->
{!! Form::open(['url' => 'javascript:void(0)','id'=>'offer_form']) !!}
<div class="modal-inner clearfix">
    <a href="#" class="close" data-dismiss="modal">close</a>
    <div class="clearfix">
        <div class="col-md-5 col-sm-5">
            <!-- <h6>Make a new Offer</h6> -->
            @if(!empty($counterOffer))
            <h5 class="bidprice">Last Seller CounterOffer:<span>US ${{ $counterOffer }}</span></h5>
            <h5>Shipping:<span>Free</span></h5>
            @endif
            
            @if((!isset($offerData['remaining_offer']) || $offerData['remaining_offer'] > 0 ) && $offerData['status'] !='accept')
            <h3 id="make_offer_title">Make a new offer</h3>
            <div class="form-horizontal" id="make_offer_div">
                <div class="form-group">
                    <label class="control-label col-sm-3">US$</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="amount">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Quantity</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control width100 pull-left" name="offer_quantity" >
                        <span class="infotext"> @if( isset($offerData) && !empty($offerData) ) {{ $offerData['remaining_offer'] }} @else 2 @endif Available</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Note</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" cols="5" rows="4" name="note"></textarea>
                    </div>
                </div>
            </div>
            @elseif($offerData['status'] !='accept')
                <h3>You have reached maximum offer limit </h3>
            @elseif($offerData['status'] =='accept')

                <h3>Your offer has been accepted, Please click on Pay Now button for buy this product. </h3>

            @endif
        </div>
        <div class="col-md-7 col-sm-7 verticalline">
            <div class="innerbid">
                <h6>Offer History</h6>
                <div class="table-responsive">
                    <div class="offer-conversion clearfix">
                        <div class="seller">
                            <div class="thumb-pic"><img src="{{$loginSellerUserData['login_image']}}" alt="Avtar" width="61" height="61"></div>
                            <span class="body-semibold">you</span>
                        </div>
                        <div class="buyer">
                            <div class="thumb-pic"><img src="{{$loginSellerUserData['seller_image']}}" alt="Avtar" width="61" height="61"></div>
                            <div class="buyeruser">
                                <div>
                                    <span class="body-semibold">{{ $sellerData['first_name'] }} &nbsp; {{ $sellerData['last_name'] }}</span>
                                    <span class="rating">
                                        <img src="{{asset('assets/front/img/star.png')}}" align="">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ul class="conversion-chat">
                        @if( isset($offerData) && !empty($offerData) )
                            @foreach($offerData->offerDetails as $offer)
                                <li>
                                    <span class="whooffer">{{ $offer['offer_status'] }}</span>
                                    <span>US ${{ $offer['amount'] }}</span>
                                    <span>{{ date('M d, Y',strtotime($offer['updated_at'])) }}</span>
                                </li>
                                 @if( !empty($offer['counter_offer']))
                                <li class="orange">
                                    <span class="whooffer">Seller Counter Offer</span>
                                    <span>US ${{ $offer['counter_offer'] }}</span>
                                    <span>{{ date('M d, Y',strtotime($offer['updated_at'])) }}</span>
                                </li>
                                @endif
                            @endforeach
                        @endif
<!--                        <li class="orange">
                            <span class="whooffer">counter offer</span>
                            <span>US $30.00</span>
                            <span>Dec 09 2016</span>
                        </li> -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <hr>
    
    @if((!isset($offerData['remaining_offer']) || $offerData['remaining_offer'] > 0 ) && $offerData['status'] !='accept')
    <p>By Confirming Offer, You are entering into a legally binding agreement as described in <a href="#" title="Terms and Conditions" class="link">Terms and Conditions</a>, and committing to purchase the item(s), is Offer is accepted. if made a mistake,you can retract your bid within 15 minutes.</p>
    
    
    <div class="form-btnblock clearfix text-right">
        
        {{--*/ $display = 'display:none' /*--}}
        @if($loginSellerUserData['show_retract_button'] == 'yes')
        $display = 'display:inline';
        @endif
        <input type="button" id="retract_offer" title="Retract Bid" class="btn btn-primary" value="Retract Offer" style={{$display}}>
        <!-- <a href="javascript:void(0)" class="cancel-link" id="retract_offer" title="Retract Bid" data-dismiss="modal">Retract Offer</a> -->
        <input type="button" id="submit_offer_new" title="Submit Offer" class="btn btn-primary" value="Submit Offer">
    </div>
    <div style="display: none;align-items: center" id="offer_success_div">
    <h3>Thanks Your offer has been sent successfully!</h3>
    <p> Please allow up to 48 hours for a response from seller, or you can <a href="#" title="Buy Now" class="link">Buy Now</a></p>
    </div>
    @endif

    @if( @$offerData->status == 'accept' )
    <div class="form-btnblock clearfix text-right">
    <input type="button" id="pay_now" title="Pay Now" class="btn btn-primary" value="Pay Now">
    </div>
    @endif

</div>

{!! Form::close() !!}
<!--Make Offer Modal End-->
<?php $url      = $loginSellerUserData['offer_url']; 
$retract_url    = $loginSellerUserData['retract_offer_url'];
?>

<script type="text/javascript">
$("#submit_offer_new").click(function(ev) {
    var srlz = $('#offer_form').serialize();
    $.ajax({
        url: '{{$url }}',
        data: srlz,
        dataType: 'json',
        type: 'POST',
        success: function (dataofconfirm) {
           // Add row  in history
           $('.help-block').remove();
           $('.conversion-chat').append('<li><span class="whooffer">'+dataofconfirm.offer_status+'</span><span>US$'+dataofconfirm.amount+'</span><span>'+dataofconfirm.created+'</span></li>');
           $('.infotext').html(dataofconfirm.remaining+' Available');

           if(dataofconfirm.remaining == 0)
           {
                $('#make_offer_title').remove();
                $('#make_offer_div').html('<h3>You have reached maximum offer limit </h3>');
           }

           if(dataofconfirm.offer_status == 'Submitted')
           {
                $('#offer_success_div').show();
                $('#submit_offer_new').attr('disabled','disabled');
                $('#retract_offer').show();
           }
        },
        error: function (data, statusText, xhr, $form) {
            // Form validation error.
            if (422 == data.status) {
                processFormErrors($form, $.parseJSON(data.responseText));
                return;
            }
            toastr.error('Whoops! It looks like something went wrong on servers.\n\Please try again, or contact support if the problem persists.');
        }
    });
});

$('#retract_offer').click(function(){

    $.ajax({
        url: '{{$retract_url }}',
        data: '',
        dataType: 'json',
        type: 'POST',
        success: function (dataofconfirm) {
           // Add row  in history
           if(dataofconfirm.status == 'success')
           {
                $('#offer_success_div').html('Your offer has been canceled successfully!');
                $('#retract_offer').attr('disabled','disabled');
           }else{
                $('#offer_success_div').html('There is some error!');
           }
           return false;
        },
    });
});

function processFormErrors($form, errors) {

    $.each(errors, function (index, error) {

        if ((index.indexOf(".") >= 0)) {
            var selector = '.' + index.replace(/\./g, "\\.");
            $(selector, $form).after('<div class="help-block error">' + error + '</div>').parent().addClass('has-error');
        } else {
            var $input = $(':input[name=' + index + ']', $form);
            if (index == 'global_form_message') {
                toastr.error(error);
            } else if (index == 'report_value') {
                $('.error_forum_report').after('<div class="help-block error">' + error + '</div>').closest("div.form-group").addClass('has-error');
            } else if ($input.prop('type') === 'file') {
                $('#input-' + $input.prop('name')).after('<div class="help-block error">' + error + '</div>').closest("div.form-group").addClass('has-error');
            } else if ($("textarea[name=" + index + "]").hasClass("ckeditor")) {
                // for textarea having class ckeditor
                $("textarea[name=" + index + "]").next().after('<div class="help-block error">' + error + '</div>').closest("div.form-group").addClass('has-error');
            } else if ($("select[name=" + index + "]").hasClass("selectpicker")) {
                // for select box having class selectpicker
                $input.closest("div.btn-group.bootstrap-select").after('<div class="help-block error">' + error + '</div>').closest("div").parent().addClass('has-error');
            } else {
                if ($input.closest("form").hasClass("custom-wo-public")) {
                    $input.closest('.input-icon').after('<div class="help-block error">' + error + '</div>').parent().addClass('has-error');
                } else {
                    if ($input.prop('type') == 'checkbox') {
                        // for checkbox
                        $input.next().next().after('<div class="help-block error">' + error + '</div>').closest("div").addClass('has-error');
                    } else {
                        $input.after('<div class="help-block error">' + error + '</div>').closest("div").addClass('has-error');
                    }
                }

            }
        }
    });

    /*var $submitButton = $form.find('input[type=submit]');
    toggleSubmitDisabled($submitButton);*/
}

</script>


