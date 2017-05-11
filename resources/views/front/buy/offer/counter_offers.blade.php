<!--Counter Offer Modal Start-->

{!! Form::open(['url' => route('buyerSendCounterOffer',[encrypt($offer[0]->id)]),'class'=>'ajax','id'=>'counter_offer_form']) !!}
<div class="modal-inner clearfix">
    <a href="#" class="close" data-dismiss="modal">close</a>
    <div class="clearfix">
        @if($type == 'offer')
        <div class="col-md-5 col-sm-5">
            <h3 id="make_offer_title">Send Counter Offer</h3>
            <div class="form-horizontal" id="make_offer_div">
                <div class="form-group">
                    <label class="control-label col-sm-3">US$</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="amount">
                    </div>
                </div>
                <div class="form-btnblock clearfix text-right">
                    <input type="submit" id="submit_offer_new" title="Send Counter Offer" class="btn btn-primary" value="Send Counter Offer">
                </div>
            </div>
        </div>
        @endif
         @if($type == 'offer')
        <div class="col-md-7 col-sm-7 verticalline">
        @else
        <div class="col-md-12">
        @endif
            <div class="innerbid">
                <h6> @if($type == 'offer') Counter @endif Offer History</h6>
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
                        @if( isset($offer) && !empty($offer) )
                            @foreach($offer[0]->offerAllDetails as $offer)
                                <li>
                                    <span class="whooffer">{{ $offer['offer_status'] }}</span>
                                    <span>US ${{ $offer['amount'] }}</span>
                                    <span>{{ date('M d, Y',strtotime($offer['created_at'])) }}</span>
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
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    
</div>

{!! Form::close() !!}
<!--Counter Offer Modal End-->
@push('scripts')
<script type="text/javascript">
/*$("#submit_offer_new").click(function(ev) {
    var srlz = $('#counter_offer_form').serialize();
    var last_amount = $('.conversion-chat li').last().find('span:eq(1)').html().replace ( /[^\d.]/g, '' );
    $('#last_amount').val(last_amount);
    $.ajax({
        url: "",
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
});*/

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
@endpush 